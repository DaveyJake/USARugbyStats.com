<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141214122000_RbacPermissionsForCitizenshipField extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $permissions = <<<EOB
INSERT IGNORE INTO `accounts_rbac_permission` (`id`, `name`) VALUES
(50, 'account.profile.extprofile.citizenship')
EOB;
        $this->addSql($permissions);

        $assignments = <<<EOB
INSERT IGNORE INTO `accounts_rbac_roles_permissions` (`role_id`, `permission_id`) VALUES
(5, 50)
EOB;
        $this->addSql($assignments);

    }

    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `accounts_rbac_roles_permissions` WHERE `permission_id` = 50;");
        $this->addSql("DELETE FROM `accounts_rbac_permission` WHERE `id` = 50;");
    }
}
