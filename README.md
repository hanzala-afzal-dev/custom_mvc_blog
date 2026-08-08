# Custom MVC Blog

A small blog application built from scratch in plain PHP — no framework — as
practice for a take-home assignment during an interview process. The goal was
to implement routing, authentication, and CRUD the "hard way" to show an
understanding of what frameworks like Laravel or Symfony normally do for you.

## Features

- **User accounts** — registration and login with hashed passwords
  (`password_hash` / `password_verify`) and PHP sessions.
- **Blog posts** — create, view, edit, and delete posts.
- **Ownership rules** — normal users can only edit/delete their own posts;
  admins can delete any post. See [`Permission.php`](src/Traits/Permission.php).
- **CSRF protection** on every form submission (`Traits\Csrf`).
- **Pagination** on the posts listing.
- **PSR-7 HTTP messages** for requests/responses (`nyholm/psr7`) and
  **FastRoute** for URL routing.

## Tech stack

| Layer      | Choice                                             |
|------------|-----------------------------------------------------|
| Language   | PHP 7.4 / 8.2+                                       |
| Routing    | [nikic/fast-route](https://github.com/nikic/FastRoute) |
| HTTP       | [nyholm/psr7](https://github.com/Nyholm/psr7) (PSR-7) |
| Database   | MySQL via PDO                                        |
| Env config | [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) |
| Testing    | PHPUnit 11                                           |

## Project structure

```
public/            Front controller (public/index.php) — document root for the web server
src/
  App/
    Controllers/    Request handling (AuthenticationController, PostController)
    Services/       Business logic (AuthService, PostService)
    Models/         Database-backed models (User, Post)
  Core/             App bootstrap + Router (wraps FastRoute)
  Database/         PDO connection
  Traits/           Reusable behaviours: Csrf, Session, Permission, Render, EscapeString
  Views/            Plain PHP templates
  routes.php        Route table (method, path, controller/action)
setup-db/           SQL schema, seed data, install/uninstall scripts
tests/Unit/         PHPUnit tests
```

## Requirements

- PHP 7.4 or 8.2+ with the `pdo` and `json` extensions
- Composer
- MySQL (or a compatible database)

## Setup

1. **Install dependencies**

   ```bash
   composer install
   ```

2. **Configure environment**

   Copy the example env file and fill in your database credentials:

   ```bash
   cp .env.example .env
   ```

   ```
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=custom_mvc_blog
   DB_USER=root
   DB_PASS=
   DB_CHARSET=utf8mb4
   DB_DRIVER=mysql
   ```

3. **Create the database and tables**

   This creates the database (if needed), runs `setup-db/tables.sql`, and
   loads `setup-db/seed.sql`:

   ```bash
   composer db:install
   ```

   To drop everything again:

   ```bash
   composer db:drop
   ```

4. **Run the app**

   Point your web server's document root at `public/`, or use PHP's built-in
   server for local development:

   ```bash
   php -S localhost:8000 -t public
   ```

   Then visit `http://localhost:8000/registration` to create an account, or
   `http://localhost:8000/login` if you already have one.

## Routes

| Method | Path                 | Description             |
|--------|----------------------|--------------------------|
| GET    | `/login`              | Login form               |
| POST   | `/authenticate`       | Log in                   |
| GET    | `/registration`       | Registration form        |
| POST   | `/register`           | Create an account        |
| GET    | `/logout`             | Log out                  |
| GET    | `/posts`              | List posts (paginated)   |
| GET    | `/posts/create`       | New post form             |
| POST   | `/posts`              | Create a post             |
| GET    | `/posts/{id}`         | View a single post        |
| GET    | `/posts/{id}/edit`    | Edit post form             |
| POST   | `/posts/{id}/update`  | Update a post              |
| POST   | `/posts/{id}/delete`  | Delete a post               |

## Testing & code style

```bash
composer test    # run PHPUnit
composer phpcs    # check PSR-12 coding standard
composer phpcbf    # auto-fix coding standard violations
```

## Notes

- The `comments` table exists in the schema for future use, but the comment
  feature (controller/routes/views) hasn't been built yet.
- This is a learning/practice project, not production-hardened code — CSRF
  and password hashing are in place, but things like rate limiting, email
  verification, and input sanitization are minimal.
