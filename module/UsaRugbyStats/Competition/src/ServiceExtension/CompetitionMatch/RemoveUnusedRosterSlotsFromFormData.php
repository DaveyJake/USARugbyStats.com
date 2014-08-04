<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;

/**
 * Processing Rule to remove roster slots which have empty player selection
 */
class RemoveUnusedRosterSlotsFromFormData extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->data) ) {
            return false;
        }
        if ( ! isset($e->getParams()->data['match']['teams']['H']['players']) &&
             ! isset($e->getParams()->data['match']['teams']['A']['players']) ) {
             return false;
         }

        return true;
    }

    public function execute(EventInterface $e)
    {
        foreach ($e->getParams()->data['match']['teams'] as $team=>&$fsTeam) {
            if ( !isset($fsTeam['players']) || empty($fsTeam['players']) ) {
                continue;
            }
            foreach ($fsTeam['players'] as $pkey=>$pdata) {
                if ( empty($pdata['player']) ) {
                    unset($fsTeam['players'][$pkey]);
                }
            }
        }
    }
}
