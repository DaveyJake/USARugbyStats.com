<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature;
use Zend\EventManager\EventInterface;

/**
 * Drop existing signatures when a match is modified
 * @TODO this needs to be a bit more intelligent
 */
class DropSignaturesWhenMatchModified extends AbstractRule
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
        foreach ( $e->getParams()->entity->getSignatures() as $key=>$sig ) {
            if (! $sig instanceof MatchSignature) {
                continue;
            }

            // Signatures not persisted can be changed
            $sigId = $sig->getId();
            if ( empty($sigId) ) {
                continue;
            }

            $e->getParams()->entity->removeSignature($sig);
        };
    }
}
