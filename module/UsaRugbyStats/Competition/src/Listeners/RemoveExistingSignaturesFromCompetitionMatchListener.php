<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;

class RemoveExistingSignaturesFromCompetitionMatchListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('update.save', array($this, 'run'), 70); // pre
    }

    public function run(EventInterface $e)
    {
        $data = $e->getParam('data');

        if ( !isset($data['match']['signatures']) || empty($data['match']['signatures']) ) {
            return;
        }
        foreach ($data['match']['signatures'] as $key => $signature) {
            if ( isset($signature['id']) && !empty($signature['id']) ) {
                unset($data['match']['signatures'][$key]);
            }
        }

        $e->setParam('data', $data);
    }
}
