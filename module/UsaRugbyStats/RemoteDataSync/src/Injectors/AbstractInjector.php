<?php
namespace UsaRugbyStats\RemoteDataSync\Injectors;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

abstract class AbstractInjector extends AbstractActionController
{
    protected $logger;

    abstract public function executeAction();

    public function getLogger()
    {
        if ( empty($this->logger) ) {
            $this->logger = new Logger();
            $this->logger->addWriter(new Stream('php://stdout'));
        }

        return $this->logger;
    }

    public function setLogger(Logger $obj)
    {
        $this->logger = $obj;

        return $this;
    }

}
