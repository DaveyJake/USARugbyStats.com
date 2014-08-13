<?php
namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140812195200_AddRbacPermissionsForLocation extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $permissions = <<<EOB
INSERT IGNORE INTO `accounts_rbac_permission` (`id`, `name`) VALUES
(29,	'competition.location.list'),
(30,	'competition.location.view'),
(31,	'competition.location.create'),
(32,	'competition.location.update');
EOB;
        $this->addSql($permissions);

        $assignments = <<<EOB
INSERT IGNORE INTO `accounts_rbac_roles_permissions` (`role_id`, `permission_id`) VALUES
(3,	32),
(4,	32),
(3,	31),
(4,	31),
(2,	30),
(2,	29);
EOB;
        $this->addSql($assignments);

    }

    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `accounts_rbac_permission` WHERE id IN (29,30,31,32);");
        $this->addSql("DELETE FROM `accounts_rbac_roles_permissions` WHERE permission_id IN (29,30,31,32);");
    }
}
