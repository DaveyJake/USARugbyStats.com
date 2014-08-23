<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;

class RecalculateScore extends AbstractRule
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
        $e->getParams()->entity->recalculateScore();
    }
}
