<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Zend\InputFilter\InputFilter;

class LocationCreateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = new Form('create-location');

        // Set the hydrator
        $form->setHydrator(new DoctrineObject($sm->get('zfcuser_doctrine_em')));

        // Set the base fieldset (location)
        $locationFieldset = $sm->get('usarugbystats_competition_location_fieldset');
        $locationFieldset->setUseAsBaseFieldset(true);
        $form->add($locationFieldset);

        $form->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Create Location',
            ),
        ));

        // Construct the input filter

        $locationInputFilter = $sm->get('usarugbystats_competition_location_inputfilter');

        $if = new InputFilter();
        $if->add($locationInputFilter, 'location');
        $form->setInputFilter($if);

        return $form;
    }
}
