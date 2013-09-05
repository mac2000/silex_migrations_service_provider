Silex Migrations Service Provider
=================================

This service provider will give you ability to easily migrate your schema directly from browser.

Installation
------------

Add to your `composer.json`:

    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/mac2000/migrations_service_provider"
        }
    ],
    "require": {
        "mac2000/migrations_service_provider": "*"
    }

Register Service Provider
-------------------------

    $app->register(new MigrationsServiceProvider(), array(
        'migration.table' => 'version', // Optional argument - table name where migrations log will be stored, will be created automatically, default value is: doctrine_migration_versions
        'migration.namespace' => 'Acme\\Migration', // Namespace where your migration classes can be found, do not forget about slash escaping and do not add last slash
        'migration.directory' => 'src/Acme/Migration' // Directory where your migration classes can be found
    ));

Run tests
---------

    vendor/bin/phpunit

