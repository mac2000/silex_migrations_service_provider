<?php
namespace Acme\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version1
 *
 * Notice that file and class names MUST be Version*.php
 *
 * Define people table
 *
 * @package Acme\Migrations
 */
class Version1 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->write('<info>Create `people` table</info>');
        $this->addSql("
            CREATE TABLE people (
                id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(128) NOT NULL,
                last_name VARCHAR(128) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci;
        ");

        $this->write('<info>Add some records to `people` table</info>');
        $this->addSql("
            INSERT INTO people VALUES (NULL, 'Alexandr', 'Marchenko'), (NULL, 'Maria', 'Marchenko');
        ");
    }

    public function down(Schema $schema)
    {
        $this->write('<info>Delete `people` table</info>');
        $this->addSql("DROP TABLE people;");;
    }
}