<?php
return array(
    'router' => array(
        'routes' => array(
            'usarugbystats_frontend-embed_competition_standings' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/embed/competition/:id/standings',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend-embed_competition_standings',
                        'action'     => 'index',
                    ),
                ),
            ),
            'usarugbystats_frontend-embed_competition_schedule' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/embed/competition/:id/schedule',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend-embed_competition_schedule',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_competition-frontend-embed_competition_standings' => 'UsaRugbyStats\CompetitionFrontendEmbed\Controller\CompetitionStandingsController',
            'usarugbystats_competition-frontend-embed_competition_schedule' => 'UsaRugbyStats\CompetitionFrontendEmbed\Controller\CompetitionScheduleController',
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'usarugbystats_frontend-embed_*' => [ 'guest' ]
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'layout/embedded' => __DIR__ . '/../view/layout.phtml',

            'usa-rugby-stats/competition-frontend-embed/competition/standings' => __DIR__ . '/../view/competition/standings.phtml',
            'usa-rugby-stats/competition-frontend-embed/competition/schedule' => __DIR__ . '/../view/competition/schedule.phtml',
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
