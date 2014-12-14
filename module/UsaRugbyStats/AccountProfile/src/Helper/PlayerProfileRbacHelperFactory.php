<?php
namespace UsaRugbyStats\AccountProfile\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlayerProfileRbacHelperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new PlayerProfileRbacHelper(
            $sm->get('ZfcRbac\Service\AuthorizationService')
        );

        return $service;
    }
}
