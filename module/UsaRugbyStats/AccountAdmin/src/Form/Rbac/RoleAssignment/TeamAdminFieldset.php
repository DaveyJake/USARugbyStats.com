<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Doctrine\Common\Persistence\ObjectManager;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;
use DoctrineModule\Form\Element\ObjectSelect;
use Doctrine\Common\Persistence\ObjectRepository;

class TeamAdminFieldset extends RoleAssignmentFieldset
{
    protected $teamRepo;
    
    public function __construct(ObjectManager $om, ObjectRepository $teamRepo)
    {
        parent::__construct('team-admin');
        
        $this->teamRepo = $teamRepo;
        
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
                'target_element' => $team,
                'should_create_template' => true,                
            )
        ));
    }
    
    public function getTeam($teamid)
    {
        if (empty($teamid)) {
            return null;
        }
        return $this->teamRepo->find($teamid);
    }
}