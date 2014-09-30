<?php

return [
    'htsession' => [
        'options' => [
            'enable_session_set_save_handler' => true,
            'validators' => [
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ],
            'config_options' => [
                'name' => 'USARUGBYCMS2014',
                'remember_me_seconds' => (3600*24),
                'cache_expire' => (60*24),
                'use_cookies' => true,
                'cookie_domain' => '.usarugbystats.com',
                'cookie_secure' => false,
                'cookie_httponly' => false,
                'cookie_lifetime' => (3600*24),
            ],
        ],
    ],
    'service_manager' => [
        'aliases' => [
            'HtSession\SessionSetSaveHandler' => 'HtSession\DoctrineDbalSessionSetSaveHandler',
            'HtSession\AuthenticationService' => 'zfcuser_auth_service',
        ]
    ]
];
