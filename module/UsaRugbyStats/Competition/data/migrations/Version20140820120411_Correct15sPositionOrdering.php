<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @see https://trello.com/c/gyfsurDQ/81-positions-numbered-incorrectly
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Version20140820120411_Correct15sPositionOrdering extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql("UPDATE competition_competition_match_team_player SET number=5 WHERE position='BSF' AND number=6");
        $this->addSql("UPDATE competition_competition_match_team_player SET number=6 WHERE position='OSF' AND number=5");

        $this->addSql("UPDATE competition_competition_match_team_player SET number=10 WHERE position='W1' AND number=12");
        $this->addSql("UPDATE competition_competition_match_team_player SET number=11 WHERE position='IC' AND number=10");
        $this->addSql("UPDATE competition_competition_match_team_player SET number=12 WHERE position='OC' AND number=11");
    }

    public function down(Schema $schema)
    {
        $this->addSql("UPDATE competition_competition_match_team_player SET number=6 WHERE position='BSF' AND number=5");
        $this->addSql("UPDATE competition_competition_match_team_player SET number=5 WHERE position='OSF' AND number=6");

        $this->addSql("UPDATE competition_competition_match_team_player SET number=12 WHERE position='W1' AND number=10");
        $this->addSql("UPDATE competition_competition_match_team_player SET number=10 WHERE position='IC' AND number=11");
        $this->addSql("UPDATE competition_competition_match_team_player SET number=11 WHERE position='OC' AND number=12");
    }
}
