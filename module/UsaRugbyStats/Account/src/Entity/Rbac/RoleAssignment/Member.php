<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;

class Member extends BaseAssignment
{
    public function getDiscriminator()
    {
        return 'member';
    }
}
