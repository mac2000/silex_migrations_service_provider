<?php
namespace Mac2000\Application;

use Doctrine\DBAL\Migrations\Migration;

trait MigrationsTrait {
    /**
     * @return Migration
     */
    public function migration()
    {
        return $this['migration'];
    }
}