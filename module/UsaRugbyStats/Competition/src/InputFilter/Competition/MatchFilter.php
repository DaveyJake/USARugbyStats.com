<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamCollectionFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Competition Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchFilter extends InputFilter
{
    public function __construct(Match\MatchTeamFilter $ifMatchTeam, ObjectRepository $locationRepository)
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
            'name'       => 'location',
            'required'   => false,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $locationRepository,
                        'fields' => 'id'
                    )
                )
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'locationDetails',
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
                   'options' => array(
                       'haystack' => array('0','1'),
                       'strict'   => true
                   ),
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
