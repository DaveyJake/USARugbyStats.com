<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140930133516_ImportSessionTable extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = <<<EOB
CREATE TABLE `session` (
  `id` varchar(128) NOT NULL DEFAULT '',
  `name` char(32) NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOB;

        $this->addSql($table);
    }

    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE session');
    }
}
