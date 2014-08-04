<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CanChangeTeamEventsFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $obj = new CanChangeTeamEvents();
        $obj->setAuthorizationService($sm->get('ZfcRbac\Service\AuthorizationService'));

        return $obj;
    }
}
