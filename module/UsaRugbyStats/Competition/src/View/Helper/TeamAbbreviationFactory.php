<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class TeamAbbreviationFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $sl = ( $pluginManager instanceof AbstractPluginManager )
            ? $pluginManager->getServiceLocator()
            : $pluginManager;

        $viewHelper = new TeamAbbreviation();
        $viewHelper->setTeamService($sl->get('usarugbystats_competition_team_service'));

        return $viewHelper;
    }
}
