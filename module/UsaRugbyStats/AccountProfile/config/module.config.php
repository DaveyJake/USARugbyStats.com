<?php
return array(
    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'usarugbystats-accountprofile_personalstats_extension' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionFactory',
            'usarugbystats-accountprofile_personalstats_extension_service' => 'UsaRugbyStats\AccountProfile\PersonalStats\ExtensionServiceFactory',
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'ldc-user-profile/profile/extension/personalstats' => __DIR__ . '/../view/ldc-user-profile/profile/extension/personalstats.phtml'
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
    ),
);
