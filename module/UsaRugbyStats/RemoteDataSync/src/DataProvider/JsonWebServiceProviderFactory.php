<?php
namespace UsaRugbyStats\RemoteDataSync\DataProvider;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class JsonWebServiceProviderFactory implements FactoryInterface
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

        $job = new JsonWebServiceProvider();
        $job->setWebServiceEndpoint($config['usa-rugby-stats']['remote-data-sync']['web_service_endpoint']);

        return $job;
    }
}
