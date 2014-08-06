<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Team;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class MemberFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('member');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(
            array(
                'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
                'name' => 'role',
                'options' => array(
                    'label' => 'Member',
                    'object_manager' => $om,
                    'target_class'   => 'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member',
                ),
            )
        );

        $this->add(
            array(
                'type' => 'UsaRugbyStats\Application\Common\ObjectSelect',
                'name' => 'team',
                'options' => array(
                    'label' => 'Team',
                    'object_manager' => $om,
                    'target_class'   => 'UsaRugbyStats\Competition\Entity\Team',
                    'find_method'    => array(
                        'name'   => 'findBy',
                        'params' => array(
                            'criteria' => array(),
                            'orderBy'  => array('name' => 'ASC'),
                        ),
                    ),
                ),
            )
        );

        $this->add(
            array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'membershipStatus',
                'options' => array(
                    'label' => 'Status',
                    'value_options' => array(
                        NULL => 'Not Specified',
                        0 => 'Unpaid',
                        1 => 'Pending',
                        2 => 'Current',
                        3 => 'Grace Period',
                        4 => 'Lapsed',
                    ),
                ),
            )
        );

    }

}
