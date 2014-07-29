<?php
namespace UsaRugbyStatsTest\RemoteDataSync\DataProvider;

use UsaRugbyStats\RemoteDataSync\DataProvider\JsonWebServiceProvider;
use UsaRugbyStats\Competition\Entity\Team;
use Zend\Http\Response;

class JsonWebServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->mockClient = \Mockery::mock('Zend\Http\Client[send]');

        $this->service = new JsonWebServiceProvider();
        $this->service->setHttpClient($this->mockClient);
        $this->service->setWebServiceEndpoint('http://localhost');
    }

    public function testSyncTeamFailsWhenResponseIsNotJson()
    {
        $team = new Team();
        $team->setRemoteId(4242);

        $mockResponse = new Response();
        $mockResponse->setContent('IAmNotJSON');

        $this->mockClient->shouldReceive('send')->once()->andReturn($mockResponse);

        $this->assertNull($this->service->syncTeam($team));
    }

    public function testSyncTeamSucceedsWhenResponseIsJson()
    {
        $team = new Team();
        $team->setRemoteId(4242);

        $dataSet = [
            [ 'ID' => 123 ],
            [ 'ID' => 456 ],
        ];

        $mockResponse = new Response();
        $mockResponse->setContent(json_encode($dataSet));

        $this->mockClient->shouldReceive('send')->once()->andReturn($mockResponse);

        $this->assertEquals($dataSet, $this->service->syncTeam($team));
    }
}
