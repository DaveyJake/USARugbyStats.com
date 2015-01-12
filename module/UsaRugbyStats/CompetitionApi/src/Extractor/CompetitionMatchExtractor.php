<?php
namespace UsaRugbyStats\CompetitionApi\Extractor;

use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Location;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Application\Common\ExtendedValidationGroupForm;
use Zend\Stdlib\Extractor\ExtractionInterface;

class CompetitionMatchExtractor
{
    public static function extract(Match $m, ExtractionInterface $extractor)
    {
        $m->recalculateScore();

        $output = [
            'match' => [
                'id' => $m->getId(),
                'competition' => $m->getCompetition() ? $m->getCompetition()->getId() : null,
                'date_date' => $m->getDate()->format('Y-m-d'),
                'timezone' => $m->getTimezone(),
                'location' => $m->getLocation() ? $m->getLocation()->getId() : null,
                'locationDetails' => $m->getLocationDetails(),
                'status' => $m->getStatus(),
                'isLocked' => $m->isLocked() ? '1' : '0',
                'teams' => [
                    'H' => [],
                    'A' => [],
                ]
            ]
        ];
        
        $output['_embedded'] = array();

        // Expand Competition entity
        if ( $m->getCompetition() instanceof Competition ) {
            $output['match']['competition'] = $m->getCompetition()->getId();
            $output['_embedded']['competition'] = [
                'id' => $m->getCompetition()->getId(),
                'name' => $m->getCompetition()->getName(),
                'type' => $m->getCompetition()->getType(),
                'typeName' => $m->getCompetition()->getTypeString(),
                'variant' => $m->getCompetition()->getVariant(),
                'maxPlayersOnRoster' => $m->getCompetition()->getMaxPlayersOnRoster(),
            ];
        } else {
            $output['match']['competition'] = null;
        }

        // Expand location entity
        if ( $m->getLocation() instanceof Location ) {
            $output['match']['location'] = $m->getLocation()->getId();
            $output['_embedded']['location'] = [
                'id' => $m->getLocation()->getId(),
                'name' => $m->getLocation()->getName(),
                'coordinates' => $m->getLocation()->getCoordinates(),
                'address' => $m->getLocation()->getAddress(),
            ];
        } else {
            $output['match']['location'] = null;
        }
        
        // Expand team entities
        $output['match']['teams'] = ['H' => [], 'A' => []];
        foreach ( $m->getTeams() as $type => $team ) {
            if ( ! $team instanceof MatchTeam || ! $team->getTeam() instanceof Team ) {
                continue;
            }
            
            $output['match']['teams'][$type] = $extractor->extract($team);
            $output['match']['teams'][$type]['match'] = $team->getMatch() ? $team->getMatch()->getId() : null;
            $output['match']['teams'][$type]['team'] = $team->getTeam() ? $team->getTeam()->getId() : null;
            $output['_embedded']['team'][$team->getTeam()->getId()] = [
                'id' => $team->getTeam()->getId(),
                'name' => $team->getTeam()->getName(),
                'abbreviation' => $team->getTeam()->getAbbreviation(),
            ];
            
            if ( !empty($team->getEvents()) ) {
                $output['match']['teams'][$team->getType()]['events'] = [];
                foreach ($team->getEvents() as $k => $event ) {
                    $output['match']['teams'][$team->getType()]['events'][$k] = $extractor->extract($event);
                    $output['match']['teams'][$team->getType()]['events'][$k]['match'] = $event->getMatch() ? $event->getMatch()->getId() : null;
                    $output['match']['teams'][$team->getType()]['events'][$k]['team'] = $event->getTeam() ? $event->getTeam()->getId() : null;
                    if (isset($output['match']['teams'][$team->getType()]['events'][$k]['player'])) {
                        $output['match']['teams'][$team->getType()]['events'][$k]['player'] = $event->getPlayer() ? $event->getPlayer()->getId() : null;
                    }
                    if (isset($output['match']['teams'][$team->getType()]['events'][$k]['playerOn'])) {
                        $output['match']['teams'][$team->getType()]['events'][$k]['playerOn'] = $event->getPlayerOn() ? $event->getPlayerOn()->getId() : null;
                        $output['match']['teams'][$team->getType()]['events'][$k]['playerOff'] = $event->getPlayerOff() ? $event->getPlayerOff()->getId() : null;
                    }
                    $output['match']['teams'][$team->getType()]['events'][$k]['runningScore'] = $event->getRunningScore();
                }
            }
            
            if ( !empty($team->getPlayers()) ) {
                $output['match']['teams'][$team->getType()]['players'] = [];
                foreach ( $team->getPlayers() as $k => $p ) {
                    if ( ! $p instanceof MatchTeamPlayer || ! $p->getPlayer() instanceof AccountInterface ) {
                        continue;
                    }
                    $output['match']['teams'][$team->getType()]['players'][$k] = $extractor->extract($p);
                    $output['match']['teams'][$team->getType()]['players'][$k]['team'] = $p->getTeam() ? $p->getTeam()->getId() : null;
                    $output['match']['teams'][$team->getType()]['players'][$k]['player'] = $p->getPlayer() ? $p->getPlayer()->getId() : null;
                    
                    $output['_embedded']['teamPlayer'][$p->getId()] = [
                        'id' => $p->getId(),
                        'number' => $p->getNumber(),
                        'position' => $p->getPosition(),
                        'isFrontRow' => $p->getIsFrontRow(),
                        'acct' => $p->getPlayer()->getId(),
                    ];
                    $output['_embedded']['player'][$p->getPlayer()->getId()] = [
                        'id' => $p->getPlayer()->getId(),
                        'name' => $p->getPlayer()->getDisplayName(),
                    ];
                }
            }
        }

        return $output;
    }
}
