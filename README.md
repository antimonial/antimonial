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
and the Apache rewrite rules (`public/.htaccess`) ship with the skeleton, so
you do **not** need to create them.

## Structure

```
antimonial/
├── public/            # Web root: index.php (front controller) + .htaccess
├── bootstrap/        # app.php builds the Antimonial\Core\App instance
├── app/
│   ├── Config/       # app.php, database.php (loaded by Config::load)
│   ├── Routes/       # web.php (route definitions)
│   ├── Controllers/  # your controllers (App\Controllers\*)
│   ├── Models/       # your models (App\Models\*)
│   └── Views/        # PHP views + layouts/
└── composer.json
```

## License

MIT
