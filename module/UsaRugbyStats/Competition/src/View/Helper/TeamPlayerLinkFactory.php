<?php
namespace UsaRugbyStats\Competition\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractPluginManager;

class TeamPlayerLinkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $sl = ( $pluginManager instanceof AbstractPluginManager )
            ? $pluginManager->getServiceLocator()
            : $pluginManager;

        $em = $sl->get('zfcuser_doctrine_em');

        $viewHelper = new TeamPlayerLink();
        $viewHelper->setMatchTeamPlayerRepository($em->getRepository('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer'));

        return $viewHelper;
    }
}
