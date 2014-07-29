<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\Status;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;

class StatusControllerFactory implements FactoryInterface
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

        $injector = new StatusController();

        return $injector;
    }
}
