<?php
namespace UsaRugbyStats\CompetitionAdmin\Form;

use Zend\ServiceManager\ServiceLocatorInterface;

class TeamUpdateFormFactory extends TeamCreateFormFactory
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $form = parent::createService($sm);

        $form->setName('update-team');
        $form->get('submit')->setOptions(['label' => 'Save Changes']);

        return $form;
    }
}
