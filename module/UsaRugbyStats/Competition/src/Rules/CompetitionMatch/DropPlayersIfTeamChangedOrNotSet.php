<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;

class DropPlayersIfTeamChangedOrNotSet extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $side = $e->getParams()->entity->getTeam('H');
        if ( $side instanceof MatchTeam && ! $side->getTeam() instanceof Team ) {

            $side->removeEvents($side->getEvents());
            if ( isset($e->getParams()->data['match']['teams']['H']['events']) ) {
                $e->getParams()->data['match']['teams']['H']['events'] = array();
            }

            $side->removePlayers($side->getPlayers());
            if ( isset($e->getParams()->data['match']['teams']['H']['players']) ) {
                $e->getParams()->data['match']['teams']['H']['players'] = array();
            }
        }

        $side = $e->getParams()->entity->getTeam('A');
        if ( $side instanceof MatchTeam && ! $side->getTeam() instanceof Team ) {

            $side->removeEvents($side->getEvents());
            if ( isset($e->getParams()->data['match']['teams']['A']['events']) ) {
                $e->getParams()->data['match']['teams']['A']['events'] = array();
            }

            $side->removePlayers($side->getPlayers());
            if ( isset($e->getParams()->data['match']['teams']['A']['players']) ) {
                $e->getParams()->data['match']['teams']['A']['players'] = array();
            }
        }
    }
}
