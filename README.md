# Nexo Ridge Homes

> A luxury property showcase with editorial pages and private administration.

## What it does

- Residence listings and property detail pages
- Journal, about, contact and privacy pages
- Private admin for listings, articles, settings and enquiries
- Sitemap, robots endpoint and SEO-friendly routes

## Technology

PHP 8.2, MySQL, JavaScript, GSAP

## Local setup

1. Create a local MySQL database and import `database.sql`.
2. Set local database values in `app/bootstrap.php` or its environment configuration.
3. Open the site in WAMP, then create the first admin account.

## Database and sample data

`database.sql` contains table definitions only; application seed code adds fictional sample content.

Only schema and fictional starter data are intended for this public repository. Live customer, patient, order and enquiry records are never part of this snapshot.

## Publication note

This repository is a source snapshot. It does not include deployment credentials, live database records, customer uploads or server backups. Create fresh secrets and review the application before production use.

## Development credit

Software Development by [Energetic Edge (Pvt) Ltd](https://www.energeticedge.lk/)
