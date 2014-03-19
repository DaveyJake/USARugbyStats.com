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
                    'add_card' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_card.php',
                        ),
                    ),
                    'add_card_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_card_process.php',
                        ),
                    ),
                    'add_comp' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_comp.php',
                        ),
                    ),
                    'add_comp_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_comp_process.php',
                        ),
                    ),
                    'add_division' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_division.php',
                        ),
                    ),
                    'add_game' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_game.php',
                        ),
                    ),
                    'add_game_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_game_process.php',
                        ),
                    ),
                    'add_score' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_score.php',
                        ),
                    ),
                    'add_score_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_score_process.php',
                        ),
                    ),
                    'add_status' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_status.php',
                        ),
                    ),
                    'add_status_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_status_process.php',
                        ),
                    ),
                    'add_sub' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_sub.php',
                        ),
                    ),
                    'add_sub_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_sub_process.php',
                        ),
                    ),
                    'add_team' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_team.php',
                        ),
                    ),
                    'add_team_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'add_team_process.php',
                        ),
                    ),
                    'comp_games' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'comp_games.php',
                        ),
                    ),
                    'comp_info' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'comp_info.php',
                        ),
                    ),
                    'comp_list' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'comp_list.php',
                        ),
                    ),
                    'comp' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'comp.php',
                        ),
                    ),
                    'comp_teams' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'comp_teams.php',
                        ),
                    ),
                    'db_update' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update.php',
                        ),
                    ),
                    'db_update_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update_process.php',
                        ),
                    ),
                    'db_update_team_check' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update_team_check.php',
                        ),
                    ),
                    'db_update_team_list' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update_team_list.php',
                        ),
                    ),
                    'db_update_team' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update_team.php',
                        ),
                    ),
                    'db_update_team_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update_team_process.php',
                        ),
                    ),
                    'db_update_users_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'db_update_users_process.php',
                        ),
                    ),
                    'delete_card_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'delete_card_process.php',
                        ),
                    ),
                    'delete_game_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'delete_game_process.php',
                        ),
                    ),
                    'delete_score_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'delete_score_process.php',
                        ),
                    ),
                    'delete_sub_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'delete_sub_process.php',
                        ),
                    ),
                    'delete_team_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'delete_team_process.php',
                        ),
                    ),
                    'delete_user_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'delete_user_process.php',
                        ),
                    ),
                    'edit_event_roster' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_event_roster.php',
                        ),
                    ),
                    'edit_event_roster_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_event_roster_process.php',
                        ),
                    ),
                    'edit_game_info' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_game_info.php',
                        ),
                    ),
                    'edit_game_info_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_game_info_process.php',
                        ),
                    ),
                    'edit_game_roster' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_game_roster.php',
                        ),
                    ),
                    'edit_game_roster_previous_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_game_roster_previous_process.php',
                        ),
                    ),
                    'edit_game_roster_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_game_roster_process.php',
                        ),
                    ),
                    'edit_user' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_user.php',
                        ),
                    ),
                    'edit_user_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'edit_user_process.php',
                        ),
                    ),
                    'event_roster' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'event_roster.php',
                        ),
                    ),
                    'game_card_events' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_card_events.php',
                        ),
                    ),
                    'game_header' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_header.php',
                        ),
                    ),
                    'game_info' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_info.php',
                        ),
                    ),
                    'game' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game.php',
                        ),
                    ),
                    'game_player_sort' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_player_sort.php',
                        ),
                    ),
                    'game_roster_info' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_roster_info.php',
                        ),
                    ),
                    'game_roster' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_roster.php',
                        ),
                    ),
                    'game_rosters' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_rosters.php',
                        ),
                    ),
                    'game_score_events' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_score_events.php',
                        ),
                    ),
                    'game_score' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_score.php',
                        ),
                    ),
                    'game_sub_events' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'game_sub_events.php',
                        ),
                    ),
                    'group_members_sync' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'group_members_sync.php',
                        ),
                    ),
                    'groups_active' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'groups_active.php',
                        ),
                    ),
                    'groups_sync' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'groups_sync.php',
                        ),
                    ),
                    'help' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'help.php',
                        ),
                    ),
                    'hide_comp_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'hide_comp_process.php',
                        ),
                    ),
                    'player_role_sync_poll' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'player_role_sync_poll.php',
                        ),
                    ),
                    'player_sort' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'player_sort.php',
                        ),
                    ),
                    'player_sync_poll' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'player_sync_poll.php',
                        ),
                    ),
                    'signatures' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'signatures.php',
                        ),
                    ),
                    'signatures_process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'signatures_process.php',
                        ),
                    ),
                    'team_event_rosters' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'team_event_rosters.php',
                        ),
                    ),
                    'team_game_rosters' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'team_game_rosters.php',
                        ),
                    ),
                    'team_games' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'team_games.php',
                        ),
                    ),
                    'team' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'team.php',
                        ),
                    ),

                    'silex-processqueue'  => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'processqueue',
                            'target-file' => 'index.php',
                            'use-layout' => false,
                        ),
                    ),
                    'silex-group-above'  => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'group_above',
                            'target-file' => 'index.php',
                            'use-layout' => false,
                        ),
                    ),
                    'silex-sync'  => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'sync',
                            'target-file' => 'index.php',
                            'use-layout' => false,
                        ),
                    ),
                    'silex-standings'  => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'standings',
                            'target-file' => 'index.php',
                            'use-layout' => false,
                        ),
                    ),
                    'silex-standings-xml'  => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'standings.xml',
                            'target-file' => 'index.php',
                            'use-layout' => false,
                        ),
                    ),
                    'silex-player'  => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => 'player',
                            'target-file' => 'index.php',
                            'use-layout' => false,
                        ),
                    ),
                ),
            ),
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'urs-la/add_card' => array('team_admin'),
                'urs-la/add_card_process' => array('team_admin'),
                'urs-la/add_comp' => array('team_admin'),
                'urs-la/add_comp_process' => array('team_admin'),
                'urs-la/add_division' => array('team_admin'),
                'urs-la/add_game' => array('team_admin'),
                'urs-la/add_game_process' => array('team_admin'),
                'urs-la/add_score' => array('team_admin'),
                'urs-la/add_score_process' => array('team_admin'),
                'urs-la/add_status' => array('team_admin'),
                'urs-la/add_status_process' => array('team_admin'),
                'urs-la/add_sub' => array('team_admin'),
                'urs-la/add_sub_process' => array('team_admin'),
                'urs-la/add_team' => array('team_admin'),
                'urs-la/add_team_process' => array('team_admin'),
                'urs-la/comp_games' => array('team_admin'),
                'urs-la/comp_info' => array('team_admin'),
                'urs-la/comp_list' => array('team_admin'),
                'urs-la/comp' => array('team_admin'),
                'urs-la/comp_teams' => array('team_admin'),
                'urs-la/db_update' => array('team_admin'),
                'urs-la/db_update_process' => array('team_admin'),
                'urs-la/db_update_team_check' => array('team_admin'),
                'urs-la/db_update_team_list' => array('team_admin'),
                'urs-la/db_update_team' => array('team_admin'),
                'urs-la/db_update_team_process' => array('team_admin'),
                'urs-la/db_update_users_process' => array('team_admin'),
                'urs-la/delete_card_process' => array('team_admin'),
                'urs-la/delete_game_process' => array('team_admin'),
                'urs-la/delete_score_process' => array('team_admin'),
                'urs-la/delete_sub_process' => array('team_admin'),
                'urs-la/delete_team_process' => array('team_admin'),
                'urs-la/delete_user_process' => array('team_admin'),
                'urs-la/edit_event_roster' => array('team_admin'),
                'urs-la/edit_event_roster_process' => array('team_admin'),
                'urs-la/edit_game_info' => array('team_admin'),
                'urs-la/edit_game_info_process' => array('team_admin'),
                'urs-la/edit_game_roster' => array('team_admin'),
                'urs-la/edit_game_roster_previous_process' => array('team_admin'),
                'urs-la/edit_game_roster_process' => array('team_admin'),
                'urs-la/edit_user' => array('team_admin'),
                'urs-la/edit_user_process' => array('team_admin'),
                'urs-la/event_roster' => array('team_admin'),
                'urs-la/game_card_events' => array('team_admin'),
                'urs-la/game_header' => array('team_admin'),
                'urs-la/game_info' => array('team_admin'),
                'urs-la/game' => array('team_admin'),
                'urs-la/game_player_sort' => array('team_admin'),
                'urs-la/game_roster_info' => array('team_admin'),
                'urs-la/game_roster' => array('team_admin'),
                'urs-la/game_rosters' => array('team_admin'),
                'urs-la/game_score_events' => array('team_admin'),
                'urs-la/game_score' => array('team_admin'),
                'urs-la/game_sub_events' => array('team_admin'),
                'urs-la/group_members_sync' => array('team_admin'),
                'urs-la/groups_active' => array('team_admin'),
                'urs-la/groups_sync' => array('team_admin'),
                'urs-la/help' => array('member'),
                'urs-la/hide_comp_process' => array('team_admin'),
                'urs-la/player_role_sync_poll' => array('team_admin'),
                'urs-la/player_sort' => array('team_admin'),
                'urs-la/player_sync_poll' => array('team_admin'),
                'urs-la/signatures' => array('team_admin'),
                'urs-la/signatures_process' => array('team_admin'),
                'urs-la/team_event_rosters' => array('team_admin'),
                'urs-la/team_game_rosters' => array('team_admin'),
                'urs-la/team_games' => array('team_admin'),
                'urs-la/team' => array('team_admin'),
                
                'urs-la/silex-processqueue' => array('team_admin'),
                'urs-la/silex-group-above' => array('team_admin'),
                'urs-la/silex-sync' => array('team_admin'),
                'urs-la/silex-standings' => array('team_admin'),
                'urs-la/silex-standings-xml' => array('team_admin'),
                'urs-la/silex-player' => array('team_admin'),
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
