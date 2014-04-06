<?php
namespace UsaRugbyStats\Competition\InputFilter;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Team Input Filter
 * 
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class TeamFilter extends InputFilter
{
    public function __construct(ObjectRepository $objectRepository)
    {

        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\NoObjectExists',
                    'options' => array(
                        'object_repository' => $objectRepository,
                        'fields' => 'name'
                    )
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));
        
    }
}
