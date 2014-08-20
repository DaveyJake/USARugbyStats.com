<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * @see https://trello.com/c/W3mu0ld6/85-fix-sorting-of-team-union-and-player-role-associations
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Version20140820110059_GenerateSortKeyForTeamMembershipRecords extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $sql = <<<SQL
UPDATE
    competition_team_members ctm
        LEFT JOIN competition_teams ct ON ct.id = ctm.team_id
        LEFT JOIN accounts_rbac_roleassignment_member AS arrm ON arrm.id = ctm.role_id
        LEFT JOIN accounts_rbac_roleassignment arr ON arrm.id = arr.id
        LEFT JOIN accounts a ON a.user_id = arr.account_id
SET sortKey = LCASE(REPLACE(REPLACE(CONCAT(ct.name, a.display_name), ' ', ''), ',', ''))
;
SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
