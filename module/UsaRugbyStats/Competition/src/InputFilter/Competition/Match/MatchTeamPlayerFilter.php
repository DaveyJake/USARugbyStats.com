<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Match Team Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamPlayerFilter extends InputFilter
{
    public function __construct(ObjectRepository $playerRepository)
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
            'name'       => 'number',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'position',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'player',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $playerRepository,
                        'fields' => 'id'
                    )
                )
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

    }
}
