<?php
return array(
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
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            'usarugbystats_competition_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                'paths' => __DIR__ . '/doctrine/self'
            ),
            'orm_default' => array(
                'drivers' => array(
                    'UsaRugbyStats\Competition\Entity'  => 'usarugbystats_competition_entity'
                )
            )
        ),
        'fixture' => array(
            'UsaRugbyStats_Competition_fixture_common' => __DIR__ . '/../src/Fixtures/Common',
        ),
    ),
);
