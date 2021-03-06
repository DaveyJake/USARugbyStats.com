<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use UsaRugbyStats\Application\Service\AbstractService;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Application\Entity\AccountInterface;

class MatchService extends AbstractService
{

    /**
     * List of available MatchTeamEvent types
     *
     * @var array
     */
    protected $availableMatchTeamEventTypes;

    public function findAllForTeam($teams)
    {
        return $this->getRepository()->findAllForTeam($teams);
    }

    public function findAllForCompetition($comps)
    {
        return $this->getRepository()->findAllForCompetition($comps);
    }

    public function findAllForUnion($unions)
    {
        return $this->getRepository()->findAllForUnion($unions);
    }

    public function findAllForPlayer($players)
    {
        return $this->getRepository()->findAllForPlayer($players);
    }

    public function getLastMatchRosterForTeam(Team $t, $referencePoint = null)
    {
        // Load the most recent match involving this team
        $matches = $this->getRepository()->findAllForTeam($t)->toArray();
        if ( count($matches) === 0 ) {
            return NULL;
        }

        $date = null;
        if ($referencePoint instanceof \DateTime) {
            $date = $referencePoint;
        } elseif ($referencePoint instanceof Match) {
            $date = $referencePoint->getDate();
        }

        // Keep popping matches off the reverse-chronological list until
        // we reach the match before our reference point
        // (if no reference is provided we bail at the first one)
        while ( !empty($matches) ) {
            $match = array_pop($matches);
            if (! $match instanceof Match) {
                return NULL;
            }
            if ( is_null($date) ) {
                break;
            }
            if ( $match->getDate() < $date ) {
                break;
            }
        }
        if (! $match instanceof Match) {
            return NULL;
        }

        // Determine which side they were on and load the roster for that side
        foreach ( $match->getTeams() as $matchTeam ) {
            if (! $matchTeam instanceof MatchTeam) {
                continue;
            }
            if ( ! $matchTeam->getTeam() instanceof Team || $matchTeam->getTeam()->getId() != $t->getId() ) {
                continue;
            }

            // Run through the roster and pull out the populated slots
            $players = array();
            foreach ( $matchTeam->getPlayers() as $player ) {
                if (! $player instanceof MatchTeamPlayer) {
                    continue;
                }
                if ( ! $player->getPlayer() instanceof AccountInterface ) {
                    continue;
                }
                $players[$player->getPosition()] = $player->getPlayer()->getId();
            }

            return [ 'match' => $match, 'roster' => $players ];
        }

        return NULL;
    }

    public function attachDefaultListeners()
    {
        $this->getEventManager()->attach('save', function (EventInterface $e) {
            $e->getParams()->entity->recalculateScore();
        }, -99999);
    }

    public function setAvailableMatchTeamEventTypes($set)
    {
        $this->availableMatchTeamEventTypes = $set;

        return $this;
    }

    public function getAvailableMatchTeamEventTypes()
    {
        return $this->availableMatchTeamEventTypes;
    }

}
