# Antimonial application skeleton

This is the starter application for the [Antimonial](https://github.com/antimonial/framework)
framework. It is a **minimal starting point** — a single welcome route and a
`users` table — with no bundled demo feature. Run `composer create-project`,
get it running, and build your own application on top of it.

The framework itself provides routing, a template engine, a query builder,
migrations, sessions, CSRF protection, authentication helpers, file uploads,
and logging. Those are *capabilities you can use*; see the framework's own
docs and wiki for how.

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

The skeleton ships a `users` migration (baseline infrastructure most apps
need). Create the tables with the bundled CLI (named `petri`):

```bash
./petri migrate
```

This runs the migrations in `database/migrations/` against the database
configured in `.env`. To roll back: `./petri migrate:rollback`.
To clear compiled views: `./petri view:clear`.

## Run

```bash
php -S localhost:8000 -t public public/index.php
```

Then open <http://localhost:8000>. The front controller (`public/index.php`)
ships with the skeleton, so you do **not** need to create it.

## Where to start building

| You want to… | Edit |
| --- | --- |
| Add a page | `app/Routes/web.php` (register a route) |
| Handle a request | `app/Controllers/` (add a controller) |
| Read/write data | `app/Models/` (add a model) + `database/migrations/` |
| Render HTML | `app/Views/` (views use the built-in template engine) |
| Change framework config | `app/Config/` |

Routes can be named for reverse URL generation — see the [framework README](https://github.com/antimonial/framework)
for named routes and the `route()` helper.

## Tests

The suite is a smoke test that boots the app and asserts `GET /` returns 200.
It uses an isolated SQLite database (created per run in `tests/bootstrap.php`)
so it never touches your real database.

```bash
composer install
./vendor/bin/phpunit
```

## Views & Template Engine

The skeleton ships on top of the framework's built-in template engine
(see the [framework README](https://github.com/antimonial/framework) for the
full syntax). Views are plain PHP files in `app/Views/` that compile to
cached PHP on first render — no setup required.

Key points:

- **Auto-escaping by default.** `{{ $name }}` is XSS-safe; `{{{ $content }}}`
  emits raw, trusted HTML (used here for the layout's `$content` slot).
- **Loops & conditionals:** `@foreach($items as $item) … @endforeach`,
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
│   └── migrations/    # create_users_table
├── app/
│   ├── Config/       # app.php, database.php (loaded by Config::load)
│   ├── Routes/       # web.php (route definitions)
│   ├── Controllers/  # HomeController
│   ├── Models/       # User
│   └── Views/        # home.php + layouts/ (built-in template engine)
└── composer.json
```

## Documentation

- [Framework Wiki](https://github.com/antimonial/framework/wiki) — routing, the template engine, the query builder, sessions, CSRF, and the full API reference.
- [Framework README](https://github.com/antimonial/framework) — quick start, what's included, and security notes.

## License

MIT
