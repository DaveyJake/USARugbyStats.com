<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

class RoleAssignmentTestEntity extends RoleAssignment
{
    public function getDiscriminator()
    {
        return 'test';
    }
}
