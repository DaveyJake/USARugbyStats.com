<?php
namespace UsaRugbyStats\DataImporter\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\Log\LoggerAwareInterface;

class FixtureRunner extends AbstractController
{
    protected $fixturesRun = array();

    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $group_id = trim($this->params()->fromRoute('group'));
        if ( empty($group_id) ) {
            $this->getLogger()->crit('No fixture group specified!');

            return;
        }

        $fixtures = $this->getFixtureService()->getFixtureGroup($group_id);
        if ( empty($fixtures) ) {
            $this->getLogger()->crit('No fixtures in group specified!');

            return;
        }

        foreach ($fixtures as $name => $f) {
            $this->runFixture("{$group_id}.{$name}");
        }
    }

    protected function runFixture($name)
    {
        if ( in_array($name, $this->fixturesRun, true) ) {
            return;
        }
        $this->getLogger()->debug('Running fixture: ' . $name);

        $fixture = $this->getFixtureService()->getFixture($name);
        if ( empty($fixture) ) {
            $this->getLogger()->err(' - Fixture not found!');

            return;
        }

        if ( ! empty($fixture['dependencies']) ) {
            foreach ($fixture['dependencies'] as $dep) {
                if ( ! in_array($dep, $this->fixturesRun, true) ) {
                    $this->getLogger()->debug(" - {$name} depends on {$dep}");
                    $this->runFixture($dep);
                }
            }
        }

        if ( ! is_file($fixture['file']) && is_readable($fixture['file']) ) {
            $this->getLogger()->err(' - Fixture file not readable!');

            return;
        }

        $data = include $fixture['file'];
        if ( empty($data) ) {
            $this->getLogger()->err(' - Fixture data could not be loaded!');

            return;
        }

        try {
            $task = $this->getTaskService()->get($fixture['task']);
            if ($task instanceof LoggerAwareInterface) {
                $task->setLogger($this->getLogger());
            }
            $task->execute($data);
        } catch ( \Exception $e ) {
            $this->getLogger()->crit(' - EXCEPTION: ' . $e->getMessage());
        }

        array_push($this->fixturesRun, $name);
    }

}
