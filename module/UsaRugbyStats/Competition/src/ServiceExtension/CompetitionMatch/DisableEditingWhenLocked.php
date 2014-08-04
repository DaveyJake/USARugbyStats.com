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
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $isFinished = $e->getParams()->entity->isFinished()
            && $e->getParams()->entity->hasSignature('HC')
            && $e->getParams()->entity->hasSignature('AC')
            && $e->getParams()->entity->hasSignature('REF')
            && $e->getParams()->entity->hasSignature('NR4');

        if ($isFinished) {
            $e->getParams()->entity->setIsLocked(true);
        }

        if ( $e->getParams()->entity->isLocked() ) {
            $this->disableDetailsChange($e->getParams());
            $this->disableTeamChange($e->getParams());
            $this->disableTeamEventsChange($e->getParams());
            $this->disableTeamRosterChange($e->getParams());
            $this->disableSignatureChange($e->getParams());
        }
    }
}
