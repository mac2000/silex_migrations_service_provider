<?php
namespace Mac2000\Tests\Application;

use Mac2000\Provider\MigrationsServiceProvider;
use Silex\Application;
use Mac2000\Application\MigrationsTrait;
use Silex\Provider\DoctrineServiceProvider;

class MigrationsApplication extends Application {
    use MigrationsTrait;


    function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->register(new DoctrineServiceProvider());
        $this->register(new MigrationsServiceProvider(), array(
            'migration.table' => 'version',
            'migration.namespace' => 'Acme\\Migration',
            'migration.directory' => 'src/Acme/Migration'
        ));
    }
}