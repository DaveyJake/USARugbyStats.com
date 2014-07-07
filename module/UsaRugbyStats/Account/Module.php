<?php
namespace UsaRugbyStats\Account;

use Zend\Mvc\MvcEvent;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteInterface;
use UsaRugbyStats\Account\Listeners\AuditLogCommentSetterListener;

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

            // We only need to do this if the redirect parameter is a valid URI
            $parameterSource = $request->getPost()->offsetExists('redirect')
                ? $request->getPost()
                : $request->getQuery();

            $uri = new \Zend\Uri\Uri($parameterSource->get('redirect'));
            if ( ! $uri->isValid() ) {
                return;
            }

            // If it's an absolute URI and not the same as this host, kill it and short-circuit
            if ( $uri->isAbsolute() && $uri->getHost() != $request->getUri()->getHost() ) {
                $parameterSource->offsetUnset('redirect');

                return;
            }

            // Determine if the paramater passed in a valid route name.  If it is, we're done here...
            $parts = explode('/', $uri->getPath());
            $router = $e->getRouter();
            foreach ($parts as $part) {
                if ( ! $router->hasRoute($part) ) {
                    $router = null;
                    break;
                }
                $router = $router->getRoute($part);
            }
            if ($router instanceof RouteInterface) {
                return;
            }

            // Use the router to turn the relative URI provided into a route name
            $subreq = new \Zend\Http\Request();
            $subreq->setUri($uri->toString());
            $routeMatch = $e->getRouter()->match($subreq);

            // If it matches a route, set that route as the target...otherwise kill it
            if ($routeMatch instanceof RouteMatch) {
                $parameterSource->set('redirect', $routeMatch->getMatchedRouteName());
            } else {
                $parameterSource->offsetUnset('redirect');
            }

        }, 1000);

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
