<?php
namespace UsaRugbyStatsTest\Competition\Service\PlayerStatistics;

use Mockery;
use UsaRugbyStats\Competition\Service\PlayerStatistics\AppearanceCreditCalculator;
use Zend\EventManager\Event;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;

class AppearanceCreditCalculatorTest extends \PHPUnit_Framework_TestCase
{
    protected $service;

    public function setUp()
    {
        $this->params = new \ArrayObject();

        $this->params['result'] = [];
        $this->params['match'] = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');
        $this->params['matchYear'] = '2014-15';
        $this->params['account'] = Mockery::mock('UsaRugbyStats\Application\Entity\AccountInterface');

        $this->event = new Event();
        $this->event->setParams($this->params);

        $this->service = new AppearanceCreditCalculator();
    }

    public function testPlayerGetsNoCreditForBeingOnStartingRosterInReserveSlot()
    {
        $pos = new MatchTeamPlayer();
        $pos->setPosition(MatchTeamPlayer::POSITION_R1);

        $this->params['match']->shouldReceive('getRosterPositionForPlayer')->once()->andReturn($pos);

        $this->service->processMatch($this->event);

        $this->assertTrue(!isset($this->params['result']['career']['credits']['started']));
    }

    public function testPlayerGetsCreditForBeingOnStartingRosterInNonReserveSlot()
    {
        $opponent = new MatchTeam();
        $opponent->setTeam((new Team())->setId(456));
        $opponent->setType('A');
        $opponent->setMatch($this->params['match']);

        $matchTeam = new MatchTeam();
        $matchTeam->setTeam((new Team())->setId(123));
        $matchTeam->setType('H');
        $matchTeam->setMatch($this->params['match']);

        $pos = new MatchTeamPlayer();
        $pos->setTeam($matchTeam);
        $pos->setPosition(MatchTeamPlayer::POSITION_LHP);

        $this->params['match']->shouldReceive('getRosterPositionForPlayer')->once()->andReturn($pos);
        $this->params['match']->shouldReceive('getTeam')->with('H')->andReturn($matchTeam);
        $this->params['match']->shouldReceive('getTeam')->with('A')->andReturn($opponent);

        $this->service->processMatch($this->event);

        $this->assertEquals(['played' => 1, 'started' => 1], $this->params['result']['career']['credits']);
        $this->assertEquals(['played' => 1, 'started' => 1], $this->params['result']['season']['2014-15']['credits']);
        $this->assertEquals(['played' => 1, 'started' => 1], $this->params['result']['team']['123']['career']['credits']);
        $this->assertEquals(['played' => 1, 'started' => 1], $this->params['result']['team']['123']['season']['2014-15']['credits']);
        $this->assertEquals(['played' => 1, 'started' => 1], $this->params['result']['opponent']['456']['career']['credits']);
        $this->assertEquals(['played' => 1, 'started' => 1], $this->params['result']['opponent']['456']['season']['2014-15']['credits']);
    }

    public function testPlayerGetsCreditForBeingSubbedIntoAGame()
    {
        $opponent = new MatchTeam();
        $opponent->setTeam((new Team())->setId(456));
        $opponent->setType('A');
        $opponent->setMatch($this->params['match']);

        $matchTeam = new MatchTeam();
        $matchTeam->setTeam((new Team())->setId(123));
        $matchTeam->setType('H');
        $matchTeam->setMatch($this->params['match']);

        $pos = new MatchTeamPlayer();
        $pos->setTeam($matchTeam);
        $pos->setPosition(MatchTeamPlayer::POSITION_R8);
        $pos->setPlayer($this->params['account']);

        $this->params['match']->shouldReceive('getRosterPositionForPlayer')->once()->andReturn($pos);
        $this->params['match']->shouldReceive('getTeam')->with('H')->andReturn($matchTeam);
        $this->params['match']->shouldReceive('getTeam')->with('A')->andReturn($opponent);
        $this->params['account']->shouldReceive('getId')->andReturn(456);

        $this->params['event'] = new SubEvent();
        $this->params['event']->setPlayerOn($pos);

        $this->service->processMatchEvent($this->event);

        $this->assertEquals(['played' => 1, 'sub' => 1], $this->params['result']['career']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1], $this->params['result']['season']['2014-15']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1], $this->params['result']['team']['123']['career']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1], $this->params['result']['team']['123']['season']['2014-15']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1], $this->params['result']['opponent']['456']['career']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1], $this->params['result']['opponent']['456']['season']['2014-15']['credits']);
    }

    public function testPlayerCannotGetASecondPlayedCreditForSubbingBackIn()
    {
        $opponent = new MatchTeam();
        $opponent->setTeam((new Team())->setId(456));
        $opponent->setType('A');
        $opponent->setMatch($this->params['match']);

        $matchTeam = new MatchTeam();
        $matchTeam->setTeam((new Team())->setId(123));
        $matchTeam->setType('H');
        $matchTeam->setMatch($this->params['match']);

        $pos = new MatchTeamPlayer();
        $pos->setTeam($matchTeam);
        $pos->setPosition(MatchTeamPlayer::POSITION_LHP);
        $pos->setPlayer($this->params['account']);

        $this->params['match']->shouldReceive('getRosterPositionForPlayer')->twice()->andReturn($pos);
        $this->params['match']->shouldReceive('getTeam')->with('H')->andReturn($matchTeam);
        $this->params['match']->shouldReceive('getTeam')->with('A')->andReturn($opponent);
        $this->params['account']->shouldReceive('getId')->andReturn(456);

        // Process the match, netting the player a 'played' and a 'started' point
        $this->service->processMatch($this->event);

        $dummyPlayer = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer');
        $dummyPlayer->shouldReceive('getPlayer->getId')->andReturn(777);

        // Sub the player off
        $this->params['event'] = new SubEvent();
        $this->params['event']->setPlayerOn($dummyPlayer);
        $this->params['event']->setPlayerOff($pos);
        $this->service->processMatchEvent($this->event);

        // Sub them back on, netting them a 'sub' point but not another 'played' point
        $this->params['event'] = new SubEvent();
        $this->params['event']->setPlayerOn($pos);
        $this->params['event']->setPlayerOff($dummyPlayer);
        $this->service->processMatchEvent($this->event);

        $this->assertEquals(['played' => 1, 'sub' => 1, 'started' => 1], $this->params['result']['career']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1, 'started' => 1], $this->params['result']['season']['2014-15']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1, 'started' => 1], $this->params['result']['team']['123']['career']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1, 'started' => 1], $this->params['result']['team']['123']['season']['2014-15']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1, 'started' => 1], $this->params['result']['opponent']['456']['career']['credits']);
        $this->assertEquals(['played' => 1, 'sub' => 1, 'started' => 1], $this->params['result']['opponent']['456']['season']['2014-15']['credits']);
    }
}
