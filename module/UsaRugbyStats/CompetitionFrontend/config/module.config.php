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
                'child_routes' =>array(),
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
                    'update-details' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/update/details',
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
                    'route' => '/competition/:cid/match',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend_competition_match_controller',
                        'action'     => 'list',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' =>array(
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
                            'route' => '/:mid',
                            'defaults' => array(
                                'action'     => 'view',
                            ),
                        ),
                    ),
                    'update' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:mid/update',
                            'defaults' => array(
                                'action'     => 'update',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' =>array(
                            'copy-roster' => array(
                                'type' => 'Segment',
                                'options' => array(
                                    'route' => '/copyRosterForTeam/:team',
                                    'defaults' => array(
                                        'action'     => 'copy-roster',
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/:mid/delete',
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
            'usarugbystats_competition-frontend_dashboard_member_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\MemberController',
            'usarugbystats_competition-frontend_dashboard_team-admin_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\TeamAdminController',
            'usarugbystats_competition-frontend_dashboard_union-admin_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\UnionAdminController',
            'usarugbystats_competition-frontend_dashboard_competition-admin_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Dashboard\CompetitionAdminController',

            'usarugbystats_competition-frontend_player_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\PlayerController',
            'usarugbystats_competition-frontend_team_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\TeamController',
            'usarugbystats_competition-frontend_union_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\UnionController',
            'usarugbystats_competition-frontend_competition_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\CompetitionController',
            'usarugbystats_competition-frontend_competition_match_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\Competition\MatchController',
            'usarugbystats_competition-frontend_location_controller' => 'UsaRugbyStats\CompetitionFrontend\Controller\LocationController',
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'home' => array('member'),
                'usarugbystats_frontend_player' => array('member'),
                'usarugbystats_frontend_team' => array('member'),
                'usarugbystats_frontend_team/update' => array('team_admin'),
                'usarugbystats_frontend_union' => array('member'),
                'usarugbystats_frontend_union/update' => array('union_admin'),
                'usarugbystats_frontend_competition' => array('member'),
                'usarugbystats_frontend_competition/update-details' => array('union_admin', 'competition_admin'),
                'usarugbystats_frontend_competition/update-matches' => array('union_admin', 'competition_admin'),
                'usarugbystats_frontend_competition/create-match' => array('union_admin', 'competition_admin'),
                'usarugbystats_frontend_competition_match/view' => array('member'),
                'usarugbystats_frontend_competition_match' => array('member'),
                'usarugbystats_frontend_competition_match/create' => array('competition_admin'),
                'usarugbystats_frontend_competition_match/update' => array('team_admin', 'competition_admin'),
                'usarugbystats_frontend_competition_match/update/copy-roster' => array('team_admin', 'competition_admin'),
                'usarugbystats_frontend_competition_match/delete' => array('competition_admin'),
                'usarugbystats_frontend_location' => array('member'),
                'usarugbystats_frontend_location/view' => array('member'),
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
);
