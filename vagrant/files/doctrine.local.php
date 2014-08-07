<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'localhost',
                    'port' => '3306',
                    'user' => 'usarugbystats',
                    'password' => 'rugbyrugbyrugby',
                    'dbname' => 'usarugbystats'
                )
            )
        )
    ),
    'service_manager' => array(
        'aliases' => array(
            'zfcuser_doctrine_em' => 'Doctrine\ORM\EntityManager'
        )
    )
);
