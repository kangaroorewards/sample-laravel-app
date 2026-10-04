# Sample Laravel application
Sample Laravel application using the Kangaroo Rewards API

# Requirements and installation

- PHP 8.4.1 or newer (PHP 8.5 recommended), with the standard Laravel extensions and PDO SQLite.
- Composer 2.2 or newer.
- Node.js 24 LTS and npm (Vite requires Node.js 20.19+ or 22.12+).

```sh
composer install
cp .env.example .env
php artisan key:generate
npm ci
npm run build
```

The application uses Laravel 13, PHPUnit 13, Vite 8, Tailwind CSS 4, and Bootstrap 5.3.8.
Tailwind 4 targets modern browsers (Safari 16.4+, Chrome 111+, and Firefox 128+).
Frontend assets must be built before opening the welcome page or running the test suite.

When upgrading an existing installation, install from the updated lockfiles and rebuild assets.
Sessions now use JSON serialization; existing PHP-serialized sessions will expire, so users must sign in again.
Keep the existing `APP_KEY` and environment configuration.

# Configuration
Also add corresponding configuration to your `config/services.php`:

```
// config/services.php

'kangaroo' => [
    'client_id'       => env('KANGAROO_CLIENT_ID'),
    'client_secret'   => env('KANGAROO_CLIENT_SECRET'),
    'redirect'        => env('KANGAROO_REDIRECT_URI'),
    'application_key' => env('KANGAROO_APPLICATION_KEY'),
],
```

…and in your .env file:

```
KANGAROO_CLIENT_ID=your_client_id
KANGAROO_CLIENT_SECRET=your_client_secret
KANGAROO_REDIRECT_URI=https://yourdomain.com/callback
KANGAROO_APPLICATION_KEY=your_application_key
KANGAROO_USERNAME=your_username
KANGAROO_PASSWORD=your_password
```

Create DB
```
touch database/database.sqlite
```

Run migrations
```
php artisan migrate
```

# Starting the web server

```
php artisan serve
```

Navigate to `/login`.

# Verification

```sh
composer validate --strict
npm run build
vendor/bin/phpunit
composer audit
npm audit
```

GitHub Actions runs the build, migrations, Blade compilation, and tests on PHP 8.4 and 8.5.
The integration tests fake Kangaroo API responses and do not require live credentials.
