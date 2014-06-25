<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use Doctrine\Common\Persistence\ObjectManager;

class SuperAdminFieldset extends RoleAssignmentFieldset
{
    public function __construct(ObjectManager $om)
    {
        parent::__construct('super_admin');
    }

    public function getDisplayName() { return 'Super Administrator'; }
}
