<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;
use Zend\InputFilter\CollectionInputFilter;

/**
 * Match Team Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamFilter extends InputFilter
{
    public function __construct(CollectionInputFilter $collPlayers, CollectionInputFilter $collEvents, ObjectRepository $teamRepository)
    {

        $this->add(array(
            'name'       => 'id',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'type',
            'required'   => true,
            'validators' => array(
                array('name' => 'InArray', 'options' => array(
                    'haystack' => array('H','A'),
                    'strict'   => \Zend\Validator\InArray::COMPARE_STRICT
               ))
            ),
            'filters'   => array(
                array('name' => 'Alpha'),
            ),
        ));

        $this->add(array(
            'name'       => 'team',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $teamRepository,
                        'fields' => 'id'
                    )
                )
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add($collPlayers, 'players');
        $this->add($collEvents, 'events');
    }

    /**
     * Necessary so that workaround for ZF2-6304 works properly
     * @see UsaRugbyStats\Application\Common\NestedCollectionInputFilter
     */
    public function __clone()
    {
        $obj = clone $this->get('players');
        $this->remove('players');
        $this->add($obj, 'players');

        $obj = clone $this->get('events');
        $this->remove('events');
        $this->add($obj, 'events');
    }

}
