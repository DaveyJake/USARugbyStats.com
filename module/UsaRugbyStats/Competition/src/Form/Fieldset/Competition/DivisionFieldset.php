<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class DivisionFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
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
    }

}