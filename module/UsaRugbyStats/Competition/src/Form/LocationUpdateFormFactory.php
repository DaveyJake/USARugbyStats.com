<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocationUpdateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = $sm->get('usarugbystats_competition_location_createform');

        $form->setName('update-location');
        $form->get('submit')->setOptions(['label' => 'Save Changes']);

        return $form;
    }
}
