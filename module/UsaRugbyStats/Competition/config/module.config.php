<?php
return array(
    'service_manager' => array(
        'aliases' => array(),
        'factories' => array(
            'usarugbystats_competition_team_service' => 'UsaRugbyStats\Competition\Service\TeamServiceFactory',
            'usarugbystats_competition_team_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\TeamFieldsetFactory',
            'usarugbystats_competition_team_createform' => 'UsaRugbyStats\Competition\Form\TeamCreateFormFactory',
            'usarugbystats_competition_team_updateform' => 'UsaRugbyStats\Competition\Form\TeamUpdateFormFactory',
            'usarugbystats_competition_team_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\TeamFilterFactory',
                        
            'usarugbystats_competition_union_service' => 'UsaRugbyStats\Competition\Service\UnionServiceFactory',
            'usarugbystats_competition_union_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\UnionFieldsetFactory',
            'usarugbystats_competition_union_createform' => 'UsaRugbyStats\Competition\Form\UnionCreateFormFactory',
            'usarugbystats_competition_union_updateform' => 'UsaRugbyStats\Competition\Form\UnionUpdateFormFactory',
            'usarugbystats_competition_union_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\UnionFilterFactory',
        ),
        'shared' => array(
            'usarugbystats_competition_team_fieldset' => false,
            'usarugbystats_competition_team_createform' => false,
            'usarugbystats_competition_team_updateform' => false,
            
        	'usarugbystats_competition_union_fieldset' => false,
            'usarugbystats_competition_union_createform' => false,
            'usarugbystats_competition_union_updateform' => false,
        ),
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
//            'UsaRugbyStats_Competition_fixture_common' => __DIR__ . '/../src/Fixtures/Common',
        ),
    ),
);
