<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @see https://trello.com/c/gyfsurDQ/81-positions-numbered-incorrectly
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Version20140820084310_PlayerPositionNumberIndexedFromOne extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE competition_competition_match_team_player SET number=number+1;");
    }

    public function down(Schema $schema)
    {
        $this->addSql("UPDATE competition_competition_match_team_player SET number=number-1;");
    }
}
