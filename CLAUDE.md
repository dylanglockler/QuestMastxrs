# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What This App Is

Questmastxrs — a site for free, public, real-world treasure hunts. Hosts hide laminated clue cards around a city (parks, neighborhoods); each clue leads to the next and ships with 3 progressively-revealing hints. Guests ("questers") browse a hunt's clues at their own pace (no accounts, just a nickname), leave tips for each other on a per-clue message board, and post celebratory photos to a hunt's gallery once they finish. Hosts manage everything from a Filament admin panel at `/admin`. Vibe: friendly, neighborly, adventurous, cryptic, home-grown, a bit snarky.

Production domain: www.questmastxrs.com (registered at Porkbun, hosted on Cloudways). Local dev: `http://questmastxrs.test` via Laravel Herd.

## Commands

```bash
composer setup    # first-time bootstrap: install, key gen, migrate, npm install, build
composer dev       # server + queue listener + pail logs + vite, concurrently
composer test      # clears config cache, then runs the full PHPUnit suite
php artisan test --filter=TestClassName   # run a single test
./vendor/bin/pint  # format code
npm run build      # production asset build
```

Don't use `php artisan serve` for anything that involves file uploads (photo posting) — PHP's built-in dev server has a known bug on Windows that breaks multipart uploads. Use the Herd-served `questmastxrs.test` instead.

## Architecture

**Stack:** Laravel 13 / PHP 8.4, Filament 5, Tailwind CSS 4, Vite 8, Alpine.js, SQLite (file-backed in dev, in-memory for tests). Mail via Postmark (`log` driver locally).

### Domain model

- **`Hunt`** — `title`, `slug` (route key), `tagline`, `description`, `city`, `neighborhood`, `cover_image`, `status` (draft/active/archived), `starting_hint`, `published_at`. `hasMany` `Clue` (ordered) and `Photo`.
- **`Clue`** — belongs to `Hunt`, has `order`, `riddle_text`, `location_note` (general, non-spoiler area). `hasMany` `Hint` (ordered) and `Message`.
- **`Hint`** — belongs to `Clue`, fixed at exactly 3 per clue (`order` 1–3), managed in the admin as a Filament Repeater rather than a standalone resource.
- **`Message`** — a per-clue message board post (nickname + body). `hidden_at`/`hidden_by` support reversible host takedown; the `visible()` scope filters these out publicly.
- **`Photo`** — a per-hunt celebratory photo (nickname + caption + storage path), same `hidden_at`/`hidden_by`/`visible()` takedown pattern as `Message`.

### Public flow (`routes/web.php`)

- `HomeController@index` — lists active hunts.
- `HuntController@show` — the hunt page: intro, all clues open (self-paced, no gating), each clue's 3 hints click-to-reveal via Alpine, each clue's message board, and a photo gallery/upload form at the bottom.
- `MessageController@store` / `PhotoController@store` — validate and persist quester submissions, remember the nickname in a cookie, and notify hosts via `NewSubmissionNotification`.

### Admin panel (`/admin`, Filament)

- `HuntResource` — CRUD on hunts, with a `CluesRelationManager` (clues + a 3-item hints Repeater) nested inside.
- `MessageResource` / `PhotoResource` — moderation queues with hide/unhide row actions (soft takedown, not hard delete).

### Permissions & Auth

Spatie `laravel-permission` gates `/admin` to the `host` role (`User::canAccessPanel()`). Hosts are seeded by `AdminSeeder` (Cliff, Alex, Evaline); `HuntSeeder` adds one example hunt ("The Riverbend Ramble") for local testing — run it manually via `php artisan db:seed --class=HuntSeeder`, it's not wired into `DatabaseSeeder` by default.

### Notifications

`NewSubmissionNotification` mails every `host`-role user whenever a `Message` or `Photo` is created, dispatched directly from the storing controller (no queue/job indirection).
