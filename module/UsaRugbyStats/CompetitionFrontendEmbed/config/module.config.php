<?php
return array(
    'router' => array(
        'routes' => array(
            'usarugbystats_frontend-embed_team_schedule' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/embed/team/:tid/schedule',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend-embed_team_schedule',
                        'action'     => 'index',
                    ),
                ),
            ),
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
            'usarugbystats_frontend-embed_competition_match_rosters' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/embed/competition/:cid/match/:mid/rosters',
                    'defaults' => array(
                        'controller' => 'usarugbystats_competition-frontend-embed_competition_match_rosters',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'usarugbystats_competition-frontend-embed_team_schedule' => 'UsaRugbyStats\CompetitionFrontendEmbed\Controller\TeamScheduleController',
            'usarugbystats_competition-frontend-embed_competition_standings' => 'UsaRugbyStats\CompetitionFrontendEmbed\Controller\CompetitionStandingsController',
            'usarugbystats_competition-frontend-embed_competition_schedule' => 'UsaRugbyStats\CompetitionFrontendEmbed\Controller\CompetitionScheduleController',
            'usarugbystats_competition-frontend-embed_competition_match_rosters' => 'UsaRugbyStats\CompetitionFrontendEmbed\Controller\CompetitionMatchRostersController',
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

            'usa-rugby-stats/competition-frontend-embed/team/schedule' => __DIR__ . '/../view/team/schedule.phtml',
            'usa-rugby-stats/competition-frontend-embed/competition/standings' => __DIR__ . '/../view/competition/standings.phtml',
            'usa-rugby-stats/competition-frontend-embed/competition/schedule' => __DIR__ . '/../view/competition/schedule.phtml',
            'usa-rugby-stats/competition-frontend-embed/competition/match/rosters' => __DIR__ . '/../view/competition/match/rosters.phtml',
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
