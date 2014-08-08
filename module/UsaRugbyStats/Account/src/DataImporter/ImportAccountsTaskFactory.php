<?php
namespace UsaRugbyStats\Account\DataImporter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\DataImporter\Service\TaskService;

class ImportAccountsTaskFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new ImportAccountsTask($sm);

        return $service;
    }
}
