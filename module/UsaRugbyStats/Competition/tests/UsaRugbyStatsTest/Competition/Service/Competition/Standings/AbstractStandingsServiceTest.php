<?php
namespace UsaRugbyStatsTest\Competition\Service\Competition\Standings;

use UsaRugbyStats\Competition\Service\Competition\StandingsService;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

abstract class AbstractStandingsServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var StandingsService
     */
    protected $service;

    /**
     * @var Competition
     */
    protected $competition;

    public function setUp()
    {
        $this->faker = \Faker\Factory::create();

        $this->service = new StandingsService();

        $this->competition = new Competition();
    }

    public function testGetTeamRecordsForIncludesTeamsWhichHaveNotPlayedAMatch()
    {
        $randomTeam = $this->generateRandomTeam(123, 'Testing 123');

        $tm = new TeamMembership();
        $tm->setTeam($randomTeam);
        $this->competition->addTeamMembership($tm);

        $result = $this->service->getTeamRecordsFor($this->competition);
        $this->assertTrue($result->containsKey(123));
        $this->assertEquals(0, $result->get(123)->getTotalGames());
    }

    public function generateRandomTeam($id, $name = null)
    {
        $t = new Team();
        $t->setId($id);
        $t->setName($name ?: $this->faker->company);

        return $t;
    }

    public function populateCompetitionWithSampleData($competition, $data)
    {
        foreach ($data as $index => $game) {
            $homeTeam = $game['teams']['H']['team'];
            $awayTeam = $game['teams']['A']['team'];

            $match = new Match();
            $match->setId($index+1);

            $objHomeTeam = new MatchTeam();
            $objHomeTeam->setTeam($homeTeam);
            $match->setHomeTeam($objHomeTeam);

            $objAwayTeam = new MatchTeam();
            $objAwayTeam->setTeam($awayTeam);
            $match->setAwayTeam($objAwayTeam);

            $match->setStatus($game['status']);

            foreach ($game['teams'] as $teamSide => $teamData) {
                foreach ($teamData['scoring'] as $score) {
                    $e = new ScoreEvent();
                    $e->setType($score);
                    $e->setTeam($teamSide == 'H' ? $objHomeTeam : $objAwayTeam);
                    $match->addEvent($e);
                }
            }

            $competition->addMatch($match);
        }
    }

}
