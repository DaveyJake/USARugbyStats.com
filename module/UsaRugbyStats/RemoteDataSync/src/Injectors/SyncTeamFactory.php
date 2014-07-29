<?php
namespace UsaRugbyStats\RemoteDataSync\Injectors;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;

class SyncTeamFactory implements FactoryInterface
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

        $injector = new SyncTeam();
        $injector->setTeamService($sm->get('usarugbystats_competition_team_service'));

        return $injector;
    }
}
