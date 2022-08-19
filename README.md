# Easy way to synchronize remote database to the local.

[![Latest Stable Version](http://poser.pugx.org/indeev/laravel-remote-db-sync/v)](https://packagist.org/packages/indeev/laravel-remote-db-sync)
[![Total Downloads](http://poser.pugx.org/indeev/laravel-remote-db-sync/downloads)](https://packagist.org/packages/indeev/laravel-remote-db-sync)
[![Latest Unstable Version](http://poser.pugx.org/indeev/laravel-remote-db-sync/v/unstable)](https://packagist.org/packages/indeev/laravel-rapid-db-anonymizer)
[![License](http://poser.pugx.org/indeev/laravel-remote-db-sync/license)](https://packagist.org/packages/indeev/laravel-remote-db-sync)

![Laravel Remote DB Sync](https://github.com/inDeev/laravel-remote-db-sync/blob/master/img/laravel_remote_db_sync.png)

Bored by exporting & importing remote db to the local is history. With this package, just run single command and everything get processed in the background.

## Installation

You can install the package via composer:

```bash
composer require indeev/laravel-remote-db-sync --dev
```

It is required to have external connection defined in _config/database.php_

```php
'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            // ...
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            // ...
        ],

        'azure' => [ // Our external database connection
            'driver' => 'mysql',
            'host' => env('DB_HOST_AZURE', 'dbservicename.mysql.database.azure.com'),
            'port' => env('DB_PORT_AZURE', '3306'),
            'database' => env('DB_DATABASE_AZURE', ''),
            'username' => env('DB_USERNAME_AZURE', ''),
            'password' => env('DB_PASSWORD_AZURE', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'server_version' => '5.7',
            'modes'  => [
                'ONLY_FULL_GROUP_BY',
                'STRICT_TRANS_TABLES',
                'NO_ZERO_IN_DATE',
                'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                'NO_ENGINE_SUBSTITUTION',
            ],
        ],
        // ...
```

## Usage

To sync all tables: 

```bash
php db:sync_remote azure
```

To sync everything without tables marked for skip:

```bash
php db:sync_remote azure --skipped=cache,log
```

To sync only defined tables (if --only is set, --skipped is ignored):

```bash
php db:sync_remote azure --only=customer,customer_settings
```

In all cases above can be added parameter --only_schema=table,names to define tables without data sync. 
For instance to sync everything without tables cache, log and without data on tables documents, document_metas: 

```bash
php db:sync_remote azure --skipped=cache,log --only_schema=documents,document_metas
```

## Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email katerinak@indeev.eu instead of using the issue tracker.

## Credits

-   [Petr Kateřiňák](https://github.com/indeev)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
