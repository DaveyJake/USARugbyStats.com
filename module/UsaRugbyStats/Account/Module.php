<?php
namespace UsaRugbyStats\Account;

use Zend\Mvc\MvcEvent;
use UsaRugbyStats\Account\Listeners\AuditLogCommentSetterListener;
use UsaRugbyStats\Application\Service\StaticEventManagerListenerService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();

        StaticEventManagerListenerService::registerListeners($app->getServiceManager());

        // Automatically attach Member role to new users
        $userService = $app->getServiceManager()->get('zfcuser_user_service');
        $listener = $app->getServiceManager()->get('UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup');
        $listener->setGroups(['member']);
        $userService->getEventManager()->attach($listener);

        // Enforce match between discriminator and role object association in RoleAssignment objects
        $dem = $app->getServiceManager()->get('doctrine.eventmanager.orm_default');
        $dem->addEventSubscriber($app->getServiceManager()->get('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation'));

        // Automatically set audit log entry comments
        if ( $app->getServiceManager()->has('auditService') ) {
            $listener = new AuditLogCommentSetterListener(
                $app->getServiceManager()->get('auditService')
            );
            $listener->attach($userService->getUserMapper()->getEventManager());
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
