<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\Status;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use UsaRugbyStats\RemoteDataSync\Queue\QueueInterface;

class StatusController extends AbstractActionController
{
    public function indexAction()
    {
        $token = $this->params()->fromQuery('id');
        if ( ! \Zend\Validator\StaticValidator::execute($token, 'Zend\Validator\Hex') ) {
            $this->getResponse()->setStatusCode(400);

            return new JsonModel(['error' => 'Invalid Token specified!']);
        }

        return new JsonModel(['token' => $token, 'status' => $this->getQueueAdapter()->getJobStatus($token)]);
    }

    /**
     * @return QueueInterface
     */
    public function getQueueAdapter()
    {
        if ( empty($this->queueAdapter) ) {
            $this->queueAdapter = $this->getServiceLocator()->get(
                'usa-rugby-stats_remote-data-sync_queueprovider'
            );
        }

        return $this->queueAdapter;
    }

    /**
     * @param  QueueInterface $queueAdapter
     * @return self
     */
    public function setQueueAdapter(QueueInterface $queueAdapter = null)
    {
        $this->queueAdapter = $queueAdapter;

        return $this;
    }
}
