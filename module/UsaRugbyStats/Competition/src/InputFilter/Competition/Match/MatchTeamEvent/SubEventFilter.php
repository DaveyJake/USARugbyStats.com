<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent;

use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEventFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Sub Event InputFilter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class SubEventFilter extends MatchTeamEventFilter
{
    public function __construct(ObjectRepository $repoPlayer)
    {
        parent::__construct();

        $this->add(array(
            'name'       => 'type',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Alpha'),
            ),
        ));

        $this->add(array(
            'name'       => 'playerOn',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $repoPlayer,
                        'fields' => 'id'
                    )
                )
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'playerOff',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $repoPlayer,
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
