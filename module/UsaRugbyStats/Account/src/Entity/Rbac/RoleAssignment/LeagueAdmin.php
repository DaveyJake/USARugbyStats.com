<?php
namespace UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment as BaseAssignment;

class LeagueAdmin extends BaseAssignment
{
    public function getDiscriminator()
    {
        return 'league_admin';
    }
}