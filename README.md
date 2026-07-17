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
to `app/storage/views/` (ignored by git). To force native PHP rendering, call
`Antimonial\View\View::setEngine(null)`.

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

## License

MIT
