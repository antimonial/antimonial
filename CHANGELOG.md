# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.18.3] - 2026-07-17

### Added
- Personal blog demo built on `antimonial/framework` 0.18.3.
- Database migrations for `users` and `posts` tables (`database/migrations/`).
- `petri` CLI with `migrate`, `migrate:rollback`, and `view:clear` commands.
- Authentication: register, login, and logout (`AuthController`) with
  `password_hash()`-hashed credentials and `Auth` facade.
- Posts CRUD with image uploads and ownership-based authorization
  (`PostController::findOwnedPost()` returns 404 for other users' posts).
- CSRF protection on state-changing forms via the `@csrf` directive.
- Session-based flash messages and form re-population (`errors()` / `old()`).
- Static assets (`public/assets/css/app.css`) and a shared layout with an
  error/flash block.
- File logging via `ErrorHandler::setLogDirectory()` → `app/storage/logs`.
- PHPUnit feature tests (auth, posts ownership, validation, uploads) using an
  isolated SQLite database.

### Changed
- Bumped `antimonial/framework` dependency to `0.18.3`.
- Rewrote `README.md` as an end-to-end blog demo guide.

[0.18.3]: https://github.com/antimonial/antimonial/releases/tag/v0.18.3
