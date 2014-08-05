<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;

class DropEventsIfMatchIsNotStarted extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! $e->getParams()->entity->isNotStarted() || ! ( isset($e->getParams()->data) && $e->getParams()->data['match']['status'] == 'NS' ) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $side = $e->getParams()->entity->getTeam('H');
        $side->removeEvents($side->getEvents());
        if ( isset($e->getParams()->data['match']['teams']['H']['events']) ) {
            $e->getParams()->data['match']['teams']['H']['events'] = array();
        }

        $side = $e->getParams()->entity->getTeam('A');
        $side->removeEvents($side->getEvents());
        if ( isset($e->getParams()->data['match']['teams']['A']['events']) ) {
            $e->getParams()->data['match']['teams']['A']['events'] = array();
        }
    }
}
