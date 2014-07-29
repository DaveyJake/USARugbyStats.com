<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Controller\ControllerManager;

class SyncTeamControllerFactory implements FactoryInterface
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

        $injector = new SyncTeamController();
        $injector->setTeamService($sm->get('usarugbystats_competition_team_service'));

        return $injector;
    }
}
