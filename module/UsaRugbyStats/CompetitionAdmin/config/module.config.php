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
                                    'route' => '/list',
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
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action'     => 'edit',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove/:id',
                                    'defaults' => array(
                                        'action'     => 'remove',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'usarugbystats_unionadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/union',
                            'defaults' => array(
                                'controller' => 'usarugbystats_unionadmin_controller',
                                'action'     => 'index',
                            ),
                        ),
                        'child_routes' =>array(
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list',
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
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action'     => 'edit',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove/:id',
                                    'defaults' => array(
                                        'action'     => 'remove',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'usarugbystats_competitionadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/competition',
                            'defaults' => array(
                                'controller' => 'usarugbystats_competitionadmin_controller',
                                'action'     => 'index',
                            ),
                        ),
                        'child_routes' =>array(
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list',
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
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action'     => 'edit',
                                        'userId'     => 0
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'divisions' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/divisions',
                                            'defaults' => array(
                                                'action'     => 'divisions',
                                            ),
                                        ),
                                    ),
                                    'matches' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/matches',
                                            'defaults' => array(
                                                'controller' => 'usarugbystats_competitionmatchadmin_controller',
                                                'action'     => 'list',
                                            ),
                                        ),
                                        'may_terminate' => true,
                                        'child_routes' =>array(
                                            'list' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/list',
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
                                                    'route' => '/edit/:match',
                                                    'defaults' => array(
                                                        'action'     => 'edit',
                                                    ),
                                                ),
                                            ),
                                            'remove' => array(
                                                'type' => 'Segment',
                                                'options' => array(
                                                    'route' => '/remove/:match',
                                                    'defaults' => array(
                                                        'action'     => 'remove',
                                                    ),
                                                ),
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'view' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/view/:id',
                                    'defaults' => array(
                                        'action'     => 'view',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' =>array(
                                    'standings' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/standings',
                                            'defaults' => array(
                                                'action'     => 'view-standings',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove/:id',
                                    'defaults' => array(
                                        'action'     => 'remove',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'usarugbystats_locationadmin' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/locations',
                            'defaults' => array(
                                'controller' => 'usarugbystats_locationadmin_controller',
                                'action'     => 'index',
                            ),
                        ),
                        'child_routes' =>array(
                            'list' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/list',
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
                                    'route' => '/edit/:id',
                                    'defaults' => array(
                                        'action'     => 'edit',
                                        'userId'     => 0
                                    ),
                                ),
                            ),
                            'remove' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/remove/:id',
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
            'usarugbystats_unionadmin_controller' => 'UsaRugbyStats\CompetitionAdmin\Controller\UnionAdminController',
            'usarugbystats_locationadmin_controller' => 'UsaRugbyStats\CompetitionAdmin\Controller\LocationAdminController',
            'usarugbystats_competitionadmin_controller' => 'UsaRugbyStats\CompetitionAdmin\Controller\CompetitionAdminController',
            'usarugbystats_competitionmatchadmin_controller' => 'UsaRugbyStats\CompetitionAdmin\Controller\CompetitionMatchAdminController',
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
            'usarugbystats_unionadmin' => array(
                'label' => 'Unions',
                'route' => 'zfcadmin/usarugbystats_unionadmin/list',
                'pages' => array(
                    'create' => array(
                        'label' => 'New Union',
                        'route' => 'zfcadmin/usarugbystats_unionadmin/create',
                    ),
                ),
            ),
            'usarugbystats_competitionadmin' => array(
                'label' => 'Competitions',
                'route' => 'zfcadmin/usarugbystats_competitionadmin/list',
                'pages' => array(
                    'create' => array(
                        'label' => 'New Competition',
                        'route' => 'zfcadmin/usarugbystats_competitionadmin/create',
                    ),
                ),
            ),
            'usarugbystats_locationadmin' => array(
                'label' => 'Locations / Venues',
                'route' => 'zfcadmin/usarugbystats_locationadmin/list',
                'pages' => array(),
            ),
        ),
    ),

    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'usarugbystats_competition-admin_team_service' => 'UsaRugbyStats\CompetitionAdmin\Service\TeamAdminServiceFactory',
            'usarugbystats_competition-admin_team_administrator_fieldset' => 'UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team\AdministratorFieldsetFactory',
            'usarugbystats_competition-admin_team_createform' => 'UsaRugbyStats\CompetitionAdmin\Form\TeamCreateFormFactory',
            'usarugbystats_competition-admin_team_updateform' => 'UsaRugbyStats\CompetitionAdmin\Form\TeamUpdateFormFactory',
        ),
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
