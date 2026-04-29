# RiftMind Maintainer README

This repository is a small PHP website for a fictional League of Legends coaching product called RiftMind. It is mostly static content with a few stateful features layered on top:

- shared header/footer templating
- service pages generated from a central catalog + body-copy map
- file-backed `News` and `Contacts` pages
- a cookie-backed "recently viewed services" feature
- a session-protected admin page
- a MySQL-backed company user list plus a JSON API for cross-site aggregation

The goal of this README is to reduce re-discovery time before making future changes.

## Project shape

Top-level pages:

- `index.php`: homepage
- `about.php`: about/mission page
- `products.php`: services index
- `recent-services.php`: shows the last 5 viewed services from a browser cookie
- `news.php`: renders announcements from `data/news.txt`, with hardcoded fallback items
- `contacts.php`: renders contacts from `data/contacts.txt`
- `secure.php`: admin-only page showing a sample local user list
- `users.php`: user section with links to create/search forms
- `user-create.php`: MySQL-backed user creation form
- `user-search.php`: MySQL-backed user search form
- `login.php` / `logout.php`: session auth flow
- `all-company-users.php`: local DB users plus remote teammate APIs

Shared code:

- `includes/header.php` / `includes/footer.php`: page shell and navigation
- `includes/auth.php`: simple session auth helper
- `includes/service_catalog.php`: canonical list of service metadata
- `includes/service_bodies.php`: long-form service copy
- `includes/recent_services.php`: cookie read/write helpers
- `includes/user_repository.php`: PDO access to local MySQL `users` table
- `includes/db_config.php`: DB credentials
- `includes/company_config.php`: local company id + remote teammate API URLs
- `includes/curl_fetch_json.php`: remote API fetch helper

Generated service pages:

- `services/*.php`: each page just sets a slug and delegates to `services/_layout.php`
- `services/_layout.php`: shared renderer for all service detail pages

Content/assets:

- `css/style.css`: site styling
- `images/services/*.svg`: service artwork
- `data/contacts.txt`: file-backed contact data
- `data/news.txt`: file-backed news data
- `sql/users.sql`: ordered schema + 20 sample seed rows for the MySQL `users` table

Other files:

- `api/company_users.php`: JSON endpoint exposing this site's local company users
- `ai-turtle.html`, `handdrawn-turtle.html`: standalone HTML experiments, not part of the main PHP site flow

## Mental model

There are really three content systems in this repo:

1. Simple standalone pages
   `index.php`, `about.php`, and `secure.php` mostly contain their own markup directly.

2. Data-driven service pages
   The services section is centralized. The card grid on `products.php` comes from `includes/service_catalog.php`, and the detail-page body copy comes from `includes/service_bodies.php`. Each file in `services/` is just a tiny entry point that sets `$service_slug`.

3. File or database backed pages
   `news.php` and `contacts.php` parse plaintext files in `data/`. The user aggregation feature uses MySQL for local users and cURL/JSON for remote users.

If you keep those three systems in mind, most changes are straightforward.

## Common change recipes

### Change header, footer, or site-wide nav

Edit:

- `includes/header.php`
- `includes/footer.php`

Notes:

- Nested service pages rely on `$site_root_prefix = '../'` inside `services/_layout.php`.
- If you add new shared assets or links, make sure the path still works from both root pages and `/services/*`.

### Change homepage or other standalone copy

Edit the relevant page directly:

- `index.php`
- `about.php`
- `secure.php`

### Add, remove, or rename a service

You usually need to update four places:

1. `includes/service_catalog.php`
   Add/update the service title, short copy, image path, and href.
2. `includes/service_bodies.php`
   Add/update the lede, paragraphs, and bullet list.
3. `services/<slug>.php`
   Create or rename the entry-point file so it sets the right `$service_slug`.
4. `images/services/<slug>.svg`
   Add or update artwork.

The recent-services cookie feature also depends on the slug existing in `service_catalog.php`. If a slug is removed from the catalog, older cookie entries are silently ignored.

### Change the "recently viewed services" behavior

Edit:

- `includes/recent_services.php`
- `recent-services.php`

Current behavior:

- cookie name: `riftmind_recent_services`
- stores up to 5 unique service slugs
- newest visit is first
- TTL is 30 days
- scoped to the site folder path computed from `$_SERVER['SCRIPT_NAME']`

This feature is browser-local only. It is not stored in the database.

### Update contacts

Edit `data/contacts.txt`.

Expected format:

```txt
Name: Jane Doe
Role: Support
Email: jane@example.com
Phone: 555-123-4567
---
Name: Another Person
Role: Sales
Email: sales@example.com
Phone: 555-987-6543
```

`contacts.php` splits records on `---` and parses `Key: value` lines.

### Update news

Edit `data/news.txt`.

Expected format:

```txt
Date: 2026-04-13
Title: Example announcement
Body: Short paragraph here.
---
Date: 2026-04-01
Title: Another update
Body: Another paragraph here.
```

If `data/news.txt` is missing or parses to no items, `news.php` falls back to built-in sample announcements.

### Change login behavior or admin credentials

Edit:

- `includes/auth.php`
- `login.php`
- `secure.php`

Important current behavior:

- auth is session-based
- admin credentials are hardcoded in `includes/auth.php`
- current default credentials are `admin` / `admin123`
- redirect sanitizing in `login.php` is intentionally simple and restrictive

This is fine for a class project/demo, but it is not production-grade authentication.

### Change the local company user list / cross-company aggregation

Edit:

- `includes/db_config.php`
- `includes/user_repository.php`
- `includes/company_config.php`
- `api/company_users.php`
- `all-company-users.php`
- optionally `sql/users.sql`

Flow:

1. `all-company-users.php` loads local users through PDO from the `users` table.
2. It then calls each teammate URL from `get_remote_user_api_urls()`.
3. Each remote site is expected to expose the same JSON shape from `api/company_users.php`.

Expected remote response shape:

```json
{
  "company": "A",
  "users": [
    {
      "name": "Mary Smith",
      "email": "mary.smith@example.com",
      "joined": "2025-01-15",
      "plan": "Pro"
    }
  ]
}
```

## Setup notes

### Database

The local company user feature expects a MySQL table named `users`.

Use `sql/users.sql` to create it. For DreamHost, create the database through the DreamHost panel first, then select that database in phpMyAdmin and run the SQL file in order.

`includes/user_repository.php` expects these columns:

- `id`
- `first_name`
- `last_name`
- `email`
- `home_address`
- `home_phone`
- `cell_phone`
- `joined`
- `plan`

### Company config

Update `includes/company_config.php` before deployment:

- set `COMPANY_ID` to your assigned letter
- replace `TEAMMATE_*_DOMAIN` placeholders with real teammate URLs

### PHP extensions/features used

This code assumes:

- sessions
- PDO with MySQL driver
- cURL for remote company fetches
- JSON support

## Risks and gotchas

- `includes/db_config.php` currently contains real-looking credentials in plain text. Treat that file as sensitive and consider moving secrets to environment-specific config outside version control.
- `secure.php` does not use the database. It shows a hardcoded sample array of users.
- `all-company-users.php` does use the database. Do not assume the secure page and company API are backed by the same source.
- Service detail pages depend on both `service_catalog.php` and `service_bodies.php`. Updating only one will cause missing content or a 404/500-style failure path.
- Header/footer links are duplicated in two files. If navigation changes, update both.
- `news.php` and `contacts.php` use permissive plaintext parsing. Malformed records are usually skipped rather than throwing explicit errors.
- `api/company_users.php` returns a generic error payload on failure, not the raw exception message.

## Suggested next cleanup items

If this project gets touched again, the highest-value simplifications would be:

- move DB credentials out of `includes/db_config.php`
- centralize shared navigation links in one include instead of header/footer duplication
- move `secure.php` to the same data source as `all-company-users.php`, if that is the desired behavior
- add a small local run/deploy note once the hosting workflow is finalized
- add basic input validation or admin editing tools for `news` and `contacts` if those files will change often

## Verification status

I wrote this README from the current codebase structure and file contents.

I could not run PHP locally in this environment because the `php` CLI is not installed here, so runtime verification is still worth doing on the target host or a PHP-enabled local setup.
