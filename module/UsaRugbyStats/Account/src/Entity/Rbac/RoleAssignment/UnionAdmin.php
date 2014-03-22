<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;

class UnionAdmin extends BaseAssignment
{
    public function getDiscriminator()
    {
        return 'union_admin';
    }
}