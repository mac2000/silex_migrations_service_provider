<?php
namespace Mac2000\Tests\Application;

use PHPUnit_Framework_TestCase;

class MigrationsTraitTest extends PHPUnit_Framework_TestCase {
    public function testTrait() {
        $app = new MigrationsApplication();

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Migration', $app->migration());
    }
}