<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class MatchSignatureFieldset extends Fieldset
{

    public function __construct(ObjectManager $om)
    {
        parent::__construct('match-signature');

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'id',
            'options' => array(
                'label' => 'Identifier',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'type',
            'options' => array(
                'label' => 'Type',
                'value_options' => [
                    'HC' => 'Home Coach',
                    'AC' => 'Away Coach',
                    'REF' => 'Referee',
                    'NR4' => '#4',
                ]
            ),
        ));

        $this->add(array(
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'account',
            'options' => array(
                'label' => 'Signee',
                'object_manager' => $om,
                'target_class'   => 'UsaRugbyStats\Account\Entity\Account',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\DateTime',
            'name' => 'timestamp',
            'options' => array(
                'label' => 'Timestamp',
            ),
        ));

    }

}
