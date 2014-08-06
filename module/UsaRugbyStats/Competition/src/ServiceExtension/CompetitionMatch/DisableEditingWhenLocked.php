<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
/**
 * Processing Rule to disable editing of a match when it's locked
 * (while still allowing changing the isLocked field)
 *
 */
class DisableEditingWhenLocked extends AbstractRule
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
        if ( $e->getParams()->entity->isLocked() ) {
            $this->disableDetailsChange($e->getParams());
            $this->disableTeamChange($e->getParams());
            $this->disableTeamEventsChange($e->getParams());
            $this->disableTeamRosterChange($e->getParams());
            $this->disableSignatureChange($e->getParams());
            $e->getParams()->flags->{'match.signatures.allowed_types'} = [];
        }
    }
}
