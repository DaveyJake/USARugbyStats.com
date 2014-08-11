<?php
return array(
    'usarugbystats' => array(
        'application' => array(
            'iframeable' => array(
                'routes' => array(),
                'layout' => 'layout/iframe',
            ),
            'event_listeners' => array(
            )
        ),
    ),

    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'UsaRugbyStats\Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'UsaRugbyStats\Application\Controller\Index' => 'UsaRugbyStats\Application\Controller\IndexController',
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'home' => array('member'),
            ),
        ),
    ),
    'navigation' => array(
        'default' => array(
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'factories' => array(
            'default' => 'Zend\Navigation\Service\DefaultNavigationFactory',
            'usarugbystats_application_listener_iframeablepage' => 'UsaRugbyStats\Application\Listener\IframeablePageListenerFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'isIframed' => 'UsaRugbyStats\Application\View\Helper\IsIframedFactory',
        ),
    ),

    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'usarugbystats_application_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/doctrine/self'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'UsaRugbyStats\Application\Entity'  => 'usarugbystats_application_entity'
                )
            )
        ),
    ),
);
