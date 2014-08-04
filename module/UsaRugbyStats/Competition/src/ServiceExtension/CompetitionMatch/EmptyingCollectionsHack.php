<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;

/**
 * Processing Rule to remove roster slots which have empty player selection
 */
class EmptyingCollectionsHack extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->data) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {

        // @HACK to fix GH-15 (Can't empty an existing Collection)

        if ( $e->getParams()->flags->{'match.teams.H.players'}->is_on() ) {
            if ( !isset($e->getParams()->data['match']['teams']['H']['players']) || empty($e->getParams()->data['match']['teams']['H']['players']) ) {
                $e->getParams()->data['match']['teams']['H']['players'] = array();
                $e->getParams()->entity->getHomeTeam()->removePlayers($e->getParams()->entity->getHomeTeam()->getPlayers());
            }
        }
        if ( $e->getParams()->flags->{'match.teams.H.events'}->is_on() ) {
            if ( !isset($e->getParams()->data['match']['teams']['H']['events']) || empty($e->getParams()->data['match']['teams']['H']['events']) ) {
                $e->getParams()->data['match']['teams']['H']['events'] = array();
                $e->getParams()->entity->getHomeTeam()->removeEvents($e->getParams()->entity->getHomeTeam()->getEvents());
            }
        }
        if ( $e->getParams()->flags->{'match.teams.A.players'}->is_on() ) {
            if ( !isset($e->getParams()->data['match']['teams']['A']['players']) || empty($e->getParams()->data['match']['teams']['A']['players']) ) {
                $e->getParams()->data['match']['teams']['A']['players'] = array();
                $e->getParams()->entity->getAwayTeam()->removePlayers($e->getParams()->entity->getAwayTeam()->getPlayers());
            }
        }
        if ( $e->getParams()->flags->{'match.teams.A.events'}->is_on() ) {
            if ( !isset($e->getParams()->data['match']['teams']['A']['events']) || empty($e->getParams()->data['match']['teams']['A']['events']) ) {
                $e->getParams()->data['match']['teams']['A']['events'] = array();
                $e->getParams()->entity->getAwayTeam()->removeEvents($e->getParams()->entity->getAwayTeam()->getEvents());
            }
        }
        if ( $e->getParams()->flags->{'match.signatures'}->is_on() ) {
            if ( !isset($e->getParams()->data['match']['signatures']) || empty($e->getParams()->data['match']['signatures']) ) {
                $e->getParams()->data['match']['signatures'] = array();
                $e->getParams()->entity->removeSignatures($e->getParams()->entity->getSignatures());
            }
        }

    }
}
