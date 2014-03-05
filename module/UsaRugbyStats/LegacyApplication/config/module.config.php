<?php
return array(
    'usarugbystats' => array(
	    'legacy-application' => array(
    	    'directory' => '/path/to/usarugbystats/app'
        ),
    ),

    'router' => array(
        'routes' => array(
            'urs-la' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'usarugbystats-legacyapplication_page',
                        'action'     => 'render',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'help' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'help.php',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'urs-la/*' => array('member'),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'usarugbystats-legacyapplication_page' => 'UsaRugbyStats\LegacyApplication\Controller\PageController',
        ),
    ),
    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'usa-rugby-stats/legacy-application/render' => __DIR__ . '/../view/render.phtml',
        ),
    ),
);
