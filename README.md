# Sample Laravel application
Sample Laravel application using the Kangaroo Rewards API

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
$ cd ~/public
$ php -S localhost:8000
```

Naviage to `/login`