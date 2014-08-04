<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CanChangeSignatureForCoachFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $obj = new CanChangeSignatureForCoach();
        $obj->setAuthorizationService($sm->get('ZfcRbac\Service\AuthorizationService'));

        return $obj;
    }
}
