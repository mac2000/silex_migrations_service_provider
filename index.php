<?php
use Acme\Application;

$loader = require 'vendor/autoload.php';
$loader->add('', __DIR__ . '/demo');

$app = new Application();
$app['debug'] = true;
$app->run();