<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Competition TeamMembership Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class TeamMembershipFilter extends InputFilter
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
