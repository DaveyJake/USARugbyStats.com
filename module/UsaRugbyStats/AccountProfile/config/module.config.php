<?php
return array(
    'ldc-user-profile' => array(
        'registered_extensions' => array(
            'usarugbystats-accountprofile_extprofile_extension' => true,
            'usarugbystats-accountprofile_personalstats_extension' => true,
        ),
    ),

    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'ldc-user-profile_extension_zfcuser_inputfilter' => 'UsaRugbyStats\AccountProfile\ZfcUser\ZfcUserInputFilterFactory',

            'usarugbystats-accountprofile_personalstats_extension' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionFactory',
            'usarugbystats-accountprofile_personalstats_extension_service' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionServiceFactory',

            'usarugbystats-accountprofile_extprofile_extension' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionFactory',
            'usarugbystats-accountprofile_extprofile_extension_service' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionServiceFactory',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'ldc-user-profile/profile/index' => __DIR__ . '/../view/ldc-user-profile/profile/index.phtml',
            'usa-rugby-stats/account-profile/form' => __DIR__ . '/../view/usa-rugby-stats/account-profile/form.phtml',
            'ldc-user-profile/profile/extension/zfcuser' => __DIR__ . '/../view/ldc-user-profile/profile/extension/zfcuser.phtml',

            'ldc-user-profile/profile/extension/personalstats' => __DIR__ . '/../view/ldc-user-profile/profile/extension/personalstats.phtml',
            'usa-rugby-stats/account-profile/personal-stats/user-timeseries-chart' => __DIR__ . '/../view/usa-rugby-stats/account-profile/personal-stats/user-timeseries-chart.phtml',

            'ldc-user-profile/profile/extension/extprofile' => __DIR__ . '/../view/ldc-user-profile/profile/extension/extprofile.phtml',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'ursProfilePersonalStatsGraph' => 'UsaRugbyStats\AccountProfile\PersonalStats\ViewHelper\UserTimeseriesChartFactory',
            'ursPlayerAvatar' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ViewHelper\PlayerAvatarFactory',
            'ursPlayerPhotoUrl' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ViewHelper\PlayerPhotoUrlFactory',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'usarugbystats_accountprofile_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/doctrine/self'
            ),

            'orm_default' => array(
                'drivers' => array(
                    'UsaRugbyStats\AccountProfile'  => 'usarugbystats_accountprofile_entity'
                )
            )
        ),
        'fixture' => array(
            'UsaRugbyStats_AccountProfile_fixture' => __DIR__ . '/../src/Fixtures/Doctrine',
        ),
    ),

    'audit' => array(
        'entities' => array(
            'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionEntity' => [],
            'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionEntity' => [],
        ),
    ),
);
