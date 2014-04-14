<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;

class SuperAdmin extends BaseAssignment
{
    public function getDiscriminator()
    {
        return 'super_admin';
    }
}
