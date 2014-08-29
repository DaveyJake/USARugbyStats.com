<?php
namespace UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team;

use Zend\Form\Fieldset;

class MemberFieldset extends Fieldset
{
    public function __construct()
    {
        parent::__construct('team-administrator');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'account'
        ));

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
