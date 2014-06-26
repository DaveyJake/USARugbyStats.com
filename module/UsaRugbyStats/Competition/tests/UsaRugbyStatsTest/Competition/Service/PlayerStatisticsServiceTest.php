<?php
namespace UsaRugbyStatsTest\Competition\Service;

use Mockery;
use UsaRugbyStats\Competition\Service\PlayerStatisticsService;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use Zend\EventManager\EventInterface;

class PlayerStatisticsServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $mockMatchService;
    protected $mockPlayer;
    protected $mockTeamPlayer;

    public function setUp()
    {
        $this->mockPlayer = Mockery::mock('UsaRugbyStats\Application\Entity\AccountInterface');;
        $this->mockPlayer->shouldReceive('getId')->andReturn(1);

        $this->mockTeamPlayer = new MatchTeamPlayer();
        $this->mockTeamPlayer->setPlayer($this->mockPlayer);

        $this->mockMatchService = Mockery::mock('UsaRugbyStats\Competition\Service\Competition\MatchService');

        $this->service = new PlayerStatisticsService();
        $this->service->setCompetitionMatchService($this->mockMatchService);
    }

    public function getEmptyResult()
    {
        return [
            'career' => $this->getStatsFieldArray(),
            'season' => [],
            'team' => [],
            'opponent' => [],
        ];
    }

    public function getStatsFieldArray()
    {
        return ['PTS' => 0, 'TR' => 0, 'CV' => 0, 'PT' => 0, 'PK' => 0, 'DG' => 0, 'YC' => 0, 'RC' => 0];
    }

    public function testEventManagerCanShortCircuitCalculation()
    {
        $this->service->getEventManager()->attach('getStatisticsFor.pre', function (EventInterface $e) {
            $e->stopPropagation(true);

            return 'foobar';
        });
        $this->assertEquals('foobar', $this->service->getStatisticsFor($this->mockPlayer));
    }

    public function testHandlesCaseWhereThereAreNoMatchesToProcess()
    {
        $this->mockMatchService->shouldReceive('getRepository->findAllForPlayer')->once()->andReturn(new ArrayCollection());

        $result = $this->service->getStatisticsFor($this->mockPlayer);
        $this->assertEquals($this->getEmptyResult(), $result);
    }

    public function testHandlesCaseWhereThereAreNoGameEventsToProcess()
    {
        $mockMatch = new Match();
        $mockMatch->setDate(new \DateTime('2014-06-06'));

        $this->mockMatchService->shouldReceive('getRepository->findAllForPlayer')->once()->andReturn(new ArrayCollection([$mockMatch]));
        $result = $this->service->getStatisticsFor($this->mockPlayer);
        $this->assertEquals($this->getEmptyResult(), $result);
    }

    public function testIgnoresUnsupportedEventTypes()
    {
        $homeTeam = new Team();
        $homeTeam->setId(99);

        $awayTeam = new Team();
        $awayTeam->setId(42);

        $mockMatch = new Match();
        $mockMatch->setDate(new \DateTime('2014-06-06'));
        $mockMatch->getTeam('H')->setTeam($homeTeam)->addPlayer($this->mockTeamPlayer);
        $mockMatch->getTeam('A')->setTeam($awayTeam);

        $event = new SubEvent();
        $event->setType('BL');
        $event->setPlayerOn($this->mockTeamPlayer);
        $event->setPlayerOff($this->mockTeamPlayer);
        $event->setTeam($mockMatch->getTeam('H'));

        $mockMatch->addEvent($event);

        $this->mockMatchService->shouldReceive('getRepository->findAllForPlayer')->once()->andReturn(new ArrayCollection([$mockMatch]));
        $result = $this->service->getStatisticsFor($this->mockPlayer);
        $this->assertEquals($this->getEmptyResult(), $result);
    }

    /**
     * @dataProvider providerHandlesScoreProperly
     */
    public function testHandlesScoreProperly($type)
    {
        $homeTeam = new Team();
        $homeTeam->setId(99);

        $awayTeam = new Team();
        $awayTeam->setId(42);

        $mockMatch = new Match();
        $mockMatch->setDate(new \DateTime('2014-06-06'));
        $mockMatch->getTeam('H')->setTeam($homeTeam)->addPlayer($this->mockTeamPlayer);
        $mockMatch->getTeam('A')->setTeam($awayTeam);

        $event = new ScoreEvent();
        $event->setType($type);
        $event->setPlayer($this->mockTeamPlayer);
        $event->setTeam($mockMatch->getTeam('H'));

        $mockMatch->addEvent($event);

        $this->mockMatchService->shouldReceive('getRepository->findAllForPlayer')->once()->andReturn(new ArrayCollection([$mockMatch]));

        $expected = $this->getEmptyResult();
        $expected['career'][$type] = 1;
        $expected['career']['PTS'] = $event->getPoints();
        $expected['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['season']['2014-2015'][$type] = 1;
        $expected['season']['2014-2015']['PTS'] = $event->getPoints();
        $expected['team'][$homeTeam->getId()]['career'] = $this->getStatsFieldArray();
        $expected['team'][$homeTeam->getId()]['career'][$type] = 1;
        $expected['team'][$homeTeam->getId()]['career']['PTS'] = $event->getPoints();
        $expected['team'][$homeTeam->getId()]['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['team'][$homeTeam->getId()]['season']['2014-2015'][$type] = 1;
        $expected['team'][$homeTeam->getId()]['season']['2014-2015']['PTS'] = $event->getPoints();
        $expected['opponent'][$awayTeam->getId()]['career'] = $this->getStatsFieldArray();
        $expected['opponent'][$awayTeam->getId()]['career'][$type] = 1;
        $expected['opponent'][$awayTeam->getId()]['career']['PTS'] = $event->getPoints();
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015'][$type] = 1;
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015']['PTS'] = $event->getPoints();

        $result = $this->service->getStatisticsFor($this->mockPlayer);
        $this->assertEquals($expected, $result);
    }

    public function providerHandlesScoreProperly()
    {
        return [['TR'], ['PK'], ['DG'], ['PT'], ['CV']];
    }

    /**
     * @dataProvider providerHandlesCardProperly
     */
    public function testHandlesCardProperly($card)
    {
        $homeTeam = new Team();
        $homeTeam->setId(99);

        $awayTeam = new Team();
        $awayTeam->setId(42);

        $mockMatch = new Match();
        $mockMatch->setDate(new \DateTime('2014-06-06'));
        $mockMatch->getTeam('H')->setTeam($homeTeam)->addPlayer($this->mockTeamPlayer);
        $mockMatch->getTeam('A')->setTeam($awayTeam);

        $event = new CardEvent();
        $event->setType($card);
        $event->setPlayer($this->mockTeamPlayer);
        $event->setTeam($mockMatch->getTeam('H'));

        $mockMatch->addEvent($event);

        $this->mockMatchService->shouldReceive('getRepository->findAllForPlayer')->once()->andReturn(new ArrayCollection([$mockMatch]));

        $type = "{$card}C";

        $expected = $this->getEmptyResult();
        $expected['career'][$type] = 1;
        $expected['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['season']['2014-2015'][$type] = 1;
        $expected['team'][$homeTeam->getId()]['career'] = $this->getStatsFieldArray();
        $expected['team'][$homeTeam->getId()]['career'][$type] = 1;
        $expected['team'][$homeTeam->getId()]['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['team'][$homeTeam->getId()]['season']['2014-2015'][$type] = 1;
        $expected['opponent'][$awayTeam->getId()]['career'] = $this->getStatsFieldArray();
        $expected['opponent'][$awayTeam->getId()]['career'][$type] = 1;
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015'][$type] = 1;

        $result = $this->service->getStatisticsFor($this->mockPlayer);
        $this->assertEquals($expected, $result);
    }

    public function providerHandlesCardProperly()
    {
        return [['R'], ['Y']];
    }

    public function testScoresAreAdded()
    {
        $homeTeam = new Team();
        $homeTeam->setId(99);

        $awayTeam = new Team();
        $awayTeam->setId(42);

        $mockMatch = new Match();
        $mockMatch->setDate(new \DateTime('2014-06-06'));
        $mockMatch->getTeam('H')->setTeam($homeTeam)->addPlayer($this->mockTeamPlayer);
        $mockMatch->getTeam('A')->setTeam($awayTeam);

        $totalPoints = 0;

        $event = new ScoreEvent();
        $event->setType('TR');
        $event->setPlayer($this->mockTeamPlayer);
        $event->setTeam($mockMatch->getTeam('H'));
        $mockMatch->addEvent($event);
        $totalPoints += $event->getPoints();

        $event = new ScoreEvent();
        $event->setType('CV');
        $event->setPlayer($this->mockTeamPlayer);
        $event->setTeam($mockMatch->getTeam('H'));
        $mockMatch->addEvent($event);
        $totalPoints += $event->getPoints();

        $this->mockMatchService->shouldReceive('getRepository->findAllForPlayer')->once()->andReturn(new ArrayCollection([$mockMatch]));

        $expected = $this->getEmptyResult();
        $expected['career']['TR'] = 1;
        $expected['career']['CV'] = 1;
        $expected['career']['PTS'] = $totalPoints;
        $expected['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['season']['2014-2015']['TR'] = 1;
        $expected['season']['2014-2015']['CV'] = 1;
        $expected['season']['2014-2015']['PTS'] = $totalPoints;
        $expected['team'][$homeTeam->getId()]['career'] = $this->getStatsFieldArray();
        $expected['team'][$homeTeam->getId()]['career']['TR'] = 1;
        $expected['team'][$homeTeam->getId()]['career']['CV'] = 1;
        $expected['team'][$homeTeam->getId()]['career']['PTS'] = $totalPoints;
        $expected['team'][$homeTeam->getId()]['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['team'][$homeTeam->getId()]['season']['2014-2015']['TR'] = 1;
        $expected['team'][$homeTeam->getId()]['season']['2014-2015']['CV'] = 1;
        $expected['team'][$homeTeam->getId()]['season']['2014-2015']['PTS'] = $totalPoints;
        $expected['opponent'][$awayTeam->getId()]['career'] = $this->getStatsFieldArray();
        $expected['opponent'][$awayTeam->getId()]['career']['TR'] = 1;
        $expected['opponent'][$awayTeam->getId()]['career']['CV'] = 1;
        $expected['opponent'][$awayTeam->getId()]['career']['PTS'] = $totalPoints;
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015'] = $this->getStatsFieldArray();
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015']['TR'] = 1;
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015']['CV'] = 1;
        $expected['opponent'][$awayTeam->getId()]['season']['2014-2015']['PTS'] = $totalPoints;

        $result = $this->service->getStatisticsFor($this->mockPlayer);
        $this->assertEquals($expected, $result);
    }
}
