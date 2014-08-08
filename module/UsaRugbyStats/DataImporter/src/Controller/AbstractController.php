<?php
namespace UsaRugbyStats\DataImporter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use UsaRugbyStats\DataImporter\Service\TaskService;
use UsaRugbyStats\DataImporter\Service\FixtureService;

abstract class AbstractController extends AbstractActionController
{
    protected $logger;

    protected $taskService;

    protected $fixtureService;

    abstract public function executeAction();

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if ( empty($this->logger) ) {
            $this->logger = new Logger();
            $this->logger->addWriter(new Stream('php://stdout'));
        }

        return $this->logger;
    }

    /**
     * @param  Logger $obj
     * @return self
     */
    public function setLogger(Logger $obj)
    {
        $this->logger = $obj;

        return $this;
    }

    /**
     * @return TaskService
     */
    public function getTaskService()
    {
        if ( empty($this->taskService) ) {
            $this->taskService = $this->getServiceLocator()->get(
               'usarugbystats_data-importer_task-service'
            );
        }

        return $this->taskService;
    }

    /**
     * @param  TaskService $s
     * @return self
     */
    public function setTaskService(TaskService $s)
    {
        $this->taskService = $s;

        return $this;
    }

    /**
     * @return FixtureService
     */
    public function getFixtureService()
    {
        if ( empty($this->fixtureService) ) {
            $this->fixtureService = $this->getServiceLocator()->get(
                'usarugbystats_data-importer_fixture-service'
            );
        }

        return $this->fixtureService;
    }

    /**
     * @param  FixtureService $s
     * @return self
     */
    public function setFixtureService(FixtureService $s)
    {
        $this->fixtureService = $s;

        return $this;
    }
}
