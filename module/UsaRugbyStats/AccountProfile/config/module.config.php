<?php
return array(
    'ldc-user-profile' => array(
        'registered_extensions' => array(
            'usarugbystats-accountprofile_extprofile_extension' => true,
            'usarugbystats-accountprofile_personalstats_extension' => true,
        ),
    ),

    'zfc_rbac' => [
        'assertion_map' => [
            'account.profile' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedPlayerAssertion',

            'account.profile.zfcuser.username' => 'UsaRugbyStats\AccountProfile\ZfcUser\ExtensionRbacAssertion',
            'account.profile.zfcuser.email' => 'UsaRugbyStats\AccountProfile\ZfcUser\ExtensionRbacAssertion',
            'account.profile.zfcuser.display_name' => 'UsaRugbyStats\AccountProfile\ZfcUser\ExtensionRbacAssertion',
            'account.profile.zfcuser.password' => 'UsaRugbyStats\AccountProfile\ZfcUser\ExtensionRbacAssertion',
            'account.profile.zfcuser.passwordVerify' => 'UsaRugbyStats\AccountProfile\ZfcUser\ExtensionRbacAssertion',

            'account.profile.extprofile.firstName' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionRbacAssertion',
            'account.profile.extprofile.lastName' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionRbacAssertion',
            'account.profile.extprofile.telephoneNumber' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionRbacAssertion',
            'account.profile.extprofile.photoSource' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionRbacAssertion',
            'account.profile.extprofile.custom_photo' => 'UsaRugbyStats\AccountProfile\ExtendedProfile\ExtensionRbacAssertion',

            'account.profile.personalstats.height_ft' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionRbacAssertion',
            'account.profile.personalstats.height_in' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionRbacAssertion',
            'account.profile.personalstats.weight_lbs' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionRbacAssertion',
            'account.profile.personalstats.weight_oz' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionRbacAssertion',
            'account.profile.personalstats.benchPress' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionRbacAssertion',
            'account.profile.personalstats.sprintTime' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionRbacAssertion',
        ],
    ],

    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'ldc-user-profile_extension_zfcuser_inputfilter' => 'UsaRugbyStats\AccountProfile\ZfcUser\ZfcUserInputFilterFactory',

            'usarugbystats-accountprofile_helper_profilerbachelper' => 'UsaRugbyStats\AccountProfile\Helper\PlayerProfileRbacHelperFactory',

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
            'ursPlayerProfile' => 'UsaRugbyStats\AccountProfile\ViewHelper\PlayerProfileHelperFactory',
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
