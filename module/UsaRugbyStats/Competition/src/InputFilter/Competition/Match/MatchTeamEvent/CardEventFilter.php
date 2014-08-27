<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent;

use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEventFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Card Event InputFilter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class CardEventFilter extends MatchTeamEventFilter
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
            'name'       => 'player',
            'required'   => true,
            'allow_empty'=> true,
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
