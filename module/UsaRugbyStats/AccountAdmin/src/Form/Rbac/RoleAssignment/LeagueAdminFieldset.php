<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueAdminFieldset extends RoleAssignmentFieldset
{
    public function __construct(ObjectManager $om)
    {
        parent::__construct('league-admin');
    }
}