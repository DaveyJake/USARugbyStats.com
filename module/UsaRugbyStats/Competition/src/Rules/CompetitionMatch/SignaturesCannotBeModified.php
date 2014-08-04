<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature;
use Zend\EventManager\EventInterface;

/**
 * Processing Rule to disable editing of a match when it's locked
 * (while still allowing changing the isLocked field)
 *
 */
class SignaturesCannotBeModified extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->entity) ) {
            return false;
        }
        if ( ! $e->getParams()->entity->isComplete() ) {
            return false;
        }
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        foreach ( $e->getParams()->entity->getSignatures() as $key=>$sig ) {
            if (! $sig instanceof MatchSignature) {
                continue;
            }

            // Signatures not persisted can be changed
            $sigId = $sig->getId();
            if ( empty($sigId) ) {
                continue;
            }

            $e->getParams()->flags->{"match.signatures.$key"}->off();
        };
    }
}
