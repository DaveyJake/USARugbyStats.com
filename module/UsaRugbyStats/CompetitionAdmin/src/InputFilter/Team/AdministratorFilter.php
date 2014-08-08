<?php
namespace UsaRugbyStats\CompetitionAdmin\InputFilter\Team;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class AdministratorFilter extends InputFilter
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
                        'fields' => 'id',
                    ),
                ),
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));
    }
}
