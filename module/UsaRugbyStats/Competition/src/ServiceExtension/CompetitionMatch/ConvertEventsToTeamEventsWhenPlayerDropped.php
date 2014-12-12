<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;

/**
 * If a player is removed from a side
 *  - all score and card events associated with that player must be converted to team events
 *  - sub events are deleted
 */
class ConvertEventsToTeamEventsWhenPlayerDropped extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->entity) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $entity = &$e->getParams()->entity;
        if (! $entity instanceof Match) {
            return;
        }

        foreach ( $entity->getTeams() as $side ) {
            if (! $side instanceof MatchTeam) {
                continue;
            }

            foreach ( $side->getEvents() as $event ) {
                if (! $event instanceof MatchTeamEvent) {
                    continue;
                }
                switch ($event->getDiscriminator()) {
                    case 'score':
                    case 'card':
                        if ( ! $side->hasPlayer($event->getPlayer()) ) {
                            $event->setPlayer(null);
                        }
                        break;
                    case 'sub':
                        if ( ! $side->hasPlayer($event->getPlayerOn()) || ! $side->hasPlayer($event->getPlayerOff()) ) {
                            $side->removeEvent($event);
                        }
                        break;
                }
            }
        }
    }
}
