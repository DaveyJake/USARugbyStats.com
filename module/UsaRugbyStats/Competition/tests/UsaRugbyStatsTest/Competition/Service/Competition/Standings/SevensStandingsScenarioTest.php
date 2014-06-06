<?php
namespace UsaRugbyStatsTest\Competition\Service\Competition\Standings;

use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\TeamRecord;

class SevensStandingsScenarioTest extends AbstractStandingsServiceTest
{
    public function setUp()
    {
        parent::setUp();
        $this->competition->setVariant('7s');
    }

    public function testFullScenario()
    {
        $expectedResult = [
            'ATL' => [ 'Rank' => 1, 'GP' => 3, 'W' => 3, 'L' => 0, 'T' => 0, 'PF' => 81, 'PA' => 48, 'PD' =>  33, 'TR' => 13, 'FFT' => 0, 'PTS' => 9 ],
            'DB'  => [ 'Rank' => 2, 'GP' => 3, 'W' => 2, 'L' => 1, 'T' => 0, 'PF' => 78, 'PA' => 45, 'PD' =>  33, 'TR' => 12, 'FFT' => 0, 'PTS' => 7 ],
            'MIA' => [ 'Rank' => 3, 'GP' => 3, 'W' => 1, 'L' => 2, 'T' => 0, 'PF' => 38, 'PA' => 78, 'PD' => -40, 'TR' =>  6, 'FFT' => 0, 'PTS' => 5 ],
            'NAS' => [ 'Rank' => 4, 'GP' => 3, 'W' => 0, 'L' => 3, 'T' => 0, 'PF' => 43, 'PA' => 69, 'PD' => -26, 'TR' =>  7, 'FFT' => 0, 'PTS' => 3 ],
        ];

        $games = $this->getSampleCompetitionData();
        $this->populateCompetitionWithSampleData($this->competition, $games);

        $result = $this->service->getTeamRecordsFor($this->competition);
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $result);
        $this->assertCount(4, $result);

        foreach ($result as $teamRecord) {
            $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\TeamRecord', $teamRecord);
            $teamRecord instanceof TeamRecord;
            $teamName = $teamRecord->getTeam()->getName();

            $expected = $expectedResult[$teamName];
            $this->assertEquals($expected['GP'], $teamRecord->getTotalGames(), "{$teamName} Games Played incorrect");
            $this->assertEquals($expected['W'], $teamRecord->getTotalWins(), "{$teamName} Wins incorrect");
            $this->assertEquals($expected['L'], $teamRecord->getTotalLosses(), "{$teamName} Losses incorrect");
            $this->assertEquals($expected['T'], $teamRecord->getTotalTies(), "{$teamName} Ties incorrect");
            $this->assertEquals($expected['PF'], $teamRecord->getScoreInFavor(), "{$teamName} ScoreInFavor incorrect");
            $this->assertEquals($expected['PA'], $teamRecord->getScoreAgainst(), "{$teamName} ScoreAgainst incorrect");
            $this->assertEquals($expected['PD'], $teamRecord->getScoreDifferential(), "{$teamName} ScoreDifferential incorrect");
            $this->assertEquals($expected['TR'], $teamRecord->getTotalTries(), "{$teamName} Tries incorrect");
            $this->assertEquals($expected['FFT'], $teamRecord->getForfeits(), "{$teamName} Forfeits incorrect");
            $this->assertEquals($expected['PTS'], $teamRecord->getTotalPoints(), "{$teamName} Points incorrect");
        }
    }

    /**
     * @dataProvider providerTestIndividualGames
     */
    public function testIndividualGames($matchData, $matchResult)
    {
        $this->populateCompetitionWithSampleData($this->competition, [$matchData]);
        $result = $this->service->getTeamRecordsFor($this->competition);
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $result);
        $this->assertCount(2, $result);

        $match = $this->competition->getMatches()->current();
        foreach ($matchResult as $teamType => $expectedResult) {
            $teamid = $match->getTeam($teamType)->getTeam()->getId();
            $actualResult = $result->get($teamid);
            $actualResult instanceof TeamRecord;

            $this->assertEquals($expectedResult['Score'], $match->getTeam($teamType)->getScore());
            $this->assertEquals($expectedResult['Score'], $actualResult->getScoreInFavor());
            $this->assertEquals($matchResult[$teamType == 'H' ? 'A' : 'H']['Score'], $actualResult->getScoreAgainst());

            $this->assertEquals(1, $actualResult->getTotalGames());
            if ( $match->getWinningSide() == 'H' && $teamType == 'H' ) {
                $this->assertEquals(1, $actualResult->getHomeWins());
                $this->assertEquals(1, $actualResult->getTotalWins());
                $this->assertEquals(0, $actualResult->getHomeLosses());
                $this->assertEquals(0, $actualResult->getTotalLosses());
            }
            if ( $match->getWinningSide() == 'A' && $teamType == 'H' ) {
                $this->assertEquals(0, $actualResult->getHomeWins());
                $this->assertEquals(0, $actualResult->getTotalWins());
                $this->assertEquals(1, $actualResult->getHomeLosses());
                $this->assertEquals(1, $actualResult->getTotalLosses());
            }
            if ( $match->getWinningSide() == 'A' && $teamType == 'A' ) {
                $this->assertEquals(1, $actualResult->getAwayWins());
                $this->assertEquals(1, $actualResult->getTotalWins());
                $this->assertEquals(0, $actualResult->getAwayLosses());
                $this->assertEquals(0, $actualResult->getTotalLosses());

            }
            if ( $match->getWinningSide() == 'H' && $teamType == 'A' ) {
                $this->assertEquals(0, $actualResult->getAwayWins());
                $this->assertEquals(0, $actualResult->getTotalWins());
                $this->assertEquals(1, $actualResult->getAwayLosses());
                $this->assertEquals(1, $actualResult->getTotalLosses());
            }

            $this->assertEquals($expectedResult['Tries'], $actualResult->getTotalTries());
            $this->assertEquals($expectedResult['Points'], $actualResult->getTotalPoints());
            $this->assertEquals($expectedResult['FFT'], $actualResult->getForfeits());
        }

    }

    public function providerTestIndividualGames()
    {
        $data = $this->getSampleCompetitionData();

        return [
            [
                $data[0],
                [
                    'H' => [ 'Score' => 22, 'Tries' => 4, 'Points' => 3, 'FFT' => 0 ],
                    'A' => [ 'Score' => 19, 'Tries' => 3, 'Points' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[1],
                [
                    'H' => [ 'Score' => 28, 'Tries' => 4, 'Points' => 3, 'FFT' => 0 ],
                    'A' => [ 'Score' => 12, 'Tries' => 2, 'Points' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[2],
                [
                    'H' => [ 'Score' => 33, 'Tries' => 5, 'Points' => 3, 'FFT' => 0 ],
                    'A' => [ 'Score' =>  5, 'Tries' => 1, 'Points' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[3],
                [
                    'H' => [ 'Score' => 26, 'Tries' => 4, 'Points' => 3, 'FFT' => 0 ],
                    'A' => [ 'Score' =>  7, 'Tries' => 1, 'Points' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[4],
                [
                    'H' => [ 'Score' => 26, 'Tries' => 4, 'Points' => 3, 'FFT' => 0 ],
                    'A' => [ 'Score' => 24, 'Tries' => 4, 'Points' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[5],
                [
                    'H' => [ 'Score' => 21, 'Tries' => 3, 'Points' => 3, 'FFT' => 0 ],
                    'A' => [ 'Score' => 17, 'Tries' => 3, 'Points' => 1, 'FFT' => 0 ],
                ],
            ],
        ];
    }

    public function getSampleTeams()
    {
        return [
            'ATL' => $this->generateRandomTeam(1, 'ATL'),
            'DB'  => $this->generateRandomTeam(2, 'DB'),
            'MIA' => $this->generateRandomTeam(3, 'MIA'),
            'NAS' => $this->generateRandomTeam(4, 'NAS'),
        ];
    }

    public function getSampleCompetitionData()
    {
        $teams = $this->getSampleTeams();

        return [
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['ATL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV' ],  // 4*5 + 1*2 = 22 pts
                    ],
                    'A' => [
                        'team' => $teams['NAS'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV' ],  // 3*5 + 2*2 = 19 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['DB'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV', 'CV' ],  // 4*5 + 4*2 = 28 pts
                    ],
                    'A' => [
                        'team' => $teams['MIA'],
                        'scoring' => [ 'TR', 'TR', 'CV' ],  // 2*5 + 1*2 = 12 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['ATL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV', 'CV' ],  // 5*5 + 4*2 = 33 pts
                    ],
                    'A' => [
                        'team' => $teams['MIA'],
                        'scoring' => [ 'TR' ],  // 1*5 = 5 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['DB'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 4*5 + 3*2 = 26 pts
                    ],
                    'A' => [
                        'team' => $teams['NAS'],
                        'scoring' => [ 'TR', 'CV' ],  // 1*5 + 1*2 = 7 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['ATL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 4*5 + 3*2 = 26 pts
                    ],
                    'A' => [
                        'team' => $teams['DB'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', ],  // 4*5 + 2*2 = 24 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['MIA'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 3*5 + 3*2 = 21 pts
                    ],
                    'A' => [
                        'team' => $teams['NAS'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV' ],  // 3*5 + 1*2 = 17 pts
                    ],
                ],
            ],
        ];
    }

}
