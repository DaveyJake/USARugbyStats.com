<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;

class EmptyUnionTeamCollectionListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('form.bind', array($this, 'run'), 10); // pre
    }

    public function run(EventInterface $e)
    {
        $entity = $e->getParams()->entity;
        $data = $e->getParams()->data;

        // @HACK to fix GH-15 (Can't empty an existing Collection)
        if ( !isset($data['union']['teams']) || empty($data['union']['teams']) ) {
            $entity->removeTeams($entity->getTeams());
        }
    }
}
