<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140825215035_FixCompetitionMatchDetailsEditingPermission extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('UPDATE accounts_rbac_roles_permissions SET role_id=3 WHERE role_id=5 AND permission_id=24');
    }

    public function down(Schema $schema)
    {
        $this->addSql('UPDATE accounts_rbac_roles_permissions SET role_id=5 WHERE role_id=3 AND permission_id=24');
    }
}
