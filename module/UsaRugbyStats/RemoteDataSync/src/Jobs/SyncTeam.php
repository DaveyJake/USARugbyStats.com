<?php
namespace UsaRugbyStats\RemoteDataSync\Jobs;

use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Team\Member as TeamMember;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member as MemberRole;
use UsaRugbyStats\Competition\Service\TeamService;
use UsaRugbyStats\RemoteDataSync\DataProvider\DataProviderInterface;
use UsaRugbyStats\RemoteDataSync\Queue\QueueInterface;
use UsaRugbyStats\Account\Entity\Account;

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
        $encounteredPlayerRemoteIDs = [];

        $this->getLogger()->info('Enqueueing player data for update...');
        foreach ($data as $player) {
            if ( !isset($player['ID']) ) {
                continue;
            }
            array_push($encounteredPlayerRemoteIDs, $player['ID']);

            if ( !isset($player['club_ID']) ) {
                $player['club_ID'] = $team->getRemoteId();
            }
            if ( $player['club_ID'] != $team->getRemoteId() ) {
                $this->getLogger()->info(sprintf(' - Player %s => Skipped (mismatched club_ID)', $player['ID']));
                continue;
            }

            $this->getLogger()->info(sprintf(' - Processing Player %s', $player['ID']));
            $token = $this->getQueueAdapter()->enqueue('sync_player', 'UsaRugbyStats\RemoteDataSync\Jobs\SyncPlayer', [
                'player_id'    => NULL,
                'player_data'  => $player,
            ]);
            array_push($childJobs, $token);
            $this->getLogger()->info(sprintf(' - Player %s => Job #%s', $player['ID'], $token));
        }

        $this->getLogger()->info('Waiting on child jobs..');
        foreach ($childJobs as $token) {
            while ( in_array($this->getQueueAdapter()->getJobStatus($token), [1,2], true) ) {
                usleep(500);
            }
        }

        // Clear the OM and reload the team entity to ensure we have the whole picture
        $this->getTeamService()->getObjectManager()->clear();
        $team = $this->getTeamService()->findByID($team->getId());

        $this->getLogger()->info('Cleaning up orphaned records..');

        $currentMemberships = $team->getMembers();
        $dirty = false;
        foreach ($currentMemberships as $item) {
            if (! $item instanceof TeamMember) {
                continue;
            }
            if ( ! ( $role = $item->getRole() ) instanceof MemberRole ) {
                continue;
            }
            if ( ! ( $acct = $role->getAccount() ) instanceof Account ) {
                continue;
            }

            $rid = $acct->getRemoteId();
            if ( empty($rid) || !in_array($rid, $encounteredPlayerRemoteIDs, true) ) {
                $this->getLogger()->debug(sprintf(' - Removing Player %s', $rid ?: ('ID#'.$acct->getId())));
                $team->removeMember($item);
                $dirty = true;
            }
        }

        if ($dirty) {
            $this->getTeamService()->save($team);
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
