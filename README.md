# Antimonial application skeleton

This is the starter application for the [Antimonial](https://github.com/antimonial/framework)
framework. It is the canonical "what an app looks like" reference.

## Requirements

- PHP >= 8.1
- Composer
- A database — MySQL, PostgreSQL, or SQLite (only MySQL is wired up in this
  skeleton by default; see `app/Config/database.php` to add other connections)

## Install

```bash
composer create-project antimonial/antimonial my-app
cd my-app
```

The `post-create-project-cmd` script copies `.env.example` to `.env` for you.

## Configure

Edit `.env`:

```dotenv
APP_DEBUG=false
DB_HOST=127.0.0.1
DB_NAME=myapp
DB_USER=root
DB_PASS=
```

(Optionally set `APP_DEBUG=true` while developing.)

The front controller auto-loads `.env` via `Antimonial\Core\DotEnv`, so the
values are available to `env()` and `Config::load` without any extra step.

## Run

```bash
php -S localhost:8000 -t public public/index.php
```

Then open <http://localhost:8000>. The front controller (`public/index.php`)
ships with the skeleton, so you do **not** need to create it.

Routes can be named for reverse URL generation — see the [framework README](https://github.com/antimonial/framework)
for named routes and the `route()` helper.

## Keeping in sync with the framework

The skeleton follows the framework **automatically** — no manual re-release is
needed. A scheduled GitHub Actions workflow (`Sync Framework`) runs daily and:

1. Reads the latest `antimonial/framework` tag from GitHub.
2. If it is newer than the latest skeleton tag, it updates the dependency,
   regenerates `composer.lock`, commits, and **tags the skeleton with the same
   version number** as the framework (e.g. framework `v0.9.6` → skeleton `v0.9.6`).
3. Pushes the new tag — Packagist picks it up automatically.

Notes:

- `composer.json` requires `"antimonial/framework": "~0.9"` (the `~MAJOR.MINOR`
  constraint is bumped automatically when the framework moves to a new
  major/minor, e.g. `0.10` or `1.0`).
- `composer.lock` **is** committed (reproducible installs) and refreshed by the
  workflow on every sync, so `composer create-project` gives users the exact
  framework release of that skeleton tag.
- You can trigger a sync at any time from the **Actions → Sync Framework →
  Run workflow** button (`workflow_dispatch`).

## Views & Template Engine

The skeleton ships on top of the framework's built-in template engine
(see the [framework README](https://github.com/antimonial/framework) for the
full syntax). Views are plain PHP files in `app/Views/` that compile to
cached PHP on first render — no setup required.

Key points:

- **Auto-escaping by default.** `{{ $name }}` is XSS-safe; `{{{ $content }}}`
  emits raw, trusted HTML (used here for the layout's `$content` slot).
- **Loops & conditionals:** `@foreach($users as $user) … @endforeach`,
  `@if` / `@else` / `@endif`, `@unless`, `@for`, `@while`.
- **Layouts:** a view uses `@extends('layouts/main')` and fills slots with
  `@section('title') … @endsection`; the parent exposes them with
  `@yield('title', 'Default')` and receives the view body as `{{{ $content }}}`.
- **Includes:** `@include('partial', ['foo' => 'bar'])`.
- **Filters:** `{{ $name|upper }}` (extensible via `Antimonial\View\Filters::add()`).

The view path is initialized in `public/index.php` via
`View::setViewPath(ROOT_PATH . '/app/Views')`. Compiled templates are written
 to `app/storage/views/` (ignored by git).

## Structure

```
antimonial/
├── public/            # Web root: index.php (front controller)
├── bootstrap/        # app.php builds the Antimonial\Core\App instance
├── app/
│   ├── Config/       # app.php, database.php (loaded by Config::load)
│   ├── Routes/       # web.php (route definitions)
│   ├── Controllers/  # your controllers (App\Controllers\*)
│   ├── Models/       # your models (App\Models\*)
│   └── Views/        # PHP views + layouts/ (built-in template engine)
└── composer.json
```

## Documentation

- [Framework Wiki](https://github.com/antimonial/framework/wiki) — routing, the template engine, the query builder, sessions, CSRF, and the full API reference.
- [Framework README](https://github.com/antimonial/framework) — quick start, what's included, and security notes.

## License

MIT
