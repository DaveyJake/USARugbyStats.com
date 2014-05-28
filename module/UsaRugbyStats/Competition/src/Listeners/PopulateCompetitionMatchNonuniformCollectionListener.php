<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;

class PopulateCompetitionMatchNonuniformCollectionListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('create.bind', array($this, 'run'), 100); // pre
        $this->listeners[] = $events->attach('update.bind', array($this, 'run'), 100); // pre
    }

    public function run(EventInterface $e)
    {
        $entity = $e->getParam('entity');
        $data = $e->getParam('data');

        $types = $e->getTarget()->getAvailableMatchTeamEventTypes();
        foreach (['H', 'A'] as $type) {
            if ( ! isset($data['match']['teams'][$type]['events']) || count($data['match']['teams'][$type]['events']) == 0 ) {
                continue;
            }

            // Inject the entity class name into the POST request data
            // so that NonuniformCollection knows what entity to create
            foreach ($data['match']['teams'][$type]['events'] as $k=>$v) {
                $key = strtolower($v['event']);
                if ( ! isset($types[$key]) ) {
                    unset($data['match']['teams'][$type]['events'][$k]);
                    continue;
                }
                $data['match']['teams'][$type]['events'][$k]['__class__'] = $types[$key]['entity_class'];
            }
        }

        $e->setParam('data', $data);
    }
}
