<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CanChangeTeamRosterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $obj = new CanChangeTeamRoster();
        $obj->setAuthorizationService($sm->get('ZfcRbac\Service\AuthorizationService'));

        return $obj;
    }
}
