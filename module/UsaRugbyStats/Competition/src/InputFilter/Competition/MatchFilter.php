<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamCollectionFilter;

/**
 * Competition Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchFilter extends InputFilter
{
    public function __construct(Match\MatchTeamFilter $ifMatchTeam)
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
            'name'       => 'description',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $this->add(array(
            'name'       => 'isLocked',
            'required'   => false,
            'validators' => array(
               array(
                   'name' => 'InArray',
                   'haystack' => array(0,1),
                   'strict'   => true
                ),
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $cif = new MatchTeamCollectionFilter($ifMatchTeam);
        $cif->setInputFilter($ifMatchTeam);
        $this->add($cif, 'teams');
    }
}
