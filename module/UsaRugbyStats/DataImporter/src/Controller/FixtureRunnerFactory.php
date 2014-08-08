<?php
namespace UsaRugbyStats\DataImporter\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;

class FixtureRunnerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return SyncTeam
     */
    public function createService(ServiceLocatorInterface $pm)
    {
        $sm = $pm instanceof ControllerManager
            ? $pm->getServiceLocator()
            : $pm;

        $controller = new FixtureRunner();
        $controller->setServiceLocator($sm);

        return $controller;
    }
}
