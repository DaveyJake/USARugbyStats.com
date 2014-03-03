<?php
return array(
    'router' => array(
        'routes' => array(
            'zfcuser' => array(
                'options' => array(
                    'route' => '/account',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
        ),
    ),
    'service_manager' => array(
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
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'usarugbystats_account_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/doctrine/self'
            ),
    
            'orm_default' => array(
                'drivers' => array(
                    'UsaRugbyStats\Account\Entity'  => 'usarugbystats_account_entity'
                )
            )
        )
    ),
);
