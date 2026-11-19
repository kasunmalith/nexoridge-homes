<div align="center">
  <h1>Nexo Ridge Homes</h1>
  <p><strong>A property showcase with considered editorial storytelling.</strong></p>
  <p><img alt="PHP 8.2" src="https://img.shields.io/badge/PHP%208.2-243447?style=flat-square"> <img alt="MySQL" src="https://img.shields.io/badge/MySQL-243447?style=flat-square"> <img alt="Property CMS" src="https://img.shields.io/badge/Property%20CMS-243447?style=flat-square"> <img alt="GSAP" src="https://img.shields.io/badge/GSAP-243447?style=flat-square"></p>
</div>

---

## Overview

A mobile-first luxury homes website with published property listings, a journal and a hidden administrative console. The first-run admin flow can add fictional sample properties and articles to a new database.

> [!NOTE]
> This is a public source snapshot prepared from a local project. It includes implementation code and safe schema/sample data, without live records or credentials.

## What the software can do

| Area | Implemented scope |
| --- | --- |
| **Residences** | Browsable listings, detailed property pages, galleries and featured-home presentation. |
| **Journal** | Published articles with detail pages and category presentation. |
| **Enquiries** | Contact form records reviewed and status-managed in the admin area. |
| **Admin** | Listing, article, homepage and contact settings management. |
| **Discoverability** | Clean routes, canonical metadata, sitemap and robots endpoint. |

### Typical workflow

1. Staff create or publish listings and articles.
2. Visitors browse residences or stories and submit an enquiry.
3. Staff review the enquiry in the private admin console.

## Run locally

1. Use PHP 8.2, PDO MySQL and Apache `mod_rewrite`.
2. Create an empty MySQL database and import `database.sql`.
3. Set local database values in `app/bootstrap.php` (use a fresh account for any hosted deployment).
4. Open the site and visit `/admin/` to create the first admin account; the seed helper supplies fictional examples.

## Database and sample content

`database.sql` creates properties, articles, enquiries, administrators and settings tables. `app/seed.php` supplies fictional starter listings and journal entries. No live enquiries or admin credentials are published.

## Repository guide

| Path | Purpose |
| --- | --- |
| `app/bootstrap.php` | Database, helpers and shared page output |
| `app/seed.php` | Fictional starter content |
| `admin/` | Private management console |
| `assets/` | Site styles and interaction scripts |
| `database.sql` | Schema |

## Security and privacy

- Admin has no public navigation link and no default password.
- Contact requests are stored in MySQL; visitors cannot view the admin tables.

## Current boundaries

- Properties, prices and contact details are demonstration content.
- Contact submissions do not automatically send email.
- Remote images, fonts and motion libraries require an internet connection.

## Verification

Check the public listing/detail routes, first-run admin flow, enquiry submission and sitemap on a disposable install.

---

<div align="center">

Software Development by <a href="https://www.energeticedge.lk/" target="_blank" rel="noopener noreferrer">Energetic Edge (Pvt) Ltd</a>

</div>
