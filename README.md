# Wedora PHP

Plain PHP rebuild of Wedora, a Kerala wedding-vendor marketplace. It uses server-rendered PHP pages, vanilla JS fetch calls, PHP sessions for no-login visitor state, and optional MySQL via PDO.

## Run locally

```bash
cd wedora-php
php -S localhost:8000 -t public
```

Open `http://localhost:8000`.

## Optional MySQL

1. Copy `.env.example` to `.env`.
2. Fill in the `DB_*` values.
3. Import `sql/schema.sql`.

If no database is configured, the app still runs from the PHP seed files in `data/` and persists saved vendors, checklist progress, enquiries, registrations, and concierge chat in the browser session.

## Optional AI concierge

Set `ANTHROPIC_API_KEY` in `.env`. The browser never receives the key; `public/api/concierge.php` proxies chat requests server-side and falls back to a local planning response when no key is present.
