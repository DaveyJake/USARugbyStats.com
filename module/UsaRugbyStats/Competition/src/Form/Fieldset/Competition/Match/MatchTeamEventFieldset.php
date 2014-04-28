<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class MatchTeamEventFieldset extends Fieldset
{

    public function __construct($name, ObjectManager $om)
    {
        parent::__construct($name);

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'minute',
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'event',
        ));
    }

}
