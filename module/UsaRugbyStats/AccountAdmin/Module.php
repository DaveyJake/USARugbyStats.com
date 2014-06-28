<?php
namespace UsaRugbyStats\AccountAdmin;

use Zend\Mvc\MvcEvent;
use UsaRugbyStats\AccountAdmin\Listeners\AuditLogCommentSetterListener;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $sl = $e->getApplication()->getServiceManager();

        $userService = $sl->get('UsaRugbyStats\AccountAdmin\Service\UserService');

        // Override the AdminUserService with our own extension
        $zfcUserAdminController = $sl->get('ControllerLoader')->get('zfcuseradmin');
        $zfcUserAdminController->setAdminUserService($userService);

        // Automatically attach Member role to new users
        $listener = $sl->get('UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup');
        $listener->setGroups(['member']);
        $userService->getEventManager()->attach($listener);

        // Automatically set audit log entry comments
        if ( $sl->has('auditService') ) {
            $auditService = $sl->get('auditService');

            $listener = new AuditLogCommentSetterListener($auditService);
            $listener->attach($userService->getEventManager());
        }
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
