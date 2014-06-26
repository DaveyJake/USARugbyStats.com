<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class RemoveExistingSignaturesFromCompetitionMatchListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('save', array($this, 'run'), 70); // pre
    }

    public function run(EventInterface $e)
    {
        $entity = $e->getParam('entity');

        if (! $entity instanceof Match) {
            return;
        }
        if ( is_null($entity->getId()) ) {
            return;
        }

        foreach ( $entity->getSignatures() as $sig ) {
            if ( is_null($sig->getId()) ) {
                continue;
            }
            $entity->removeSignature($sig);
        }
    }
}
