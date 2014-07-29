<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\Status;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

class StatusCheckerFunctionViewHelperFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return SyncTeam
     */
    public function createService(ServiceLocatorInterface $pm)
    {
        $sm = $pm instanceof HelperPluginManager
            ? $pm->getServiceLocator()
            : $pm;

        $injector = new StatusCheckerFunctionViewHelper();

        return $injector;
    }
}
