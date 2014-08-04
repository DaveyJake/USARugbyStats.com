<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;

/**
 * Match Signature Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchSignatureFilter extends InputFilter
{
    public function __construct(ObjectRepository $accountRepository)
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
            'name'       => 'type',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Alpha'),
            ),
        ));

        $this->add(array(
            'name'       => 'account',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\ObjectExists',
                    'options' => array(
                        'object_repository' => $accountRepository,
                        'fields' => 'id'
                    )
                )
            ),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'type',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Alnum'),
            ),
        ));

        $this->add(array(
            'name'       => 'timestamp',
            'required'   => false,
            'validators' => array(),
            'filters'    => array(),
        ));
    }

}
