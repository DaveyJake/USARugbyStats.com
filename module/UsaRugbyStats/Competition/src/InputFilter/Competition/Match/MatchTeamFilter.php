<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Match Team Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamFilter extends InputFilter
{
    public function __construct(MatchTeamPlayerFilter $ifMatchTeamPlayer, ObjectRepository $teamRepository)
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

        $collPlayers = new MatchTeamPlayerCollectionFilter($ifMatchTeamPlayer);
        $this->add($collPlayers, 'players');

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
    }
}
