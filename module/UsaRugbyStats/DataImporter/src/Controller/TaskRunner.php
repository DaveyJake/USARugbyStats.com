<?php
namespace UsaRugbyStats\DataImporter\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\Log\LoggerAwareInterface;

class TaskRunner extends AbstractController
{

    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $task_id = trim($this->params()->fromRoute('task'));
        if ( empty($task_id) ) {
            $this->getLogger()->crit('No task name specified!');

            return;
        }

        $this->getLogger()->info(sprintf('Loading task: %s', $task_id));
        if ( ! $this->getTaskService()->has($task_id, true, false) ) {
            $this->getLogger()->crit('No task matching that name was found');

            return;
        }
        $task = $this->getTaskService()->get($task_id);
        if ($task instanceof LoggerAwareInterface) {
            $task->setLogger($this->getLogger());
        }

        $data = null;

        $this->getLogger()->info('Loading data from file...');
        $filename = $this->params()->fromRoute('file');
        if ( !empty($filename) && is_file($filename) ) {
            switch ( strtolower(pathinfo($filename, PATHINFO_EXTENSION)) ) {
                case 'csv':
                    $reader = new \SplFileObject($filename);
                    $reader->setFlags(\SplFileObject::READ_CSV);
                    $data = array();
                    $headerLine = null;
                    foreach ($reader as $key => $line) {
                        if ( ! is_array($line) || $line == array(null) ) {
                            continue;
                        }
                        if ( is_null($headerLine) ) {
                            $headerLine = $line;
                            continue;
                        }
                        if ( count($headerLine) != count($line) ) {
                            $this->getLogger()->err(sprintf('** Line %s contains different number of columns than header', $key));
                            continue;
                        }
                        $result = array_combine($headerLine, $line);
                        if ( is_array($result) ) {
                           array_push($data, $result);
                        }
                    }
                    continue;
                default:
                    $data = \Zend\Config\Factory::fromFile($filename);
                    continue;
            }
        }

        if ( empty($data) ) {
            $this->getLogger()->crit('No data was loaded!');

            return;
        }

        $this->getLogger()->info('Executing task...');
        $task->execute($data);

    }

}
