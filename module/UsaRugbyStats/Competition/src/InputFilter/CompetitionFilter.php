<?php
namespace UsaRugbyStats\Competition\InputFilter;

use Zend\InputFilter\InputFilter;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\CollectionInputFilter;

/**
 * Competition Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class CompetitionFilter extends InputFilter
{
    public function __construct(ObjectManager $objectManager, ObjectRepository $objectRepository, InputFilterInterface $ifDivision)
    {

        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'object_manager' => $objectManager,
                        'object_repository' => $objectRepository,
                        'fields' => 'name',
                    )
                ),
            ),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $cif = new CollectionInputFilter();
        $cif->setInputFilter($ifDivision);

        $this->add($cif, 'divisions');

    }
}
