<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamCollectionFilter;
use Doctrine\Common\Persistence\ObjectRepository;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchSignatureCollectionFilter;
use UsaRugbyStats\Application\Common\Timezones;

/**
 * Competition Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchFilter extends InputFilter
{
    public function __construct(Match\MatchTeamFilter $ifMatchTeam, Match\MatchSignatureFilter $ifMatchSignature, ObjectRepository $locationRepository)
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
            'name'       => 'date_date',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Zend\Validator\Date',
                    'options' => array(
                        'format' => 'Y-m-d'
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'date_time',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Zend\Validator\Date',
                    'options' => array(
                        'format' => 'g:i a'
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'timezone',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'InArray',
                    'options' => array(
                        'haystack' => array_keys(Timezones::$timezones),
                        'strict'   => true
                    ),
                ),
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
        $this->add($cif, 'teams');

        $sif = new MatchSignatureCollectionFilter($ifMatchSignature);
        $this->add($sif, 'signatures');
    }
}
