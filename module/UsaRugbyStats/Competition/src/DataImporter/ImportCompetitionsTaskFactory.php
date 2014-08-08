<?php
namespace UsaRugbyStats\Competition\DataImporter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportCompetitionsTaskFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new ImportCompetitionsTask(
            $sm->get('usarugbystats_competition_competition_service')
        );

        return $service;
    }
}
