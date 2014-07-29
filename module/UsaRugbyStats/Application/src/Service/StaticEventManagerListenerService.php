<?php
namespace UsaRugbyStats\Application\Service;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\SharedEventManager;

class StaticEventManagerListenerService
{

    public static function registerListeners(ServiceLocatorInterface $sm)
    {
        $listeners = $sm->get('Config')['usarugbystats']['application']['event_listeners'];

        $eventManager = $sm->get('SharedEventManager');
        $eventManager instanceof SharedEventManager;

        foreach ($listeners as $listener) {
            $eventManager->attachAggregate($sm->get($listener));
        }
    }

}
