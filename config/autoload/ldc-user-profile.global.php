<?php
/**
 * LdcUserProfile Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(

    /**
     * URL path at which to mount the profile controller
     */
    'url_path' => '/account/profile',

    /**
     * Override of default validation groups to switch editing on/off for specific fields
     */
    'validation_group_overrides' => array(
        'zfcuser' => array(
            'email',
            'password',
            'passwordVerify',
        ),
        'extprofile' => array(
            'firstName',
            'lastName',
            'telephoneNumber',
        ),
        'personalstats' => array(

        ),
    ),

    /**
     * Override of default validation groups to switch editing on/off for specific fields
     * when the user account in question has a Remote ID
     */
    'validation_group_overrides_remoteid' => array(
        'zfcuser' => array(
            'password',
            'passwordVerify',
        ),
        'personalstats' => array(),
    ),
);

/**
 * You do not need to edit below this line
 */
return array(
    'ldc-user-profile' => $settings,

    'router' => array(
        'routes' => array(
            'ldc-user-profile' => array(
                'options' => array(
                    'route'    => $settings['url_path'],
                ),
            ),
        ),
    ),

    'zfc_rbac' => [
        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                'ldc-user-profile' => [ 'member' ],
            ],
        ],
    ],
);
