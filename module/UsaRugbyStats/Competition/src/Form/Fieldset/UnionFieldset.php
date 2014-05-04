<?php
namespace UsaRugbyStats\Competition\Form\Fieldset;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Form\FieldsetInterface;

class UnionFieldset extends Fieldset
{

    public function __construct(ObjectManager $om, FieldsetInterface $fsTeam)
    {
        parent::__construct('union');

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
                'label' => 'Union Name',
            ),
        ));

        $this->add(array(
            'type'    => 'Zend\Form\Element\Collection',
            'name'    => 'teams',
            'options' => array(
                'target_element' => $fsTeam,
                'should_create_template' => true,
                'count' => 0,
            )
        ));
    }

}
