<?php
namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version2
 *
 * Combining first_name and last_name into full_name
 *
 * @package Acme\Migrations
 */
class Version2 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->write('<info>Add `full_name` to `people` table</info>');
        $this->addSql("
            ALTER TABLE people ADD COLUMN full_name VARCHAR(256);
        ");

        $this->write('<info>Move data from `first_name` and `last_name` to `full_name`</info>');
        $this->addSql("
            UPDATE people SET full_name = CONCAT(first_name, ' ', last_name);
        ");

        $this->write('<info>Delete `first_name` and `last_name` columns</info>');
        $this->addSql("
            ALTER TABLE people DROP COLUMN first_name, DROP COLUMN last_name;
        ");
    }

    public function down(Schema $schema)
    {
        $this->write('<info>Add `first_name` and `last_name` columns</info>');
        $this->addSql("
            ALTER TABLE people
              ADD COLUMN first_name VARCHAR(128),
              ADD COLUMN last_name VARCHAR(128);
        ");

        $this->write('<info>Move data from `full_name` to `first_name` and `last_name`</info>');
        $this->addSql("
            UPDATE people SET
              first_name = SUBSTRING_INDEX(full_name, ' ', 1),
              last_name = SUBSTRING_INDEX(full_name, ' ', -1);
        ");

        $this->write('<info>Delete `full_name` column</info>');
        $this->addSql("
            ALTER TABLE people DROP COLUMN full_name;
        ");
    }
}