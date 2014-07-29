<?php
namespace UsaRugbyStatsTest\RemoteDataSync\Jobs;

use UsaRugbyStats\RemoteDataSync\Jobs\SyncTeam;
use Zend\Log\Logger;

class SyncTeamTest extends AbstractJobTest
{
    public function setUp()
    {
        $this->mockServiceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');

        $this->mockEventManager = \Mockery::mock('Zend\EventManager\SharedEventManagerInterface');

        $this->mockLogger = new Logger();
        $this->mockLoggerWriter = new \Zend\Log\Writer\Mock();
        $this->mockLogger->addWriter($this->mockLoggerWriter);

        $this->mockQueueAdapter = \Mockery::mock('UsaRugbyStats\RemoteDataSync\Queue\QueueInterface');

        $this->mockSyncPlayer = \Mockery::mock('UsaRugbyStats\RemoteDataSync\Jobs\AbstractJob');
        $this->mockSyncPlayer->shouldIgnoreMissing(true);

        $this->job = new SyncTeam();
        $this->job->setLogger($this->mockLogger);
        $this->job->setQueueAdapter($this->mockQueueAdapter);
        $this->job->setSyncPlayerJobPrototype($this->mockSyncPlayer);

        $this->job->args = [];
        $this->job->job = new \stdClass();
        $this->job->job->serviceLocator = $this->mockServiceLocator;
        $this->job->job->sharedEventManager = $this->mockEventManager;
    }

    public function testGetTeamServiceProxiesToServiceLocatorForLoad()
    {
        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $this->mockServiceLocator->shouldReceive('get')->withArgs(['usarugbystats_competition_team_service'])->once()->andReturn($service);
        $this->assertSame($service, $this->job->getTeamService());
    }

    public function testGetDataProviderProxiesToServiceLocatorForLoad()
    {
        $service = \Mockery::mock('UsaRugbyStats\RemoteDataSync\DataProvider\DataProviderInterface');
        $this->mockServiceLocator->shouldReceive('get')->withArgs(['usa-rugby-stats_remote-data-sync_provider'])->once()->andReturn($service);
        $this->assertSame($service, $this->job->getDataProvider());
    }

    public function testGetQueueProviderInstantiatesResqueWrapperByDefault()
    {
        $this->job->setQueueAdapter(null);
        $this->assertInstanceOf('UsaRugbyStats\RemoteDataSync\Queue\Resque', $this->job->getQueueAdapter());
    }

    public function testExecuteFailsWhenNoTeamIdSpecified()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->job->perform();
    }

    public function testExecuteFailsWhenInvalidTeamIdSpecified()
    {
        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $service->shouldReceive('findByID')->withArgs([42])->once()->andReturnNull();

        $this->job->setTeamService($service);

        $this->setExpectedException('InvalidArgumentException');

        $this->job->args = ['team_id' => 42];
        $this->job->perform();
    }

    public function testExecuteFailsWhenTeamHasNoRemoteKey()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getRemoteId')->once()->andReturnNull();

        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $service->shouldReceive('findByID')->withArgs([42])->once()->andReturn($mockTeam);

        $this->job->setTeamService($service);

        $this->setExpectedException('RuntimeException');

        $this->job->args = ['team_id' => 42];
        $this->job->perform();
    }

    public function testExecuteDoesNotCallDataProviderIfTeamDataIsProvided()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getRemoteId')->andReturn(123456);
        $mockTeam->shouldReceive('getName')->andReturn('Foo Team');
        $mockTeam->shouldReceive('getId')->andReturn(42);

        $mockDataProvider = \Mockery::mock('UsaRugbyStats\RemoteDataSync\DataProvider\DataProviderInterface');
        $mockDataProvider->shouldReceive('syncTeam')->never();

        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $service->shouldReceive('findByID')->withArgs([42])->once()->andReturn($mockTeam);

        $this->job->setTeamService($service);
        $this->job->setDataProvider($mockDataProvider);

        $this->job->args = ['team_id' => 42, 'team_data' => array()];
        $this->assertTrue($this->job->perform());

        $lastMessage = array_pop($this->mockLoggerWriter->events);
        $this->assertStringStartsWith('No data was received', @$lastMessage['message']);
    }

    public function testExecuteBailsOutEarlyWhenDataProviderReturnsNoData()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getRemoteId')->andReturn(123456);
        $mockTeam->shouldReceive('getName')->andReturn('Foo Team');
        $mockTeam->shouldReceive('getId')->andReturn(42);

        $mockDataProvider = \Mockery::mock('UsaRugbyStats\RemoteDataSync\DataProvider\DataProviderInterface');
        $mockDataProvider->shouldReceive('syncTeam')->withArgs([$mockTeam])->once()->andReturn([]);

        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $service->shouldReceive('findByID')->withArgs([42])->once()->andReturn($mockTeam);

        $this->job->setTeamService($service);
        $this->job->setDataProvider($mockDataProvider);

        $this->job->args = ['team_id' => 42];
        $this->assertTrue($this->job->perform());

        $lastMessage = array_pop($this->mockLoggerWriter->events);
        $this->assertStringStartsWith('No data was received', @$lastMessage['message']);
    }

    public function testExecuteHappyCaseWithProvidedData()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getRemoteId')->andReturn(123456);
        $mockTeam->shouldReceive('getName')->andReturn('Foo Team');
        $mockTeam->shouldReceive('getId')->andReturn(42);

        $mockDataProvider = \Mockery::mock('UsaRugbyStats\RemoteDataSync\DataProvider\DataProviderInterface');
        $mockDataProvider->shouldReceive('syncTeam')->never();

        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $service->shouldReceive('findByID')->withArgs([42])->once()->andReturn($mockTeam);

        $this->job->setTeamService($service);
        $this->job->setDataProvider($mockDataProvider);

        $this->job->args = ['team_id' => 42, 'team_data' => [
            [ 'ID' => '424242' ],
            [ /* purposefully invalid */ ],
            [ 'ID' => '222222', 'club_ID' => '99999' /* should be skipped */ ],
        ]];

        $this->assertTrue($this->job->perform());

        $lastMessage = array_pop($this->mockLoggerWriter->events);
        $this->assertStringStartsWith('Completed', @$lastMessage['message']);
    }

}
