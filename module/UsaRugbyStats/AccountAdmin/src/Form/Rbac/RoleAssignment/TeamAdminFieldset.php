<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use DoctrineModule\Form\Element\ObjectSelect;

class TeamAdminFieldset extends RoleAssignmentFieldset
{
    protected $teamList;
    
    public function __construct(ObjectManager $om)
    {
        parent::__construct('team-admin');
        
        $team = new ObjectSelect();
        $team->setName('team');
        $team->setOptions(array(
            'label' => 'Team',
            'object_manager' => $om,
            'target_class'   => 'UsaRugbyStats\Application\Entity\Team',
        ));
        
        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'managedTeams',
            'options' => array(
                'label' => 'Managed Teams',
                'target_element' => $team
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