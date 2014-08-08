<?php
namespace UsaRugbyStats\CompetitionAdmin\InputFilter\Team;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class MemberFilter extends InputFilter
{
    public function __construct(ObjectManager $om, ObjectRepository $mapper)
    {
        $this->add(array(
            'name'       => 'account',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $mapper,
                        'fields' => 'id'
                    )
                )
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'membershipStatus',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Zend\Validator\InArray',
                    'options' => array(
                        'haystack' => [0,1,2,3,4],
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));
    }
}
