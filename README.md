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

Migration examples can be found here: `demo/Acme/Migrations/Version*.php`

Version class examples
======================

Using Raw SQL
-------------

Can be found in `demo/Acme/Migrations/Version*.php`

Doctrine
--------

    class Version1 extends AbstractMigration
    {
        public function up(Schema $schema)
        {
            $people = $schema->createTable('people');
            $people->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $people->addColumn('first_name', 'string', array('length' => 128));
            $people->addColumn('last_name', 'string', array('length' => 128));
            $people->setPrimaryKey(array('id'));
        }

        public function postUp(Schema $schema)
        {
            $this->connection->insert('people', array(
                'first_name' => 'Alexandr',
                'last_name' => 'Marchenko'
            ));

            $this->connection->insert('people', array(
                'first_name' => 'Maria',
                'last_name' => 'Marchenko'
            ));
        }

        public function down(Schema $schema)
        {
            $schema->dropTable('people');
        }
    }

    class Version2 extends AbstractMigration {

        public function up(Schema $schema)
        {
            $people = $schema->getTable('people');
            $people->addColumn('full_name', 'string', array('length' => 256));
        }

        public function postUp(Schema $schema) {
            $this->connection->createQueryBuilder()->update('people')->set('full_name', "CONCAT(first_name, ' ', last_name)")->execute();
        }

        public function down(Schema $schema)
        {
            $people = $schema->getTable('people');
            $people->dropColumn('full_name');
        }
    }

    class Version3 extends AbstractMigration {

        public function up(Schema $schema)
        {
            $people = $schema->getTable('people');
            $people->dropColumn('first_name');
            $people->dropColumn('last_name');
        }

        public function down(Schema $schema)
        {
            $people = $schema->getTable('people');
            $people->addColumn('first_name', 'string', array('length' => 128));
            $people->addColumn('last_name', 'string', array('length' => 128));
        }

        public function postDown(Schema $schema) {
            $this->connection->createQueryBuilder()->update('people')->set('first_name', "SUBSTRING_INDEX(full_name, ' ', 1)")->set('last_name', "SUBSTRING_INDEX(full_name, ' ', -1)")->execute();
        }
    }


What you should notice is that you can not change schema and data at same time.
