<?php
return array(
    
    'router' => array(
        'routes' => array(
            'zfcadmin' => array(
                'child_routes' => array(
                    'usarugbystats_teamadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/team',
                            'defaults' => array(
                                'controller' => 'usarugbystats_teamadmin_controller',
                                'action'     => 'index',
                            ),
                        ),
                        'child_routes' =>array(
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list[/:p]',
                                    'defaults' => array(
                                        'action'     => 'list',
                                    ),
                                ),
                            ),
                            'create' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/create',
                                    'defaults' => array(
                                        'action'     => 'create'
                                    ),
                                ),
                            ),
                            'edit' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/edit/:userId',
                                    'defaults' => array(
                                        'action'     => 'edit',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove/:userId',
                                    'defaults' => array(
                                        'action'     => 'remove',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_teamadmin_controller' => 'UsaRugbyStats\CompetitionAdmin\Controller\TeamAdminController',
        ),
    ),    
    'navigation' => array(
        'admin' => array(
            'usarugbystats_teamadmin' => array(
                'label' => 'Teams',
                'route' => 'zfcadmin/usarugbystats_teamadmin/list',
                'pages' => array(
                    'create' => array(
                        'label' => 'New Team',
                        'route' => 'zfcadmin/usarugbystats_teamadmin/create',
                    ),
                ),
            ),
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
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);
