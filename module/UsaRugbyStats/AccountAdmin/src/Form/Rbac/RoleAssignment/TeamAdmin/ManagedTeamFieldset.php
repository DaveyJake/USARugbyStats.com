<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdmin;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\Fieldset;

class ManagedTeamFieldset extends Fieldset 
{
    public function __construct(ObjectManager $om)
    {
        parent::__construct('managed-team');

        $this->add(
            array(
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'name' => 'team',
                'options' => array(
                    'label' => 'Team',
                    'object_manager' => $om,
                    'target_class'   => 'UsaRugbyStats\Application\Entity\Team',
                ),
            )
        );
        
    }
    

    public function populateValues($data)
    {
        return parent::populateValues(['team' => $data]);
    }
    
}