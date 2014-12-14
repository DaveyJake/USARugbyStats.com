<?php
namespace UsaRugbyStats\AccountProfile;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventInterface;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $sl = $e->getApplication()->getServiceManager();
        $vgCalculator = $sl->get('usarugbystats-accountprofile_helper_profilerbachelper');

        // Preprocess the account profile form to enforce RBAC permissions
        $e->getApplication()->getEventManager()->getSharedManager()->attach(
            'LdcUserProfile\Service\ProfileService',
            'LdcUserProfile\Service\ProfileService::constructFormForUser.post',
            function (EventInterface $e) use ($vgCalculator) {
                $vg = $vgCalculator->processForm($e->getParam('form'), $e->getParam('user'));
                $e->getParam('form')->setValidationGroup($vg);
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
