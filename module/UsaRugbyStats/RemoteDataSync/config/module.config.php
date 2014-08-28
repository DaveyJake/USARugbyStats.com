<?php
return array(
    'usa-rugby-stats' => array(
        'remote-data-sync' => array(
            'provider' => NULL,
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
            'usa-rugby-stats_remote-data-sync_queueprovider' => 'UsaRugbyStats\RemoteDataSync\Queue\Resque',
        ),
        'factories' => array(
            'usa-rugby-stats_remote-data-sync_provider_dummy' => 'UsaRugbyStats\RemoteDataSync\DataProvider\DummyDataProviderFactory',
            'usa-rugby-stats_remote-data-sync_provider_jsonwebservice' => 'UsaRugbyStats\RemoteDataSync\DataProvider\JsonWebServiceProviderFactory',
        ),
    ),

    'router' => array(
        'routes' => array(
            'usarugbystats_remotedatasync_queue_trigger_statuscheck' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/queue/jobstatus',
                    'defaults' => array(
                        'controller' => 'usarugbystats_remotedatasync_queue_trigger_statuscheck',
                        'action'     => 'index',
                    ),
                ),
            ),
            'usarugbystats_remotedatasync_queue_trigger_syncteam' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/queue/trigger/sync_team',
                    'defaults' => array(
                        'controller' => 'usarugbystats_remotedatasync_controller_queuetrigger_sync-team',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'usa-rugby-stats_remote-data-sync_injector_sync-team' => array(
                    'options' => array(
                        'route' => 'remote-data-sync sync_team --team-id= [--wait=]',
                        'defaults' => array(
                            'controller' => 'usa-rugby-stats_remote-data-sync_injector_sync-team',
                            'action'     => 'execute',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
       'factories' => array(
           'usa-rugby-stats_remote-data-sync_injector_sync-team' => 'UsaRugbyStats\RemoteDataSync\Injectors\SyncTeamFactory',
           'usarugbystats_remotedatasync_controller_queuetrigger_sync-team' => 'UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam\SyncTeamControllerFactory',

           'usarugbystats_remotedatasync_queue_trigger_statuscheck' => 'UsaRugbyStats\RemoteDataSync\Frontend\Status\StatusControllerFactory',
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'usa-rugby-stats_remote-data-sync_injector_sync-team' => [ 'guest' ],

                'usarugbystats_remotedatasync_queue_trigger_syncteam' => [ 'member' ],
                'usarugbystats_remotedatasync_queue_trigger_statuscheck' => [ 'guest' ],
            ),
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'ursRemoteDataSyncTriggerSyncTeam' => 'UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam\SyncTeamViewHelperFactory',
            'ursRemoteDataSyncJobStatusCheckerFunction' => 'UsaRugbyStats\RemoteDataSync\Frontend\Status\StatusCheckerFunctionViewHelperFactory',
        ),
    ),
);
