<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;

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
        $side = $e->getParams()->entity->getTeam('H');
        if ( $side instanceof MatchTeam && ! $side->getTeam() instanceof Team ) {
            $side->removeEvents($side->getEvents());
            $side->removePlayers($side->getPlayers());
        }

        $side = $e->getParams()->entity->getTeam('A');
        if ( $side instanceof MatchTeam && ! $side->getTeam() instanceof Team ) {
            $side->removeEvents($side->getEvents());
            $side->removePlayers($side->getPlayers());
        }
    }
}