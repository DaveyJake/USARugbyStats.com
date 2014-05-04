<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Competition Division Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class DivisionFilter extends InputFilter
{
    public function __construct(ObjectManager $objectManager, ObjectRepository $objectRepository)
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
            'name'       => 'name',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

    }
}
