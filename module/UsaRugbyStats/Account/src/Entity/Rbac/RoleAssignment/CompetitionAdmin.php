<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;

class CompetitionAdmin extends BaseAssignment
{
    public function getDiscriminator()
    {
        return 'competition_admin';
    }
}