<?php
namespace UsaRugbyStats\Application\Service;

use Zend\EventManager\EventInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ConfigInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;

class ServiceExtensionManager extends ServiceManager
{
    /**
     * @var array
     */
    protected $eventMap = array();

    /**
     * @var array
     */
    protected $attachedListeners = array();

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        if (is_null($config)) {
            $config = new ServiceExtensionManagerConfig();
        }

        parent::__construct($config);
    }

    public function attachEventListeners(EventManagerInterface $em)
    {
        $mgr = &$this;

        foreach ($this->eventMap as $event => $listeners) {
            if ( ! is_array($listeners) ) {
                $listeners = array($listeners);
            }
            foreach ($listeners as $alias => $priority) {
                if ( is_numeric($alias) ) {
                    $alias = $priority;
                    $priority = 1;
                }
                $this->attachedListeners[] = $em->attach(
                    $event,
                    function (EventInterface $e) use ($mgr, $alias) {
                        return $mgr->triggerListener($alias, $e);
                    },
                    $priority
                );
            }
        }
    }

    public function detachEventListeners(EventManagerInterface $em)
    {
        foreach ($this->attachedListeners as $listener) {
            $em->detach($listener);
        }
    }

    public function triggerListener($alias, EventInterface $e)
    {
        if ( ! $this->has($alias, false, false) ) {
            throw new ServiceNotFoundException("Service extension '{$alias}' could not be loaded!");
        }
        $extension = $this->get($alias, false);
        if (! $extension instanceof ServiceExtensionInterface) {
            throw new ServiceNotCreatedException("Registered extension '{$alias}' is not of type ServiceExtension");
        }
        if ( ! $extension->checkPrecondition($e) ) {
            return;
        }

        return $extension->execute($e);
    }

    /**
     * @return array
     */
    public function getEventMap()
    {
        return $this->eventMap;
    }

    /**
     * @param array $eventMap
     */
    public function setEventMap(array $eventMap)
    {
        $this->eventMap = $eventMap;

        return $this;
    }

}
