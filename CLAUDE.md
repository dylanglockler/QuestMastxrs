# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project state

This is a **fresh Laravel 13 skeleton** (project name: QuestMastxrs). Aside from framework scaffolding it currently contains no custom domain code — `routes/web.php` serves only the `welcome` view, and `app/` holds just the default `User` model, base `Controller`, and `AppServiceProvider`. Expect to be building features from the ground up rather than fitting into an existing architecture.

## Stack

- **PHP ^8.3**, **Laravel Framework ^13.8**
- **SQLite** by default — the DB is a single file at `database/database.sqlite` (already present; migrations run against it)
- **Vite ^8** + **Tailwind CSS v4** (via `@tailwindcss/vite`) for the frontend; entry points `resources/css/app.css` and `resources/js/app.js`
- **Laravel Herd** is the local dev environment (repo lives under `~/Herd/`), so the app is typically served at `http://questmastxrs.test` without running `artisan serve`

## Commands

```bash
composer dev      # runs server + queue listener + pail logs + vite concurrently (main dev loop)
composer setup    # first-time bootstrap: install, key gen, migrate, npm install, build
composer test     # clears config cache, then runs the full PHPUnit suite
./vendor/bin/pint # format code (Laravel Pint — the linter/formatter for this project)
npm run build     # production asset build
```

Run a single test:

```bash
php artisan test --filter=ExampleTest          # by class/method name
php artisan test tests/Feature/ExampleTest.php # by file
```

Other useful:

```bash
php artisan migrate            # apply migrations
php artisan pail               # tail application logs
php artisan tinker             # REPL
```

## Testing

- PHPUnit (config in `phpunit.xml`), split into `tests/Unit` and `tests/Feature`, base class `tests/TestCase.php`.
- The test environment (see `phpunit.xml`) forces `APP_ENV=testing`, an **in-memory SQLite** database (`DB_DATABASE=:memory:`), and array drivers for cache/mail/queue/session — tests never touch `database/database.sqlite`.

## Conventions specific to this repo

- **Formatting is Pint, not PHP-CS-Fixer directly** — always run `./vendor/bin/pint` before considering a change done.
- **App bootstrapping lives in `bootstrap/app.php`**, not in HTTP kernel / route-service-provider files (Laravel 11+ style). Register middleware, exception handling, and routing there. Note: JSON error rendering is already enabled for `api/*` request paths.
- There is **no `routes/api.php` yet** — add API routing via `->withRouting(api: ...)` in `bootstrap/app.php` if/when an API surface is needed.
