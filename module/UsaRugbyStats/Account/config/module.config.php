<?php
return array(
    'router' => array(
        'routes' => array(
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
);
