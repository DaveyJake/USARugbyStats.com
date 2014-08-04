<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch;

use Zend\EventManager\EventInterface;

class DropEventsIfMatchIsNotStarted extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }
        if ( ! $e->getParams()->entity->isNotStarted() ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $side = $e->getParams()->entity->getTeam('H');
        $side->removeEvents($side->getEvents());

        $side = $e->getParams()->entity->getTeam('A');
        $side->removeEvents($side->getEvents());
    }
}
