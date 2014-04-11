<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class CompetitionFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('competition');
        
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
                'label' => 'Competition Name',
            ),
        ));
        
    }

}