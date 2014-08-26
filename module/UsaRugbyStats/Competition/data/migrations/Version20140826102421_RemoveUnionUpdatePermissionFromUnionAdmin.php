<?php

namespace DoctrineORMModule\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Removes the `competition.union.update` permission from union_admin
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Version20140826102421_RemoveUnionUpdatePermissionFromUnionAdmin extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $sql = <<<SQL
UPDATE `accounts_rbac_roles_permissions` arrp
LEFT JOIN `accounts_rbac_permission` arp ON arrp.permission_id = arp.id
LEFT JOIN `accounts_rbac_roles` arr ON arrp.role_id = arr.id
LEFT JOIN `accounts_rbac_roles` arr_super ON arr_super.name = 'super_admin'
SET arrp.role_id = arr_super.id
WHERE arp.name = 'competition.union.update' AND arr.name = 'union_admin';
SQL;
        $this->addSql($sql);
    }

    public function down(Schema $schema)
    {
        $sql = <<<SQL
UPDATE `accounts_rbac_roles_permissions` arrp
LEFT JOIN `accounts_rbac_permission` arp ON arrp.permission_id = arp.id
LEFT JOIN `accounts_rbac_roles` arr ON arrp.role_id = arr.id
LEFT JOIN `accounts_rbac_roles` arr_union ON arr_union.name = 'union_admin'
SET arrp.role_id = arr_union.id
WHERE arp.name = 'competition.union.update' AND arr.name = 'super_admin';
SQL;
        $this->addSql($sql);
    }
}
