<?php
return array(

    'usarugbystats' => array(
       'application' => array(
            'event_listeners' => array(
                'usarugbystats_competition_rbac_listener_teamadmincannotmodifyteamunion',
                'usarugbystats_competition_rbac_listener_unionupdateteams',
                'usarugbystats_competition_rbac_listener_competitionupdatedivisions',
                'usarugbystats_competition_rbac_listener_competitionmatchdetailschange',
                'usarugbystats_competition_rbac_listener_competitionmatchsignatureschange',
            ),
        ),
    ),

    'service_manager' => array(
        'aliases' => array(),
        'invokables' => array(
            'usarugbystats_competition_listener_lockcompetitionmatchwhencompleted' => 'UsaRugbyStats\Competition\Listeners\LockCompetitionMatchWhenCompletedListener',
            'usarugbystats_competition_listener_emptycompetitionmatchcollections' => 'UsaRugbyStats\Competition\Listeners\EmptyCompetitionMatchCollectionsListener',
            'usarugbystats_competition_listener_removeunusedrosterslotsfromcompetitionmatch' => 'UsaRugbyStats\Competition\Listeners\RemoveUnusedRosterSlotsFromCompetitionMatchListener',
            'usarugbystats_competition_listener_removeexistingsignaturesfromcompetitionmatch' => 'UsaRugbyStats\Competition\Listeners\RemoveExistingSignaturesFromCompetitionMatchListener',
            'usarugbystats_competition_listener_emptyunionteamcollection' => 'UsaRugbyStats\Competition\Listeners\EmptyUnionTeamCollectionListener',
        ),
        'factories' => array(
            'usarugbystats_competition_rbac_listener_teamadmincannotmodifyteamunion' => 'UsaRugbyStats\Competition\Rbac\Listener\TeamAdminCannotModifyTeamUnionFactory',
            'usarugbystats_competition_rbac_listener_unionupdateteams' => 'UsaRugbyStats\Competition\Rbac\Listener\UnionUpdateTeamsFactory',
            'usarugbystats_competition_rbac_listener_competitionupdatedivisions' => 'UsaRugbyStats\Competition\Rbac\Listener\CompetitionUpdateDivisionsFactory',
            'usarugbystats_competition_rbac_listener_competitionmatchdetailschange' => 'UsaRugbyStats\Competition\Rbac\Listener\CompetitionMatchDetailsChangeFactory',
            'usarugbystats_competition_rbac_listener_competitionmatchsignatureschange' => 'UsaRugbyStats\Competition\Rbac\Listener\CompetitionMatchSignaturesChangeFactory',

            'usarugbystats_competition_location_service' => 'UsaRugbyStats\Competition\Service\LocationServiceFactory',
            'usarugbystats_competition_location_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\LocationFieldsetFactory',
            'usarugbystats_competition_location_createform' => 'UsaRugbyStats\Competition\Form\LocationCreateFormFactory',
            'usarugbystats_competition_location_updateform' => 'UsaRugbyStats\Competition\Form\LocationUpdateFormFactory',
            'usarugbystats_competition_location_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\LocationFilterFactory',

            'usarugbystats_competition_team_service' => 'UsaRugbyStats\Competition\Service\TeamServiceFactory',
            'usarugbystats_competition_team_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\TeamFieldsetFactory',
            'usarugbystats_competition_team_createform' => 'UsaRugbyStats\Competition\Form\TeamCreateFormFactory',
            'usarugbystats_competition_team_updateform' => 'UsaRugbyStats\Competition\Form\TeamUpdateFormFactory',
            'usarugbystats_competition_team_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\TeamFilterFactory',

            'usarugbystats_competition_union_service' => 'UsaRugbyStats\Competition\Service\UnionServiceFactory',
            'usarugbystats_competition_union_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\UnionFieldsetFactory',
            'usarugbystats_competition_union_team_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Union\TeamFieldsetFactory',
            'usarugbystats_competition_union_createform' => 'UsaRugbyStats\Competition\Form\UnionCreateFormFactory',
            'usarugbystats_competition_union_updateform' => 'UsaRugbyStats\Competition\Form\UnionUpdateFormFactory',
            'usarugbystats_competition_union_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\UnionFilterFactory',

            'usarugbystats_competition_playerstatistics_service' => 'UsaRugbyStats\Competition\Service\PlayerStatisticsServiceFactory',

            'usarugbystats_competition_competition_service' => 'UsaRugbyStats\Competition\Service\CompetitionServiceFactory',
            'usarugbystats_competition_competition_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\CompetitionFieldsetFactory',
            'usarugbystats_competition_competition_createform' => 'UsaRugbyStats\Competition\Form\CompetitionCreateFormFactory',
            'usarugbystats_competition_competition_updateform' => 'UsaRugbyStats\Competition\Form\CompetitionUpdateFormFactory',
            'usarugbystats_competition_competition_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\CompetitionFilterFactory',

            'usarugbystats_competition_competition_division_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldsetFactory',
            'usarugbystats_competition_competition_division_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\DivisionFilterFactory',

            'usarugbystats_competition_competition_teammembership_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\TeamMembershipFieldsetFactory',
            'usarugbystats_competition_competition_teammembership_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\TeamMembershipFilterFactory',

            'usarugbystats_competition_competition_standings_service' => 'UsaRugbyStats\Competition\Service\Competition\StandingsServiceFactory',

            'usarugbystats_competition_competition_match_service' => 'UsaRugbyStats\Competition\Service\Competition\MatchServiceFactory',
            'usarugbystats_competition_competition_match_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\MatchFieldsetFactory',
            'usarugbystats_competition_competition_match_createform' => 'UsaRugbyStats\Competition\Form\Competition\MatchCreateFormFactory',
            'usarugbystats_competition_competition_match_updateform' => 'UsaRugbyStats\Competition\Form\Competition\MatchUpdateFormFactory',
            'usarugbystats_competition_competition_match_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\MatchFilterFactory',

            'usarugbystats_competition_competition_match_team_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldsetFactory',
            'usarugbystats_competition_competition_match_team_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamFilterFactory',
            'usarugbystats_competition_competition_match_teamplayer_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamPlayerFieldsetFactory',
            'usarugbystats_competition_competition_match_teamplayer_inputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamPlayerFilterFactory',
            'usarugbystats_competition_competition_match_teamplayer_collectionfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamPlayerCollectionFilterFactory',
            'usarugbystats_competition_competition_match_signature_fieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchSignatureFieldsetFactory',

            'usarugbystats_competition_competition_match_teamevent_cardfieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\CardEventFieldsetFactory',
            'usarugbystats_competition_competition_match_teamevent_cardinputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent\CardEventFilterFactory',
            'usarugbystats_competition_competition_match_teamevent_scorefieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\ScoreEventFieldsetFactory',
            'usarugbystats_competition_competition_match_teamevent_scoreinputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent\ScoreEventFilterFactory',
            'usarugbystats_competition_competition_match_teamevent_subfieldset' => 'UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\SubEventFieldsetFactory',
            'usarugbystats_competition_competition_match_teamevent_subinputfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEvent\SubEventFilterFactory',
            'usarugbystats_competition_competition_match_teamevent_collectionfilter' => 'UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamEventCollectionFilterFactory',
        ),
        'shared' => array(
            'usarugbystats_competition_location_fieldset' => false,
            'usarugbystats_competition_location_createform' => false,
            'usarugbystats_competition_location_updateform' => false,

            'usarugbystats_competition_team_fieldset' => false,
            'usarugbystats_competition_team_createform' => false,
            'usarugbystats_competition_team_updateform' => false,

            'usarugbystats_competition_union_fieldset' => false,
            'usarugbystats_competition_union_createform' => false,
            'usarugbystats_competition_union_updateform' => false,

            'usarugbystats_competition_competition_fieldset' => false,
            'usarugbystats_competition_competition_createform' => false,
            'usarugbystats_competition_competition_updateform' => false,

            'usarugbystats_competition_competition_match_fieldset' => false,
            'usarugbystats_competition_competition_match_createform' => false,
            'usarugbystats_competition_competition_match_updateform' => false,

            'usarugbystats_competition_competition_match_team_fieldset' => false,
            'usarugbystats_competition_competition_match_team_inputfilter' => false,

            'usarugbystats_competition_competition_match_teamplayer_fieldset' => false,
            'usarugbystats_competition_competition_match_teamplayer_inputfilter' => false,
            'usarugbystats_competition_competition_match_teamplayer_collectionfilter' => false,

            'usarugbystats_competition_competition_match_signature_fieldset' => false,

            'usarugbystats_competition_competition_match_teamevent_cardfieldset' => false,
            'usarugbystats_competition_competition_match_teamevent_cardinputfilter' => false,
            'usarugbystats_competition_competition_match_teamevent_scorefieldset' => false,
            'usarugbystats_competition_competition_match_teamevent_scoreinputfilter' => false,
            'usarugbystats_competition_competition_match_teamevent_subfieldset' => false,
            'usarugbystats_competition_competition_match_teamevent_subinputfilter' => false,
            'usarugbystats_competition_competition_match_teamevent_collectionfilter' => false,

            'usarugbystats_competition_competition_division_fieldset' => false,
            'usarugbystats_competition_competition_teammembership_fieldset' => false,
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
            'UsaRugbyStats_Competition_fixture_common' => __DIR__ . '/../src/Fixtures/Common',
        ),
    ),
    'audit' => array(
        'entities' => array(
            'UsaRugbyStats\Competition\Entity\Location' => [],

            'UsaRugbyStats\Competition\Entity\Team' => [],
            'UsaRugbyStats\Competition\Entity\Union' => [],

            'UsaRugbyStats\Competition\Entity\Competition' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Division' => [],
            'UsaRugbyStats\Competition\Entity\Competition\TeamMembership' => [],

            'UsaRugbyStats\Competition\Entity\Competition\Match' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent' => [],
            'UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature' => [],
        ),
    ),

    'zfc_rbac' => array(
        'assertion_map' => [
            'competition.team.update' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedTeamsAssertion',
            'competition.team.update.union' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedTeamsAssertion',
            'competition.team.delete' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedTeamsAssertion',

            'competition.union.update' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedUnionsAssertion',
            'competition.union.update.teams' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedUnionsAssertion',
            'competition.union.delete' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedUnionsAssertion',

            'competition.competition.update' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',
            'competition.competition.update.details' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',
            'competition.competition.update.matches' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',
            'competition.competition.update.divisions' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',
            'competition.competition.delete' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',

            'competition.competition.division.team.add' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',
            'competition.competition.division.team.remove' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',

            'competition.competition.match.create' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionsAssertion',
            'competition.competition.match.update' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchAssertion',
            'competition.competition.match.delete' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchAssertion',

            'competition.competition.match.details.change' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchAssertion',
            'competition.competition.match.signatures.change' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchAssertion',

            'competition.competition.match.team.change' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchTeamAssertion',
            'competition.competition.match.team.roster.change' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchTeamAssertion',
            'competition.competition.match.team.events.change' => 'UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedCompetitionMatchTeamAssertion',

        ]
    ),

    'view_helpers' => array(
        'factories' => array(
            'ursPlayerName'           => 'UsaRugbyStats\Competition\View\Helper\PlayerNameFactory',
            'ursPlayerLink'           => 'UsaRugbyStats\Competition\View\Helper\PlayerLinkFactory',
            'ursTeamName'             => 'UsaRugbyStats\Competition\View\Helper\TeamNameFactory',
            'ursTeamLink'             => 'UsaRugbyStats\Competition\View\Helper\TeamLinkFactory',
            'ursUnionName'            => 'UsaRugbyStats\Competition\View\Helper\UnionNameFactory',
            'ursUnionLink'            => 'UsaRugbyStats\Competition\View\Helper\UnionLinkFactory',
            'ursCompetitionName'      => 'UsaRugbyStats\Competition\View\Helper\CompetitionNameFactory',
            'ursCompetitionLink'      => 'UsaRugbyStats\Competition\View\Helper\CompetitionLinkFactory',
            'ursCompetitionMatchName' => 'UsaRugbyStats\Competition\View\Helper\CompetitionMatchNameFactory',
            'ursCompetitionMatchLink' => 'UsaRugbyStats\Competition\View\Helper\CompetitionMatchLinkFactory',
        ),
    ),
);
