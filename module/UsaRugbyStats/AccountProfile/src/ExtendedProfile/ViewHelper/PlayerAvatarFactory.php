<?php
namespace UsaRugbyStats\AccountProfile\ExtendedProfile\ViewHelper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class PlayerAvatarFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $pluginManager)
    {
        $sl = ( $pluginManager instanceof AbstractPluginManager )
            ? $pluginManager->getServiceLocator()
            : $pluginManager;

        $viewHelper = new PlayerAvatar(
            $sl->get('ldc-user-profile_service')
        );

        return $viewHelper;
    }
}
