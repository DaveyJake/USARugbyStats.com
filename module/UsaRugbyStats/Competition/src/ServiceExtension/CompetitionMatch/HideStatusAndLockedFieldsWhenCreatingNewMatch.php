<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
/**
 * Processing Rule to hide the status and isLocked fields when creating a new match
 *
 */
class HideStatusAndLockedFieldsWhenCreatingNewMatch extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->entity) || $e->getParams()->entity->getId() !== NULL ) {
            return false;
        }
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $e->getParams()->flags->{'match.status'}->off();
        $e->getParams()->flags->{'match.status%visible'}->off();
        $e->getParams()->flags->{'match.isLocked'}->off();
        $e->getParams()->flags->{'match.isLocked%visible'}->off();
    }
}
