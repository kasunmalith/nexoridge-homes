<?php
declare(strict_types=1);
session_start();
define('BASE_PREFIX', rtrim(preg_replace('#/admin$#', '', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));
const SITE_NAME = 'Nexo Ridge Homes';
const SITE_URL = 'https://nexoridgehomes.online';
const DB_HOST = 'localhost';
const DB_NAME = 'nexoridge_homes';
const DB_USER = 'root';
const DB_PASS = '';

function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    return $pdo;
}
function e(mixed $value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { return rtrim(SITE_URL, '/') . ($path ? '/' . ltrim($path, '/') : '/'); }
function site_path(string $path=''): string { return BASE_PREFIX . '/' . ltrim($path, '/'); }
function setting(string $key, string $default = ''): string {
    try { $s=db()->prepare('SELECT setting_value FROM settings WHERE setting_key=?'); $s->execute([$key]); return (string)($s->fetchColumn() ?: $default); }
    catch (Throwable $e) { return $default; }
}
function csrf(): string { if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32)); return $_SESSION['csrf']; }
function csrf_check(): void { if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('Session expired. Please go back and try again.'); } }
function property_rows(int $limit=30): array { $s=db()->prepare('SELECT * FROM properties WHERE is_published=1 ORDER BY is_featured DESC, id DESC LIMIT ?'); $s->bindValue(1,$limit,PDO::PARAM_INT); $s->execute(); return $s->fetchAll(); }
function article_rows(int $limit=6): array { $s=db()->prepare('SELECT * FROM articles WHERE is_published=1 ORDER BY published_at DESC, id DESC LIMIT ?'); $s->bindValue(1,$limit,PDO::PARAM_INT); $s->execute(); return $s->fetchAll(); }
function image_url(string $url): string { return $url ?: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1600&q=85'; }
function head(string $title, string $description='', string $image=''): void {
    ob_start(static function(string $html): string { return BASE_PREFIX === '' ? $html : preg_replace_callback('/((?:href|src|action)=["\'])\/(?!\/)/i', static fn($m)=>$m[1].BASE_PREFIX.'/', $html); });
    $d=$description ?: 'Discover considered residences and enduring places across Sri Lanka with Nexo Ridge Homes.';
    $canonicalPath=parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH) ?: '/'; if(BASE_PREFIX!==''&&str_starts_with($canonicalPath,BASE_PREFIX))$canonicalPath=substr($canonicalPath,strlen(BASE_PREFIX));
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="theme-color" content="#14251f"><title>'.e($title).' | '.SITE_NAME.'</title><meta name="description" content="'.e($d).'"><link rel="canonical" href="'.e(url(ltrim($canonicalPath, '/'))).'">';
    echo '<meta property="og:type" content="website"><meta property="og:title" content="'.e($title).' | '.SITE_NAME.'"><meta property="og:description" content="'.e($d).'">'.($image?'<meta property="og:image" content="'.e($image).'">':'').'<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="/assets/site.css"><script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script><script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script><script src="/assets/site.js" defer></script></head><body>';
}
function nav(): void { echo '<header class="nav"><a class="brand" href="/" aria-label="Nexo Ridge Homes home"><span class="brand-mark">N<span>R</span></span><span>NEXO RIDGE<small>HOMES · SRI LANKA</small></span></a><button class="menu-toggle" aria-label="Open menu" aria-expanded="false"><i></i><i></i></button><nav class="nav-links"><a href="/properties">Residences</a><a href="/about">Our approach</a><a href="/journal">Journal</a><a href="/contact" class="nav-cta">Enquire <span>↗</span></a></nav></header>'; }
function footer(): void { echo '<footer class="footer"><div class="footer-top"><a class="brand brand-light" href="/"><span class="brand-mark">N<span>R</span></span><span>NEXO RIDGE<small>HOMES · SRI LANKA</small></span></a><p>Places with presence.<br>Homes with a point of view.</p><a class="round-link" href="/contact">Start a conversation ↗</a></div><div class="footer-bottom"><span>© '.date('Y').' Nexo Ridge Homes. All rights reserved.</span><span><a href="/privacy">Privacy & cookies</a> · Colombo, Sri Lanka</span></div><div class="software-credit">Software Development by <a href="https://www.energeticedge.lk/" target="_blank" rel="noopener noreferrer">Energetic Edge (Pvt) Ltd</a></div></footer>'; }
function cookie_banner(): void { echo '<aside class="cookie-banner" hidden id="cookie-banner"><div><strong>A considered experience.</strong><p>We use essential cookies to make this website work and optional analytics cookies to understand how it is used. <a href="/privacy">Read our privacy & cookie policy</a>.</p></div><div class="cookie-actions"><button class="cookie-settings">Settings</button><button class="cookie-essential">Essential only</button><button class="cookie-accept">Accept all</button></div></aside>'; }
function end_page(): void { footer(); cookie_banner(); echo '</body></html>'; if(ob_get_level()) ob_end_flush(); }
function property_card(array $p): void { echo '<article class="property-card reveal"><a class="property-image" href="/homes/'.e($p['slug']).'/"><img loading="lazy" src="'.e(image_url($p['image_url'])).'" alt="'.e($p['title']).' in '.e($p['location']).'"><span class="property-badge">'.e($p['category']).'</span><span class="image-arrow">↗</span></a><div class="property-meta"><span>'.e($p['location']).'</span><span>'.e($p['bedrooms']).' beds · '.e($p['area_sqft']).' sq ft</span></div><h3><a href="/homes/'.e($p['slug']).'/">'.e($p['title']).'</a></h3><p class="property-price">'.e($p['price_label']).'</p></article>'; }
function article_card(array $a): void { echo '<article class="journal-card reveal"><a href="/journal/'.e($a['slug']).'" class="journal-image"><img loading="lazy" src="'.e(image_url($a['image_url'])).'" alt="'.e($a['title']).'"></a><div class="journal-meta">'.e($a['category']).' <span>·</span> '.date('M j, Y',strtotime($a['published_at'])).'</div><h3><a href="/journal/'.e($a['slug']).'">'.e($a['title']).'</a></h3><p>'.e($a['excerpt']).'</p><a class="text-link" href="/journal/'.e($a['slug']).'">Read story ↗</a></article>'; }
