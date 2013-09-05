<?php
namespace Acme;

use Acme\Migrations\HtmlOutputWriter;
use Acme\Migrations\OutputFormatterStyle;
use Mac2000\Application\MigrationsTrait;
use Mac2000\Provider\MigrationsServiceProvider;
use Silex\Application as BaseApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\HttpFoundation\Response;

class Application extends BaseApplication
{
    use MigrationsTrait;

    function __construct(array $values = array())
    {
        parent::__construct($values);

        $this->register(new SessionServiceProvider());
        $this->register(new DoctrineServiceProvider(), array(
            'db.options' => array(
                'driver' => 'pdo_mysql',
                'host' => 'localhost',
                'dbname' => 'silex_migrations_service_provider_example',
                'user' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
            )
        ));

        #region Optional output writer
        $this['migration.outputwriter'] = $this->share(function (Application $app) {
            return new HtmlOutputWriter(new OutputFormatter(true, array(
                'error' => new OutputFormatterStyle('#fff', '#CC0000'),
                'info' => new OutputFormatterStyle('#356AA0', null, array('bold')),
                'comment' => new OutputFormatterStyle('#999', null, array('bold')),
                'warning' => new OutputFormatterStyle('#fff', '#FF7400')
            )));
        });
        #endregion

        $this->register(new MigrationsServiceProvider(), array(
            'migration.table' => 'version',
            'migration.namespace' => 'Acme\\Migrations',
            'migration.directory' => 'demo/Acme/Migrations'
        ));

        $this->get('/', function () {
            return $this->redirect('/migrate/sql');
        });

        $this->get('/migrate/sql/{to}', function ($to) {
            $this->migration()->getSql($to);
            return $this['migration.outputwriter']->getHtmlFormattedMessages();
        })->assert('to', '\d+')->value('to', null);

        $this->get('/migrate/{to}', function ($to) {
            $this->migration()->migrate($to);
            return $this['migration.outputwriter']->getHtmlFormattedMessages();
        })->assert('to', '\d+')->value('to', null);
    }
}