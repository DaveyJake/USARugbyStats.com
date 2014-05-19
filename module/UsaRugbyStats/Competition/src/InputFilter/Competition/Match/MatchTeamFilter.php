<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterInterface;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Match Team Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchTeamFilter extends InputFilter
{
    public function __construct(ObjectRepository $teamRepository)
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

    }
}
