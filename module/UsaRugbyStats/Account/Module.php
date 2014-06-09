<?php
namespace UsaRugbyStats\Account;

use Zend\Mvc\MvcEvent;
use Zend\Http\PhpEnvironment\Request;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();

        // Automatically attach Member role to new users
        $userService = $app->getServiceManager()->get('zfcuser_user_service');
        $listener = $app->getServiceManager()->get('UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup');
        $listener->setGroups(['member']);
        $userService->getEventManager()->attach($listener);

        // Enforce match between discriminator and role object association in RoleAssignment objects
        $dem = $app->getServiceManager()->get('doctrine.eventmanager.orm_default');
        $dem->addEventSubscriber($app->getServiceManager()->get('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation'));

        $app->getEventManager()->attach(MvcEvent::EVENT_DISPATCH, function (MvcEvent $e) {
            if ( ! $e->getRouteMatch()->getMatchedRouteName() == 'zfcuser/login' ) {
                return;
            }

            $request = $e->getRequest();
            if (! $request instanceof Request) {
                return;
            }

            $uri = new \Zend\Uri\Uri($request->getQuery('redirect'));
            if ( $uri->isAbsolute() ) {
                $redirect = ($uri->getHost() == $request->getUri()->getHost())
                    ? $uri->getPath()
                    : NULL;
                $request->getQuery()->set('redirect', $redirect);
            }
        }, 1000);
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
