<?php
namespace UsaRugbyStats\Competition\Form\Competition;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MatchUpdateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = $sm->get('usarugbystats_competition_competition_match_createform');

        $form->setName('update-competition-match');
        $form->get('submit')->setOptions(['label' => 'Save Changes']);

        return $form;
    }
}
