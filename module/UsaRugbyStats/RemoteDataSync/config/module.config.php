<?php
return array(
    'usa-rugby-stats' => array(
        'remote-data-sync' => array(
            'provider' => NULL,
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'usa-rugby-stats_remote-data-sync_provider_dummy' => 'UsaRugbyStats\RemoteDataSync\DataProvider\DummyDataProviderFactory',
            'usa-rugby-stats_remote-data-sync_provider_jsonwebservice' => 'UsaRugbyStats\RemoteDataSync\DataProvider\JsonWebServiceProviderFactory',
        ),
    ),

    'console' => array(
        'router' => array(
            'routes' => array(
                'usa-rugby-stats_remote-data-sync_injector_sync-team' => array(
                    'options' => array(
                        'route' => 'remote-data-sync sync_team --team-id=',
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
        ),
    ),
    'zfc_rbac' => array(
        'guards' => array(
            'ZfcRbac\Guard\RouteGuard' => array(
                'usa-rugby-stats_remote-data-sync_injector_sync-team' => [ 'guest' ],
            ),
        ),
    ),
);
