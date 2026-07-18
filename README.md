# Antimonial application skeleton

This is the starter application for the [Antimonial](https://github.com/antimonial/framework)
framework (v0.18.3). It is a small but complete **personal blog** demo that
exercises the framework's core capabilities end to end:

- **Migrations** via the bundled `petri` CLI
- **Authentication** (register / login / logout) with hashed passwords
- **CSRF protection** on every state-changing form
- **Sessions** for auth, flash messages, and form re-population
- **File uploads** (post images) with validation
- **Ownership-based authorization** (you can only edit/delete your own posts)

## Requirements

- PHP >= 8.1
- Composer
- A database — MySQL or SQLite (SQLite works out of the box for local dev;
  see `app/Config/database.php` to switch to MySQL)

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
DB_CONNECTION=sqlite
DB_NAME=database.sqlite
```

For MySQL instead:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_NAME=myapp
DB_USER=root
DB_PASS=
```

The front controller auto-loads `.env` via `Antimonial\Core\DotEnv`, so the
values are available to `env()` and `Config::load` without any extra step.

## Install the database

The skeleton does **not** assume the `users`/`posts` tables already exist.
Create them with the bundled CLI (named `petri`):

```bash
./petri migrate
```

This runs the migrations in `database/migrations/` against the database
configured in `.env`. To roll back: `./petri migrate:rollback`.
To clear compiled views: `./petri view:clear`.

## Serve uploaded images

Post images are stored under `app/storage/uploads/` and served from the web
root through a symlink. Recreate it after a fresh clone if it is missing:

```bash
ln -s ../app/storage/uploads public/uploads
```

## Run

```bash
php -S localhost:8000 -t public public/index.php
```

Then open <http://localhost:8000>. The front controller (`public/index.php`)
ships with the skeleton, so you do **not** need to create it.

Routes can be named for reverse URL generation — see the [framework README](https://github.com/antimonial/framework)
for named routes and the `route()` helper.

## What the demo does

| Capability | Where it lives |
| --- | --- |
| Migrations | `database/migrations/*`, run by `petri` |
| Auth (register/login/logout) | `app/Controllers/AuthController.php` |
| Password hashing | `password_hash()` in `AuthController::register` |
| CSRF token in forms | `@csrf` directive in `app/Views/auth/*` and `app/Views/posts/*` |
| Session flash + re-population | `errors()` / `old()` helpers in `app/Views/layouts/main.php` |
| File upload handling | `PostController::store()` (`$request->file('image')->store(...)`) |
| Ownership authorization | `PostController::findOwnedPost()` returns 404 for others' posts |
| Guest / auth middleware | `app/Routes/web.php` groups using `GuestMiddleware` / `AuthMiddleware` |
| Logging | `ErrorHandler::setLogDirectory()` in `bootstrap/app.php` → `app/storage/logs` |

## Tests

The suite uses an isolated SQLite database (created per run in `tests/bootstrap.php`)
so it never touches your real database.

```bash
composer install
./vendor/bin/phpunit
```

Feature tests cover registration + login, the auth middleware redirect,
post creation, ownership enforcement (404 for other users), validation
errors, and `UploadedFile` metadata.

## Keeping in sync with the framework

The skeleton follows the framework **automatically** — no manual re-release is
needed. A scheduled GitHub Actions workflow (`Sync Framework`) runs daily and:

1. Reads the latest `antimonial/framework` tag from GitHub.
2. If it is newer than the latest skeleton tag, it updates the dependency,
   regenerates `composer.lock`, commits, and **tags the skeleton with the same
   version number** as the framework (e.g. framework `v0.18.3` → skeleton `v0.18.3`).
3. Pushes the new tag — Packagist picks it up automatically.

Notes:

- `composer.json` requires `"antimonial/framework": "0.18.3"` (the version
  constraint is bumped automatically when the framework moves to a new release).
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
- **Loops & conditionals:** `@foreach($posts as $post) … @endforeach`,
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
├── petri              # CLI: migrate, migrate:rollback, view:clear
├── public/            # Web root: index.php (front controller) + assets/
├── bootstrap/        # app.php builds the Antimonial\Core\App instance
├── database/
│   └── migrations/    # create_users_table, create_posts_table
├── app/
│   ├── Config/       # app.php, database.php (loaded by Config::load)
│   ├── Routes/       # web.php (route definitions)
│   ├── Controllers/  # AuthController, PostController, HomeController
│   ├── Models/       # User, Post
│   └── Views/        # PHP views + layouts/ (built-in template engine)
└── composer.json
```

## Documentation

- [Framework Wiki](https://github.com/antimonial/framework/wiki) — routing, the template engine, the query builder, sessions, CSRF, and the full API reference.
- [Framework README](https://github.com/antimonial/framework) — quick start, what's included, and security notes.

## License

MIT
