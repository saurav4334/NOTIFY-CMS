# NotifySMS CMS

A CMS-driven website and admin portal for the NotifySMS bulk-SMS platform.

This is a single, complete Laravel project: the Laravel backend (admin panel,
REST API, database, auth, RBAC, media library) lives at the repository root, and
the static marketing frontend (HTML5 + TailwindCSS + vanilla JS) is bundled in
[`frontend/`](frontend/) and served by `SiteController` under `/site/`. The
frontend pages fetch live content from the `/api/v1` endpoints.

## Features

- **Admin panel** (`/admin`) — session auth with role-based access (Super Admin / Content Manager / Support Admin).
- **CMS modules** — homepage hero, trusted clients, why-us, testimonials, services, pricing slabs, FAQ, contact info, SEO, and an About-page editor.
- **Contact leads inbox** — public contact form submissions, filterable, with CSV export.
- **Media library** — uploads with validation and automatic image downscaling (native GD).
- **Public API** (`/api/v1`) — `content`, `sms-rates`, `calculate`, `contact`.
- **SEO** — dynamic `sitemap.xml` and `robots.txt`.

## Getting started (backend)

Requires PHP 8.3+, Composer, and (optionally) MySQL 8. SQLite works out of the box for local dev.

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve            # http://127.0.0.1:8000
```

- **Frontend:** http://127.0.0.1:8000/site/index.html
- **Admin:** http://127.0.0.1:8000/admin/login

### Seeded admin accounts (change before deploying)

| Email | Password | Role |
| ----- | -------- | ---- |
| admin@notifysms.com.bd | `password` | Super Admin |
| content@notifysms.com.bd | `password` | Content Manager |
| support@notifysms.com.bd | `password` | Support Admin |

## Production notes

- Switch `DB_CONNECTION` to `mysql` in `.env` (a ready-to-uncomment block is provided).
- The dev SQLite database is **not** committed; run `php artisan migrate --seed` after cloning.
