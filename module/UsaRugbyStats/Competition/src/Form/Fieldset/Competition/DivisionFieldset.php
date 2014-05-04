<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class DivisionFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, TeamMembershipFieldset $fsTeamMembership)
    {
        parent::__construct('division');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Name',
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'teamMemberships',
            'options' => array(
                'target_element' => $fsTeamMembership,
                'should_create_template' => true,
                'template_placeholder' => '__teamindex__',
                'count' => 0,
            )
        ));

    }

}
