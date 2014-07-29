<?php
namespace UsaRugbyStats\RemoteDataSync\Jobs;

use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\RemoteDataSync\DataProvider\DataProviderInterface;
use UsaRugbyStats\RemoteDataSync\Queue\Resque;
use UsaRugbyStats\RemoteDataSync\Queue\QueueInterface;

class SyncTeam extends AbstractJob
{
    /**
     * @var QueueInterface
     */
    protected $queueAdapter;

    /**
     * @var TeamService
     */
    protected $teamService;

    /**
     * @var DataProviderInterface
     */
    protected $dataProvider;

    /**
     * @var AbstractJob
     */
    protected $syncPlayerJobPrototype;

    /**
     * Default Payload Values
     *
     * @var array
     */
    protected $payloadDefaults = [
        'team_id'      => NULL,
    ];

    public function run()
    {
        if ( !isset($this->args['team_id']) || empty($this->args['team_id']) ) {
            $this->getLogger()->err('Invalid Request!');
            throw new \InvalidArgumentException('Invalid Request!');
        }

        $this->getLogger()->debug('Loading Team ID # ' . $this->args['team_id']);
        $team = $this->getTeamService()->findByID($this->args['team_id']);
        if (! $team instanceof Team) {
            $this->getLogger()->err('Team Not Found!');
            throw new \InvalidArgumentException('Team Not Found!');
        }

        $remoteKey = $team->getRemoteId();
        if ( empty($remoteKey) ) {
            $this->getLogger()->err('Team is not connected with the remote system!');
            throw new \RuntimeException('Team is not connected with the remote system!');
        }

        $this->getLogger()->info(sprintf('%s - (Local: %d, Remote: %d)', $team->getName(), $team->getId(), $team->getRemoteId()));

        if ( isset($this->args['team_data']) && is_array($this->args['team_data']) ) {
            $this->getLogger()->info('Reading data from job payload (\'team_data\')...');
            $data = $this->args['team_data'];
        } else {
            $this->getLogger()->info('Fetching data from remote data provider...');
            $data = $this->getDataProvider()->syncTeam($team);
        }

        if ( empty($data) ) {
            $this->getLogger()->debug('No data was received from the data provider...');

            return true;
        }

        $childJobs = [];

        $this->getLogger()->info('Enqueueing player data for update...');
        foreach ($data as $player) {
            if ( !isset($player['ID']) ) {
                continue;
            }
            if ( !isset($player['club_ID']) ) {
                $player['club_ID'] = $team->getRemoteId();
            }
            if ( $player['club_ID'] != $team->getRemoteId() ) {
                $this->getLogger()->info(sprintf(' - Player %s => Skipped (mismatched club_ID)', $player['ID']));
                continue;
            }

            /* uncomment these lines to run individual player updates in the background
            $token = $this->getQueueAdapter()->enqueue('sync_player', 'UsaRugbyStats\RemoteDataSync\Jobs\SyncPlayer', [
                'player_id'    => NULL,
                'player_data'  => $player,
            ]);

            array_push($childJobs, $token);
            $this->getLogger()->info(sprintf(' - Player %s => Job #%s', $player['ID'], $token));
            */

            $this->getLogger()->info(sprintf(' - Processing Player %s', $player['ID']));
            $childJob = clone $this->getSyncPlayerJobPrototype();
            $childJob->setServiceLocator($this->getServiceLocator());
            $childJob->setSharedManager($this->getSharedManager());
            $childJob->setLogger($this->getLogger());
            $childJob->args = [ 'player_id'  => NULL, 'player_data' => $player ];
            $childJob->perform();

        }

        $this->getLogger()->info('Completed!');

        return true;
    }

    /**
     * @return TeamService
     */
    public function getTeamService()
    {
        if ( empty($this->teamService) ) {
            $this->teamService = $this->getServiceLocator()->get(
                'usarugbystats_competition_team_service'
            );
        }

        return $this->teamService;
    }

    /**
     *
     * @param  TeamService $obj
     * @return self
     */
    public function setTeamService(TeamService $obj = null)
    {
        $this->teamService = $obj;

        return $this;
    }

    /**
     * @return DataProviderInterface
     */
    public function getDataProvider()
    {
        if ( empty($this->dataProvider) ) {
            $this->dataProvider = $this->getServiceLocator()->get(
                'usa-rugby-stats_remote-data-sync_provider'
            );
        }

        return $this->dataProvider;
    }

    /**
     * @param  DataProviderInterface $dataProvider
     * @return self
     */
    public function setDataProvider(DataProviderInterface $dataProvider = null)
    {
        $this->dataProvider = $dataProvider;

        return $this;
    }

    /**
     * @return QueueInterface
     */
    public function getQueueAdapter()
    {
        if ( empty($this->queueAdapter) ) {
            $this->queueAdapter = new Resque();
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

    /**
     * @return AbstractJob
     */
    public function getSyncPlayerJobPrototype()
    {
        if ( empty($this->syncPlayerJobPrototype) ) {
            $this->syncPlayerJobPrototype = new SyncPlayer();
        }

        return $this->syncPlayerJobPrototype;
    }

    /**
     * @param AbstractJob $obj
     */
    public function setSyncPlayerJobPrototype(AbstractJob $obj)
    {
        $this->syncPlayerJobPrototype = $obj;

        return $this;
    }

}
