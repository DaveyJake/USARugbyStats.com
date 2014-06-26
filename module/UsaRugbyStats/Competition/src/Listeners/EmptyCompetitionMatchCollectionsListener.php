<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;

class EmptyCompetitionMatchCollectionsListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('form.bind', array($this, 'run'), 10); // pre
    }

    public function run(EventInterface $e)
    {
        $entity = $e->getParams()->entity;
        $data = $e->getParams()->data;

        // @HACK to fix GH-15 (Can't empty an existing Collection)
        if ( !isset($data['match']['teams']['H']['players']) || empty($data['match']['teams']['H']['players']) ) {
            $entity->getHomeTeam()->removePlayers($entity->getHomeTeam()->getPlayers());
        }
        if ( !isset($data['match']['teams']['H']['events']) || empty($data['match']['teams']['H']['events']) ) {
            $entity->getHomeTeam()->removeEvents($entity->getHomeTeam()->getEvents());
        }
        if ( !isset($data['match']['teams']['A']['players']) || empty($data['match']['teams']['A']['players']) ) {
            $entity->getAwayTeam()->removePlayers($entity->getAwayTeam()->getPlayers());
        }
        if ( !isset($data['match']['teams']['A']['events']) || empty($data['match']['teams']['A']['events']) ) {
            $entity->getAwayTeam()->removeEvents($entity->getAwayTeam()->getEvents());
        }
        if ( !isset($data['match']['signatures']) || empty($data['match']['signatures']) ) {
            $entity->removeSignatures($entity->getSignatures());
        }
    }
}
