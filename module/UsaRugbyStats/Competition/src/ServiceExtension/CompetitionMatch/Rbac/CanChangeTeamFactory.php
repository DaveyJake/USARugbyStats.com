<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CanChangeTeamFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $obj = new CanChangeTeam();
        $obj->setAuthorizationService($sm->get('ZfcRbac\Service\AuthorizationService'));

        return $obj;
    }
}
