# Laravel-Vuforia
Vuforia web service API for Laravel



## Installation

You can install this package via composer using this command:

```shell
composer require eyesar/laravel-vuforia
```

Next, you must install the service provider:

```php
// config/app.php
'providers' => [
    ...
    Eyesar\Vuforia\VuforiaWebServiceProvider::class,
];
```

Optionally, you may register an alias:

```php
// config/app.php
'aliases' => [
    ...
    'VWS' => Eyesar\Vuforia\VuforiaWebService::class,
];
```

You can publish the config-file with:

```
php artisan vendor:publish --provider="Eyesar\Vuforia\VuforiaWebServiceProvider"
```

This is the contents of the published config file:

```php
// config/vws.php
<?php

return [

    /*
    |--------------------------------------------------------------
    | Vuforia Web Service URLs
    |--------------------------------------------------------------
    |
    |
    */
    'url' => [
        /*
        |--------------------------------------------------------------
        | Vuforia Web Service Targets Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'targets' => 'https://vws.vuforia.com/targets',

        /*
        |--------------------------------------------------------------
        | Vuforia Web Service Duplicates Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'duplicates' => 'https://vws.vuforia.com/duplicates',

        /*
        |--------------------------------------------------------------
        | Vuforia Web Service Duplicates Request URL
        |--------------------------------------------------------------
        |
        |
        */
        'summary' => 'https://vws.vuforia.com/summary',
        ],

    /*
    |--------------------------------------------------------------
    | Vuforia cloud database access key and secret key
    |--------------------------------------------------------------
    |
    |
    */
    'credentials' => [
        "access_key" => env('VUFORIA_ACCESS_KEY'),
        "secret_key" => env('VUFORIA_SECRET_KEY'),
    ]
];
```

Last, add Vuforia cloud database access key and secret key to `.env`

```
// .env
...
VUFORIA_ACCESS_KEY=
VUFORIA_SECRET_KEY=
...
```

## RESETfull API Reference

See [RESET-API](reset-api.md)