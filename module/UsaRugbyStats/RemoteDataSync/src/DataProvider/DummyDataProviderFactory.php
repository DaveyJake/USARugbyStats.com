<?php
namespace UsaRugbyStats\RemoteDataSync\DataProvider;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DummyDataProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return DummyDataProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        $job = new DummyDataProvider();
        $job->dummyData = $config['usa-rugby-stats']['remote-data-sync']['dummy-data'];

        return $job;
    }
}
