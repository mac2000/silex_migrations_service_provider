<?php
namespace Mac2000\Tests\Provider;

use Mac2000\Provider\MigrationsServiceProvider;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

class MigrationsServiceProviderTest extends PHPUnit_Framework_TestCase
{

    /**
     * @expectedException InvalidArgumentException
     */
    public function testMigrationConfigurationValidation()
    {
        $app = new Application();

        $app->register(new DoctrineServiceProvider());
        $app->register(new MigrationsServiceProvider());

        $app['migration']->toSql();
    }

    public function testRegister()
    {
        $app = new Application();

        $app->register(new DoctrineServiceProvider());
        $app->register(new MigrationsServiceProvider(), array(
            'migration.table' => 'version',
            'migration.namespace' => 'Acme\\Migration',
            'migration.directory' => 'src/Acme/Migration'
        ));

        $app->boot();

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Migration', $app['migration']);

        $this->assertEquals('version', $app['migration.table']);
        $this->assertEquals('Acme\\Migration', $app['migration.namespace']);
        $this->assertEquals('src/Acme/Migration', $app['migration.directory']);
    }
}