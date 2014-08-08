<?php
namespace UsaRugbyStats\DataImporter\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TaskServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return SyncTeam
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $config = $sm->get('Config')['usarugbystats']['data-importer']['tasks'];
        $service = new TaskService(new TaskServiceConfiguration($config));
        $service->addPeeringServiceManager($sm);
        // @HACK this is required due to a bug in the ServiceManager
        // (non-shared service from peer SM becomes shared as "top" SM caches it)
        $service->setRetrieveFromPeeringManagerFirst(true);

        return $service;
    }
}
