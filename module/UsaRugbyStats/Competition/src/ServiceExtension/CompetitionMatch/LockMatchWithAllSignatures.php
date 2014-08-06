<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
/**
 * If the Match is finished and has new signatures which complete the set
 * lock the match so that no further changes can be made
 */
class LockMatchWithAllSignatures extends AbstractRule
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
        $isFinished = $e->getParams()->entity->isFinished()
            && $e->getParams()->entity->hasSignature('HC')
            && $e->getParams()->entity->hasSignature('AC')
            && $e->getParams()->entity->hasSignature('REF')
            && $e->getParams()->entity->hasSignature('NR4');

        $hasNewSignature = false;
        $signatures = [];
        foreach ( $e->getParams()->entity->getSignatures() as $sig ) {
            array_push($signatures, $sig->getType());
            if ( ! $sig->getId() ) {
                $hasNewSignature = true;
            }
        }
        $signatures = array_unique($signatures);

        if ( $hasNewSignature && count($signatures) == 4 ) {
            $e->getParams()->entity->setIsLocked(true);
        }
    }
}
