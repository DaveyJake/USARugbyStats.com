<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Form\Form;

class CompetitionUpdateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = $sm->get('usarugbystats_competition_competition_createform');

        $form->setName('update-competition');
        $form->get('submit')->setOptions(['label' => 'Save Changes']);

        return $form;
    }
}
