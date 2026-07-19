# NotifyBD — Static Website (v2)

Bulk SMS Solutions in Bangladesh · <https://notifybd.com>

A **pure static website**. Plain HTML, CSS and JavaScript, plus one small PHP
endpoint for the contact form. There is **no build step, no Node.js, no npm, no
framework, no database, no CMS**. What is in this repository is exactly what runs
on the server.

To change the site, edit the files directly and deploy. There is nothing to
compile.

---

## Project overview

- **10 pages**, each a real `index.html` in its own folder, giving clean URLs
  (`/pricing/`, `/about/`, …) with no URL rewriting required.
- **One stylesheet**, `assets/css/global.css` — self-contained, no CDN.
- **Vanilla JS**, loaded with `defer`; no bundler, no modules, no dependencies.
- **Self-hosted font** (Inter) and **inline-then-flattened SVG icons** — the site
  makes **zero third-party requests**.
- **Animated brand logo** in the header (pure CSS; disables under
  `prefers-reduced-motion`).
- **Contact form** posts to `api/lead.php`, which validates, stores and emails
  the lead.

---

## Folder structure

```
/
├── index.html                  Home
├── about/index.html            About
├── services/index.html         Services
├── pricing/index.html          Pricing
├── calculator/index.html       SMS cost calculator
├── faq/index.html              FAQ
├── contact/index.html          Contact (form → api/lead.php)
├── privacy/index.html          Privacy policy
├── terms/index.html            Terms & conditions
├── 404.html                    Custom 404
│
├── assets/
│   ├── css/global.css          The entire stylesheet
│   ├── js/                     main, home, pricing, pricing-config,
│   │                           calculator, faq, contact  (vanilla, editable)
│   ├── fonts/inter-latin.woff2 Self-hosted Inter (weights 400–800)
│   └── images/                 Logo, icon, Open Graph image
│
├── api/
│   ├── lead.php                Contact-form endpoint (the only server code)
│   ├── .env.example            Copy to api/.env on the server and fill in
│   └── .htaccess               Denies everything in api/ except lead.php
│
├── .htaccess                   HTTPS, www→non-www, caching, security, 404
├── robots.txt
├── sitemap.xml
├── manifest.webmanifest
├── favicon.png
├── .cpanel.yml                 cPanel Git deployment recipe
└── README.md
```

---

## Editing the site

| To change… | Edit… |
|---|---|
| Page content | the relevant `*/index.html` |
| Styling | `assets/css/global.css` (add rules at the end, or edit inline `style=""`) |
| Prices | `assets/js/pricing-config.js` — drives the calculator |
| Behaviour | the matching file in `assets/js/` |
| Contact recipient / storage | `api/.env` on the server (never committed) |

### Two things to know without a build step

1. **The header and footer are repeated in every page.** With no build tool to
   inject shared partials, if you change the nav or footer you must make the same
   edit in all 10 HTML files. Keep them in sync.
2. **`global.css` is a fixed stylesheet.** The utility classes used across the
   pages already exist in it. If you add a *brand-new* class to some HTML, add
   the corresponding CSS rule to `global.css` yourself — there is no compiler to
   generate it.

---

## Contact API endpoint

`api/lead.php` — the only server-side code. Plain PHP (7.4+), no framework, no
database.

- **POST only** (`GET` → 405).
- Accepts form-data or JSON.
- Server-side validation: name, Bangladeshi mobile number, optional email,
  message; optional company and SMS volume.
- Anti-spam: honeypot field, submit-time trap, per-IP rate limiting.
- Security: strips CR/LF from every field (mail-header-injection defence), CORS
  restricted to `notifybd.com`, no path or error disclosure.
- Stores each lead as a line of JSON (JSONL), ideally **outside** the web root,
  and emails a notification. If email fails the lead is still stored and the
  response says so — it never claims success when a lead was lost.

### Configure it (once, on the server)

```bash
cd api
cp .env.example .env
nano .env          # LEAD_TO_EMAIL, ALLOWED_ORIGINS, LEAD_STORAGE_PATH
chmod 600 .env
```

`api/.env` is **never** committed. Put lead storage outside `public_html`:

```bash
mkdir -p /home/USER/notifybd-storage && chmod 750 /home/USER/notifybd-storage
# then in api/.env:  LEAD_STORAGE_PATH=/home/USER/notifybd-storage
```

---

## cPanel deployment

The server tracks the **`production`** branch. No Node, npm, Composer or rsync is
required.

### First deployment (cPanel Git UI)

1. cPanel → **Git™ Version Control** → **Create** → clone this repo, branch
   `production`.
2. Edit `.cpanel.yml` and set `DEPLOYPATH` to your document root (it ships blank
   on purpose, so a misconfigured deploy fails loudly instead of writing to the
   wrong place). Typical values:
   - Primary domain: `/home/USER/public_html`
   - Addon domain: `/home/USER/notifybd.com`
3. Create `api/.env` on the server (see above).
4. **Manage → Pull or Deploy → Update from Remote → Deploy HEAD Commit.**

`.cpanel.yml` then backs up the current document root, preserves `api/.env`,
`api/storage/` and `.well-known/`, clears the old files, copies this site in,
sets permissions, and verifies `index.html`, `api/lead.php` and `.htaccess`
landed.

### Later deployments

Push your changes, then in cPanel: **Update from Remote → Deploy HEAD Commit.**

### Terminal deployment (no rsync)

```bash
cd /home/USER/repositories/NOTIFY-CMS
git fetch origin && git checkout production && git reset --hard origin/production
export DEPLOYPATH=/home/USER/public_html          # your document root
tar -czf ~/notifybd-backup-$(date +%F-%H%M%S).tar.gz -C "$DEPLOYPATH" .
cp -p  "$DEPLOYPATH/api/.env"    ~/env.keep 2>/dev/null || true
cp -Rp "$DEPLOYPATH/api/storage" ~/storage.keep 2>/dev/null || true
find "$DEPLOYPATH" -mindepth 1 -maxdepth 1 ! -name '.well-known' ! -name 'cgi-bin' -exec rm -rf {} +
cp -R ./. "$DEPLOYPATH/"
rm -rf "$DEPLOYPATH/.git" "$DEPLOYPATH/.cpanel.yml"
cp -p  ~/env.keep     "$DEPLOYPATH/api/.env"    2>/dev/null || true
cp -Rp ~/storage.keep "$DEPLOYPATH/api/storage" 2>/dev/null || true
find "$DEPLOYPATH" -type d -exec chmod 755 {} + ; find "$DEPLOYPATH" -type f -exec chmod 644 {} +
chmod 600 "$DEPLOYPATH/api/.env" 2>/dev/null || true
```

### Verify after deploying

```bash
for p in / /about/ /services/ /pricing/ /calculator/ /faq/ /contact/ /privacy/ /terms/; do
  printf "%-14s %s\n" "$p" "$(curl -s -o /dev/null -w '%{http_code}' https://notifybd.com$p)"
done
curl -s -o /dev/null -w "404:  %{http_code}\n" https://notifybd.com/nope          # 404
curl -s -o /dev/null -w "GET api: %{http_code}\n" https://notifybd.com/api/lead.php # 405
curl -s -o /dev/null -w ".env: %{http_code}\n" https://notifybd.com/api/.env       # 403/404
```

### Rollback

Every deploy writes a tarball to `~/notifybd-backup-*.tar.gz`:

```bash
cd "$DEPLOYPATH" && find . -mindepth 1 -maxdepth 1 ! -name '.well-known' -exec rm -rf {} +
tar -xzf ~/notifybd-backup-YYYY-MM-DD-HHMMSS.tar.gz -C .
```

---

© 2026 NotifyBD. All rights reserved.
