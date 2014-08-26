<?php
return array(
    'usarugbystats' => array(
        'application' => array(
            'event_listeners' => array(
            )
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
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'zfc-admin/admin/index'   => __DIR__ . '/../view/zfc-admin/admin/index.phtml',
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
