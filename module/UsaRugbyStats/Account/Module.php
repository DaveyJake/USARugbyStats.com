<?php
namespace UsaRugbyStats\Account;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        // Register the 403 view renderer
        $t = $e->getTarget();        
        $t->getEventManager()->attach(
            $t->getServiceManager()->get('ZfcRbac\View\Strategy\UnauthorizedStrategy')
        );
        
        $app = $e->getApplication();

        // Automatically attach Member role to new users
        $userService = $app->getServiceManager()->get('zfcuser_user_service');
        $listener = $app->getServiceManager()->get('UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup');
        $listener->setGroups(['member']);
        $userService->getEventManager()->attach($listener);
        
        // Enforce match between discriminator and role object association in RoleAssignment objects
        $dem = $app->getServiceManager()->get('doctrine.eventmanager.orm_default');
        $dem->addEventSubscriber($app->getServiceManager()->get('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation'));
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
