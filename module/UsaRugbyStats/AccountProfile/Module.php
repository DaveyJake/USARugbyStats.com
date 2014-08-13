<?php
namespace UsaRugbyStats\AccountProfile;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Application\Entity\AccountInterface;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('Config');
        $remoteIdValidationGroup = $config['ldc-user-profile']['validation_group_overrides_remoteid'];

        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'LdcUserProfile\Service\ProfileService',
            'LdcUserProfile\Service\ProfileService::constructFormForUser.post',
            function (EventInterface $e) use ($remoteIdValidationGroup) {
                $user = $e->getParam('user');
                if ( ! $user instanceof AccountInterface || $user->getRemoteId() === NULL ) {
                    return;
                }
                $e->getParam('form')->setValidationGroup($remoteIdValidationGroup);
            }
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }
}
