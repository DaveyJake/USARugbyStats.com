<?php
namespace UsaRugbyStats\Competition\DataImporter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ImportTeamsTaskFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new ImportTeamsTask(
            $sm->get('usarugbystats_competition_team_service')
        );

        return $service;
    }
}
