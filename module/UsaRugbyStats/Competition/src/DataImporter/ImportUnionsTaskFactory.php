<?php
namespace UsaRugbyStats\Competition\DataImporter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportUnionsTaskFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new ImportUnionsTask(
            $sm->get('usarugbystats_competition_union_service')
        );

        return $service;
    }
}
