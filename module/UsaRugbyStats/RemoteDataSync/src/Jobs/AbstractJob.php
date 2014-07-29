<?php
namespace UsaRugbyStats\RemoteDataSync\Jobs;

use Zend\Log\LoggerAwareTrait;
use Zend\Log\LoggerInterface;
use Zend\Log\LoggerAwareInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as LogStreamWriter;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\EventManager\SharedEventManagerAwareInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Stdlib\ArrayUtils;

abstract class AbstractJob implements LoggerAwareInterface, ServiceLocatorAwareInterface, SharedEventManagerAwareInterface
{
    use LoggerAwareTrait;
    use ServiceLocatorAwareTrait;

    /**
     * @var SharedEventManagerInterface
     */
    protected $sharedEventManager;

    /**
     * Override getLogger to instantiate a stdout writer if no logger provided
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        if (! $this->logger instanceof LoggerInterface) {
            $this->logger = new Logger();
            $this->logger->addWriter(new LogStreamWriter('php://stdout'));
        }

        return $this->logger;
    }

    /**
     * Override getServiceLocator to pull SL from job if none is provided
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        if ( is_null($this->serviceLocator) && isset($this->job->serviceLocator) ) {
            return $this->job->serviceLocator;
        }

        return $this->serviceLocator;
    }

    /**
     * Inject a SharedEventManager instance
     *
     * @param  SharedEventManagerInterface      $sharedEventManager
     * @return SharedEventManagerAwareInterface
     */
    public function setSharedManager(SharedEventManagerInterface $sharedEventManager)
    {
        $this->sharedEventManager = $sharedEventManager;

        return $this;
    }

    /**
     * Override getSharedManager to pull SEM from job if none is provided
     *
     * @return SharedEventManagerInterface
    */
    public function getSharedManager()
    {
        if ( is_null($this->sharedEventManager) && isset($this->job->sharedEventManager) ) {
            return $this->job->sharedEventManager;
        }

        return $this->sharedEventManager;
    }

    /**
     * Remove any shared collections
     *
     * @return void
    */
    public function unsetSharedManager()
    {
        $this->sharedEventManager = null;
        $this->job->sharedEventManager = null;
    }

    abstract public function run();

    public function perform()
    {
        $this->args = isset($this->payloadDefaults)
            ? ArrayUtils::merge($this->payloadDefaults, $this->args)
            : $this->args;

        return $this->run();
    }
}
