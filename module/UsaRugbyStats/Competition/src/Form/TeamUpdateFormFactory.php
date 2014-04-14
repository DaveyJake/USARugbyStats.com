<?php
namespace UsaRugbyStats\Competition\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TeamUpdateFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = $sm->get('usarugbystats_competition_team_createform');

        $form->setName('update-team');
        $form->get('submit')->setOptions(['label' => 'Save Changes']);

        return $form;
    }
}
