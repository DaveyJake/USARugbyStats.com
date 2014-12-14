<?php
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_dashboard_router_controller',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(),
            ),
            'zfcuser' => array(
                'options' => array(
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_account_controller',
                    ),
                ),
            ),
            'usarugbystats_frontend_dashboard' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/dashboard',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_dashboard_router_controller',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'competition-admin' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/competition-admin',
                            'defaults' => array(
                                'controller' => 'usarugbystats_competition-frontend_dashboard_competition-admin_controller',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    'team-admin' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/team-admin',
                            'defaults' => array(
                                'controller' => 'usarugbystats_competition-frontend_dashboard_team-admin_controller',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                    'union-admin' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/union-admin',
                            'defaults' => array(
                                'controller' => 'usarugbystats_competition-frontend_dashboard_union-admin_controller',
                                'action'     => 'index',
                            ),
                        ),
                    ),
                ),
            ),
            'usarugbystats_frontend_player' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/player/:id',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_player_controller',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'update' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update',
                            'defaults' => array(
                                'action'     => 'update',
                            ),
                        ),
                    ),
                ),
            ),
            'usarugbystats_frontend_team' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/team/:id',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_team_controller',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'update' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update',
                            'defaults' => array(
                                'action'     => 'update',
                            ),
                        ),
                    ),
                ),
            ),
            'usarugbystats_frontend_union' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/union/:id',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_union_controller',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'update' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update',
                            'defaults' => array(
                                'action'     => 'update',
                            ),
                        ),
                    ),
                ),
            ),
            'usarugbystats_frontend_competition' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/competition/:id',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_competition_controller',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'update' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update',
                            'defaults' => array(
                                'action'     => 'update',
                            ),
                        ),
                    ),
                ),
            ),
            'usarugbystats_frontend_competition_match' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/competition/:cid/match/:mid',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_competition_match_controller',
                        'action'     => 'view',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'render-match-row' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/render-match-row',
                            'defaults' => array(
                                'action'     => 'rende-rmatch-row',
                            ),
                        ),
                    ),
                    'copy-roster' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/copyRosterForTeam/:team',
                            'defaults' => array(
                                'action'     => 'copy-roster',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/delete',
                            'defaults' => array(
                                'action'     => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
            'usarugbystats_frontend_location' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/location',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_location_controller',
                        'action'     => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
                    'search' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/search',
                            'defaults' => array(
                                'action'     => 'search',
                            ),
                        ),
                    ),
                    'create' => array(
                        'type' => 'Literal',
                        'priority' => 1000,
                        'options' => array(
                            'route' => '/create',
                            'defaults' => array(
                                'action'     => 'create',
                            ),
                        ),
                    ),
                    'view' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id',
                            'defaults' => array(
                                'action'     => 'view',
                            ),
                        ),
                    ),
                    'update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:id/update',
                            'defaults' => array(
                                'action'     => 'update',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_competition-frontend_dashboard_router_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\RouterController',
            'usarugbystats_competition-frontend_dashboard_team-admin_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\TeamAdminController',
            'usarugbystats_competition-frontend_dashboard_union-admin_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\UnionAdminController',
            'usarugbystats_competition-frontend_dashboard_competition-admin_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\CompetitionAdminController',

            'usarugbystats_competition-frontend_account_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\AccountController',
            'usarugbystats_competition-frontend_player_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\PlayerController',
            'usarugbystats_competition-frontend_team_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\TeamController',
            'usarugbystats_competition-frontend_union_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\UnionController',
            'usarugbystats_competition-frontend_competition_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\CompetitionController',
            'usarugbystats_competition-frontend_competition_match_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\CompetitionMatchController',
            'usarugbystats_competition-frontend_location_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\LocationController',
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'home' => array('member'),
                'usarugbystats_frontend_dashboard' => array('member'),
                'usarugbystats_frontend_dashboard/team-admin' => array('team_admin'),
                'usarugbystats_frontend_dashboard/union-admin' => array('union_admin'),
                'usarugbystats_frontend_dashboard/competition-admin' => array('competition_admin'),
                'usarugbystats_frontend_player' => array('guest'),
                'usarugbystats_frontend_player/update' => array('team_admin', 'competition_admin'),
                'usarugbystats_frontend_team' => array('guest'),
                'usarugbystats_frontend_team/update' => array('team_admin'),
                'usarugbystats_frontend_union' => array('guest'),
                'usarugbystats_frontend_union/update' => array('union_admin'),
                'usarugbystats_frontend_competition' => array('guest'),
                'usarugbystats_frontend_competition/update' => array('union_admin', 'competition_admin'),
                'usarugbystats_frontend_competition_match' => array('guest'),
                'usarugbystats_frontend_competition_match/render-match-row' => array('guest'),
                'usarugbystats_frontend_competition_match/copy-roster' => array('team_admin', 'competition_admin'),
                'usarugbystats_frontend_competition_match/delete' => array('union_admin', 'competition_admin'),
                'usarugbystats_frontend_location' => array('member'),
                'usarugbystats_frontend_location/view' => array('guest'),
                'usarugbystats_frontend_location/search' => array('member'),
                'usarugbystats_frontend_location/create' => array('team_admin', 'competition_admin'),
                'usarugbystats_frontend_location/update' => array('team_admin', 'competition_admin'),

            ),
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
    'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                __DIR__ . '/../public',
            ),
        ),
    ),
);
