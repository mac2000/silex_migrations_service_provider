Silex Migrations Service Provider
=================================

This service provider will give you ability to easily migrate your schema directly from browser.

Installation
------------

Add to your `composer.json`:

    "minimum-stability": "dev",
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "mac2000/silex_migrations_service_provider",
                "version": "dev-master",
                "source": {
                    "type": "git",
                    "url": "git://github.com/mac2000/silex_migrations_service_provider.git",
                    "reference": "master"
                },
                "autoload": {
                    "psr-0": {
                        "": "src"
                    }
                }
            }
        }
    ],
    "require": {
        "silex/silex": "1.*",
        "mac2000/silex_migrations_service_provider": "dev-master"
    },

Register Service Provider
-------------------------

    $app->register(new MigrationsServiceProvider(), array(
        'migration.table' => 'version', // Optional argument - table name where migrations log will be stored, will be created automatically, default value is: doctrine_migration_versions
        'migration.namespace' => 'Acme\\Migration', // Namespace where your migration classes can be found, do not forget about slash escaping and do not add last slash
        'migration.directory' => 'src/Acme/Migration' // Directory where your migration classes can be found
    ));

Usage examples
--------------

    $versions = $app['migration']->getSql();
    $versions = $app['migration']->migrate();

Migrations Trait
----------------

    use MigrationsTrait;
    ...
    $app->migration()->getSql();
    $app->migration()->migrate();

Run tests
---------

    vendor/bin/phpunit
    vendor/bin/phpunit --coverage-html ./report

Demo Application
----------------

There is `demo` application in source where you can find fully functional application example.

The only thing you need to do is run:

    mysql -uroot -proot -e "CREATE DATABASE silex_migrations_service_provider_example"

Migration examples can be found here: `domo/Acme/Migrations/Version*.php`