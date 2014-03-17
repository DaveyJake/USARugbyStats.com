<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;

class TeamAdminFieldset extends RoleAssignmentFieldset
{
    protected $teamList;
    
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
    
    public function setTeamList(Collection $c)
    {
        $this->teamList = $c;
        return $this;
    }
    
    public function getTeamList()
    {
        return $this->teamList;
    }
    
    public function getTeam($teamid)
    {
        $set = $this->teamList->filter(function($item) use ($teamid) {
            return $item->getId() == $teamid; 
        });
        return $set->current();
    }
}