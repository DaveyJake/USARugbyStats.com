<?php
namespace UsaRugbyStats\CompetitionFrontend\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class TeamLinkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $sl = ( $pluginManager instanceof AbstractPluginManager )
            ? $pluginManager->getServiceLocator()
            : $pluginManager;

        $viewHelper = new TeamLink();
        $viewHelper->setTeamService($sl->get('usarugbystats_competition_team_service'));

        return $viewHelper;
    }
}
