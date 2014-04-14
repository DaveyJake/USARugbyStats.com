<?php
namespace UsaRugbyStats\AccountAdmin;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $sl = $e->getApplication()->getServiceManager();

        // Override the AdminUserService with our own extension
        $zfcUserAdminController = $sl->get('ControllerLoader')->get('zfcuseradmin');
        $zfcUserAdminController->setAdminUserService($sl->get('UsaRugbyStats\AccountAdmin\Service\UserService'));
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
