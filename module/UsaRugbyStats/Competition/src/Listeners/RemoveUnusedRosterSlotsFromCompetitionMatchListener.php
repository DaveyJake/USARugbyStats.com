<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;

class RemoveUnusedRosterSlotsFromCompetitionMatchListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('form.bind', array($this, 'run'), 80); // pre
    }

    public function run(EventInterface $e)
    {
        $data = $e->getParams()->data;

        if ( !isset($data['match']['teams']) || empty($data['match']['teams']) ) {
            return;
        }
        foreach ($data['match']['teams'] as $team=>&$fsTeam) {
            if ( !isset($fsTeam['players']) || empty($fsTeam['players']) ) {
                continue;
            }
            foreach ($fsTeam['players'] as $pkey=>$pdata) {
                if ( empty($pdata['player']) ) {
                    unset($fsTeam['players'][$pkey]);
                }
            }
        }

        $e->getParams()->data = $data;
    }
}
