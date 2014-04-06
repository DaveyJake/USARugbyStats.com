<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class UnionFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('union');
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Union Name',
            ),
        ));
        
    }

}