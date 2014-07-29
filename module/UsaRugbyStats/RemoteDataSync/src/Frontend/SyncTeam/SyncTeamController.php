<?php
namespace UsaRugbyStats\RemoteDataSync\Frontend\SyncTeam;

use Zend\Mvc\Controller\AbstractActionController;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Service\TeamService;
use Zend\View\Model\JsonModel;
use UsaRugbyStats\RemoteDataSync\Queue\QueueInterface;

class SyncTeamController extends AbstractActionController
{
    public function indexAction()
    {
        $id = $this->params()->fromQuery('id');
        if ( ! \Zend\Validator\StaticValidator::execute($id, 'Zend\Validator\Digits') ) {
            $this->getResponse()->setStatusCode(400);

            return new JsonModel(['error' => 'Invalid Team ID specified!']);
        }

        $team = $this->getTeamService()->findByID($id);
        if (! $team instanceof Team) {
            $this->getResponse()->setStatusCode(400);

            return new JsonModel(['error' => 'Invalid Team ID specified!']);
        }

        if ( ! $this->isGranted('competition.team.update', $team) ) {
            $this->getResponse()->setStatusCode(403);

            return new JsonModel(['error' => 'You are not authorized to perform this action!']);
        }

        $teamRemoteId = $team->getRemoteId();
        if ( empty($teamRemoteId)) {
            $this->getResponse()->setStatusCode(412);

            return new JsonModel(['error' => 'This team is not properly configured for synchronization!']);
        }

        $token = $this->getQueueAdapter()->enqueue('sync_team', 'UsaRugbyStats\RemoteDataSync\Jobs\SyncTeam', [ 'team_id' => $team->getId() ]);

        return $this->redirect()->toRoute('usarugbystats_remotedatasync_queue_trigger_statuscheck', [], ['query' => ['id' => $token]]);
    }

    /**
     * @return TeamService
     */
    public function getTeamService()
    {
        if ( empty($this->svcTeam) ) {
            $this->svcTeam = new TeamService();
        }

        return $this->svcTeam;
    }

    /**
     *
     * @param  TeamService $obj
     * @return self
     */
    public function setTeamService(TeamService $obj)
    {
        $this->svcTeam = $obj;

        return $this;
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
