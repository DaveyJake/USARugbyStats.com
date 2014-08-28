<?php
namespace UsaRugbyStats\RemoteDataSync\Injectors;

use Zend\Console\Request as ConsoleRequest;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\Competition\Entity\Team;

class SyncTeam extends AbstractInjector
{
    /**
     * @var TeamService
     */
    protected $svcTeam;

    public function executeAction()
    {
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $team_id = $request->getParam('team-id',  null);
        if ( empty($team_id) ) {
            $this->getLogger()->crit('You must specify a team to sync!');

            return;
        }

        $team = $this->getTeamService()->findByID($team_id);
        if (! $team instanceof Team) {
            $this->getLogger()->crit('You must specify a team to sync!');

            return;
        }

        $this->getLogger()->info('Enqueuing your job...');

        $token = \Resque::enqueue('sync_team', 'UsaRugbyStats\RemoteDataSync\Jobs\SyncTeam', [
            'team_id' => $team->getId(),
            'wait' => $request->getParam('wait', false),
        ]);

        $this->getLogger()->info('Job is in flight!');
        $this->getLogger()->debug(' - Token: ' . $token);

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

}
