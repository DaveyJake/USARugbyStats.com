<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;

class TeamAdminFieldset extends RoleAssignmentFieldset
{
    public function __construct(ObjectManager $om)
    {
        parent::__construct('team-admin');
        
        $tagFieldset = new TeamAdmin\ManagedTeamFieldset($om);
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'managedTeams',
            'options' => array(
                'label' => 'Managed Teams',
                'target_element' => $tagFieldset
            )
        ));
        
    }
}