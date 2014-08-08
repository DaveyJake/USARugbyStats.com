<?php
namespace UsaRugbyStats\DataImporter\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ConfigInterface;

class TaskService extends ServiceManager
{
    protected $fixtureMap = array();

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        if (is_null($config)) {
            $config = new TaskServiceConfiguration();
        }

        parent::__construct($config);
    }

    public function setFixtureMap(array $map)
    {
        $this->fixtureMap = $map;
    }
}
