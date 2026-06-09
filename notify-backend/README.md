# NotifySMS CMS — Backend

Laravel backend for the NotifySMS bulk-SMS marketing site & admin portal.
This is the **scaffold**: database schema, Eloquent models, seeded content
(imported from the original `cms.js` prototype), Sanctum API auth, and a working
public API. Admin CRUD, the Blade admin panel, and media manager are stubbed for
the next phase.

## Stack

| Layer    | Choice                                             |
| -------- | -------------------------------------------------- |
| Backend  | Laravel **13.14** (PRD said 12; 13 is current stable) |
| PHP      | **8.3.31** (installed at `C:\php83` — XAMPP's 8.1 is too old for Laravel 13) |
| DB (dev) | **SQLite** — zero-config, already migrated & seeded |
| DB (prod)| **MySQL 8** — switch in `.env` (see below)          |
| Auth     | Laravel **Sanctum** (API tokens) + session for the web admin |

## Quick start (local)

```bash
# from the notify-backend folder, using the PHP 8.3 binary:
C:\php83\php.exe artisan migrate:fresh --seed   # rebuild DB + demo content
C:\php83\php.exe artisan serve                  # http://127.0.0.1:8000
```

Seeded **super admin** login: `admin@notifysms.com.bd` / `password`
(change before any real deployment).

> Tip: add `C:\php83` to your PATH so you can just type `php artisan ...`.

## Switching to MySQL (production / cPanel)

1. In `.env`, comment `DB_CONNECTION=sqlite` and uncomment the MySQL block.
2. Create the database `notifysms_cms`.
3. `php artisan migrate --seed`.

## Database schema

Mirrors PRD §11 and the `cms.js` data structures:

| Table               | Source in cms.js / PRD                  |
| ------------------- | --------------------------------------- |
| `roles` + `users`   | RBAC: super_admin / content_manager / support_admin |
| `settings`          | `settings` + `contactInfo` (key/value, grouped) |
| `homepage_sections` | `hero`, `whyUs` (JSON blocks)           |
| `services`          | PRD §6.5 (Promotional, Transactional, OTP, API, Voice) |
| `sms_rates`         | `pricingNM` / `pricingM` (slab pricing) |
| `pricing_plans`     | Named/enterprise plans                  |
| `faqs`              | `faq`                                   |
| `clients`           | `clients` (20 trusted logos)            |
| `testimonials`      | `testimonials`                          |
| `contact_leads`     | Contact form submissions                |
| `media`             | Uploads (media manager)                 |
| `activity_logs`     | Admin audit trail                       |
| `seo_meta`          | `seo` (per-page meta + OG/schema)       |

## API

Public (no auth), prefix `/api/v1`:

| Method | Endpoint      | Purpose                                  |
| ------ | ------------- | ---------------------------------------- |
| GET    | `/sms-rates`  | All active slab rates (pricing page)     |
| POST   | `/calculate`  | `{type, quantity}` → cost (calculator)   |
| POST   | `/contact`    | Store a contact lead (throttled 10/min)  |

Admin (Sanctum token), prefix `/api/admin` — `GET /user` works; CRUD is TODO.

```bash
curl -X POST http://127.0.0.1:8000/api/v1/calculate \
  -H "Content-Type: application/json" \
  -d '{"type":"masking","quantity":15000}'
```

## What's built vs. next

**Done (this scaffold)**
- 14 migrations + models with relations/casts/scopes
- Seeders importing all `cms.js` defaults + roles + super admin
- Sanctum installed; public API (rates, calculator, contact) working
- RBAC helpers on `User` (`hasPermission`, `isSuperAdmin`)
- `ActivityLog::record()` audit helper

**Next phase**
- Admin auth controllers + Blade admin panel (integrate existing `admin.html`)
- CMS CRUD controllers/resources for every module
- Media manager (upload/optimize/delete) backed by `media` table
- Wire the existing static frontend to the API (replace `cms.js` localStorage)
- Per-page SEO rendering, sitemap.xml, robots.txt
- Role middleware/policies; 2FA; password reset

## Notes / decisions
- Laravel 13 (not 12) because that's the current stable and PHP 8.3 supports it.
- `cms.js` `whyUs` & `hero` stored as JSON in `homepage_sections` rather than
  rigid columns, to keep section editing flexible.
- VAT is configurable via `settings(group=pricing, key=vat_percent)`, default 0
  (seeded rates already include taxes per the original copy).
