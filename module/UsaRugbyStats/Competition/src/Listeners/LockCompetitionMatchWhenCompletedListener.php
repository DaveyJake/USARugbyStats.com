<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;

class LockCompetitionMatchWhenCompletedListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('form.save', array($this, 'run'), 100); // pre
    }

    public function run(EventInterface $e)
    {
        $entity = $e->getParam('entity');

        $isFinished = $entity->getStatus() == 'F'
            && $entity->hasSignature('HC')
            && $entity->hasSignature('AC')
            && $entity->hasSignature('REF')
            && $entity->hasSignature('NR4');

        if ($isFinished) {
            $entity->setIsLocked(true);
        }
    }
}
