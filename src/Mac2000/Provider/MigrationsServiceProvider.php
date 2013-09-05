<?php
namespace Mac2000\Provider;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Migration;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MigrationsServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if(!isset($app['migration.outputwriter'])) {
            $app['migration.outputwriter'] = null;
        }

        $app['migration'] = $app->share(function() use($app){
            return new Migration($app['migration.configuration']);
        });

        $app['migration.configuration'] = $app->share(function() use($app){
            $configuration = new Configuration($app['db'], $app['migration.outputwriter']);

            if(isset($app['migration.table'])) {
                $configuration->setMigrationsTableName($app['migration.table']);
            }

            $configuration->setMigrationsNamespace($app['migration.namespace']);
            $configuration->setMigrationsDirectory($app['migration.directory']);
            $configuration->registerMigrationsFromDirectory($configuration->getMigrationsDirectory());

            return $configuration;
        });
    }

    public function boot(Application $app)
    {
    }
}