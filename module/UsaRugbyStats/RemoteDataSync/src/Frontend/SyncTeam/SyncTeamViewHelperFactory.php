<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\HelperPluginManager;

class SyncTeamViewHelperFactory implements FactoryInterface
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

        $injector = new SyncTeamViewHelper();

        return $injector;
    }
}
