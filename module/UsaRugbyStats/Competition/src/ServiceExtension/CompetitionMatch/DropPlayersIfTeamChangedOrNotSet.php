<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Team\Member;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;

class DropPlayersIfTeamChangedOrNotSet extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( $e->getParams()->entity === null ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        foreach (['H', 'A'] as $sideKey) {
            $side = $e->getParams()->entity->getTeam($sideKey);
            if (! $side instanceof MatchTeam) {
                continue;
            }
            if ( ! $side->getTeam() instanceof Team ) {
                $side->removeEvents($side->getEvents());
                $side->removePlayers($side->getPlayers());
                continue;
            }

            // Remove any players who are not on the team
            foreach ( $side->getPlayers() as $playerRecord ) {
                $member = $side->getTeam()->getMemberById($playerRecord->getPlayer()->getId());
                if (! $member instanceof Member) {
                    $playerId = $playerRecord->getPlayer()->getId();
                    $playerEvents = $side->getEvents()->filter(function ($e) use ($playerId) {
                        if ($e instanceof MatchTeamEvent\CardEvent) {
                            return $e->getPlayer()->getId() === $playerId;
                        }
                        if ($e instanceof MatchTeamEvent\ScoreEvent) {
                            return $e->getPlayer()->getId() === $playerId;
                        }
                        if ($e instanceof MatchTeamEvent\SubEvent) {
                            return $e->getPlayerOn()->getId() === $playerId
                               ||  $e->getPlayerOff()->getId() === $playerId;
                        }

                        return false;
                    });
                    $side->removeEvents($playerEvents);
                    $side->removePlayer($playerRecord);
                }
            }
        }
    }
}
