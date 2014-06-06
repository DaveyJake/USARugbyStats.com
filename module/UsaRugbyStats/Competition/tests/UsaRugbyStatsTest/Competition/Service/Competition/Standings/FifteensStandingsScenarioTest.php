<?php
namespace UsaRugbyStatsTest\Competition\Service\Competition\Standings;

use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\TeamRecord;

class FifteensStandingsScenarioTest extends AbstractStandingsServiceTest
{
    public function setUp()
    {
        parent::setUp();
        $this->competition->setVariant('15s');
    }

    public function testFullScenario()
    {
        $expectedResult = [
            'COL' => [ 'Rank' => 1, 'GP' => 6, 'W' => 6, 'L' => 0, 'T' => 0, 'PF' => 205, 'PA' =>  84, 'PD' =>  121, 'TR' => 28, '4T' => 4, '-7' => 0, 'FFT' => 0, 'PTS' => 28 ],
            'GRE' => [ 'Rank' => 2, 'GP' => 6, 'W' => 4, 'L' => 2, 'T' => 0, 'PF' => 145, 'PA' => 105, 'PD' =>   40, 'TR' => 21, '4T' => 2, '-7' => 1, 'FFT' => 0, 'PTS' => 19 ],
            'AUG' => [ 'Rank' => 3, 'GP' => 6, 'W' => 2, 'L' => 2, 'T' => 2, 'PF' => 144, 'PA' => 130, 'PD' =>   14, 'TR' => 22, '4T' => 4, '-7' => 2, 'FFT' => 0, 'PTS' => 18 ],
            'CHA' => [ 'Rank' => 4, 'GP' => 6, 'W' => 3, 'L' => 3, 'T' => 0, 'PF' => 137, 'PA' => 134, 'PD' =>    3, 'TR' => 21, '4T' => 3, '-7' => 2, 'FFT' => 0, 'PTS' => 17 ],
            'CF'  => [ 'Rank' => 5, 'GP' => 6, 'W' => 2, 'L' => 2, 'T' => 2, 'PF' => 121, 'PA' => 140, 'PD' =>  -19, 'TR' => 16, '4T' => 2, '-7' => 0, 'FFT' => 0, 'PTS' => 14 ],
            'CLT' => [ 'Rank' => 6, 'GP' => 6, 'W' => 1, 'L' => 4, 'T' => 1, 'PF' => 110, 'PA' => 147, 'PD' =>  -37, 'TR' => 15, '4T' => 0, '-7' => 2, 'FFT' => 0, 'PTS' =>  8 ],
            'ASH' => [ 'Rank' => 7, 'GP' => 6, 'W' => 0, 'L' => 5, 'T' => 1, 'PF' =>  84, 'PA' => 206, 'PD' => -122, 'TR' => 11, '4T' => 1, '-7' => 0, 'FFT' => 0, 'PTS' =>  3 ],
        ];

        $games = $this->getSampleCompetitionData();
        $this->populateCompetitionWithSampleData($this->competition, $games);

        $result = $this->service->getTeamRecordsFor($this->competition);
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $result);
        $this->assertCount(7, $result);

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
            $this->assertEquals($expected['4T'], $teamRecord->getTryBonuses(), "{$teamName} TryBonuses incorrect");
            $this->assertEquals($expected['-7'], $teamRecord->getLossBonuses(), "{$teamName} LossBonuses incorrect");
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
            $this->assertEquals($expectedResult['4T'], $actualResult->getTryBonuses());
            $this->assertEquals($expectedResult['-7'], $actualResult->getLossBonuses());
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
                    'H' => [ 'Score' => 20, 'Tries' => 4, 'Points' => 2, '4T' => 1, '-7' => 1, 'FFT' => 0 ],
                    'A' => [ 'Score' => 27, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[1],
                [
                    'H' => [ 'Score' => 15, 'Tries' => 3, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 40, 'Tries' => 7, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[2],
                [
                    'H' => [ 'Score' => 22, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 20, 'Tries' => 3, 'Points' => 1, '4T' => 0, '-7' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[3],
                [
                    'H' => [ 'Score' => 15, 'Tries' => 2, 'Points' => 4, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' =>  5, 'Tries' => 1, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[4],
                [
                    'H' => [ 'Score' => 24, 'Tries' => 3, 'Points' => 4, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' =>  3, 'Tries' => 0, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[5],
                [
                    'H' => [ 'Score' => 10, 'Tries' => 1, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 29, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[6],
                [
                    'H' => [ 'Score' => 12, 'Tries' => 1, 'Points' => 2, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 12, 'Tries' => 2, 'Points' => 2, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[7],
                [
                    'H' => [ 'Score' => 24, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 21, 'Tries' => 3, 'Points' => 1, '4T' => 0, '-7' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[8],
                [
                    'H' => [ 'Score' =>  8, 'Tries' => 1, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 26, 'Tries' => 3, 'Points' => 4, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[9],
                [
                    'H' => [ 'Score' => 17, 'Tries' => 2, 'Points' => 4, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 15, 'Tries' => 2, 'Points' => 1, '4T' => 0, '-7' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[10],
                [
                    'H' => [ 'Score' => 36, 'Tries' => 6, 'Points' => 1, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 50, 'Tries' => 7, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[11],
                [
                    'H' => [ 'Score' => 21, 'Tries' => 3, 'Points' => 2, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 21, 'Tries' => 2, 'Points' => 2, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[12],
                [
                    'H' => [ 'Score' => 17, 'Tries' => 2, 'Points' => 1, '4T' => 0, '-7' => 1, 'FFT' => 0 ],
                    'A' => [ 'Score' => 20, 'Tries' => 3, 'Points' => 4, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[13],
                [
                    'H' => [ 'Score' => 19, 'Tries' => 3, 'Points' => 4, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 10, 'Tries' => 1, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[14],
                [
                    'H' => [ 'Score' => 33, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 20, 'Tries' => 3, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[15],
                [
                    'H' => [ 'Score' =>  6, 'Tries' => 0, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 60, 'Tries' => 8, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[16],
                [
                    'H' => [ 'Score' => 24, 'Tries' => 4, 'Points' => 3, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 24, 'Tries' => 3, 'Points' => 2, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[17],
                [
                    'H' => [ 'Score' => 22, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 19, 'Tries' => 3, 'Points' => 1, '4T' => 0, '-7' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[18],
                [
                    'H' => [ 'Score' => 24, 'Tries' => 3, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 48, 'Tries' => 6, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
            [
                $data[19],
                [
                    'H' => [ 'Score' => 31, 'Tries' => 4, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 30, 'Tries' => 4, 'Points' => 2, '4T' => 1, '-7' => 1, 'FFT' => 0 ],
                ],
            ],
            [
                $data[20],
                [
                    'H' => [ 'Score' => 36, 'Tries' => 6, 'Points' => 5, '4T' => 1, '-7' => 0, 'FFT' => 0 ],
                    'A' => [ 'Score' => 10, 'Tries' => 1, 'Points' => 0, '4T' => 0, '-7' => 0, 'FFT' => 0 ],
                ],
            ],
        ];
    }

    public function getSampleTeams()
    {
        return [
            'COL' => $this->generateRandomTeam(1, 'COL'),
            'GRE' => $this->generateRandomTeam(2, 'GRE'),
            'AUG' => $this->generateRandomTeam(3, 'AUG'),
            'CHA' => $this->generateRandomTeam(4, 'CHA'),
            'CF'  => $this->generateRandomTeam(5, 'CF'),
            'CLT' => $this->generateRandomTeam(6, 'CLT'),
            'ASH' => $this->generateRandomTeam(7, 'ASH'),
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
                        'team' => $teams['AUG'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR' ],  // 4*5 = 20 pts
                    ],
                    'A' => [
                        'team' => $teams['GRE'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'DG' ],  // 4*5 + 2*2 + 1*3 = 27 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CF'],
                        'scoring' => [ 'TR', 'TR', 'TR' ],  // 3*5 = 15 pts
                    ],
                    'A' => [
                        'team' => $teams['COL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'CV', 'DG' ],  // 7*5 + 1*2 + 1*3 = 40 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CHA'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV' ],  // 4*5 + 1*2 = 22 pts
                    ],
                    'A' => [
                        'team' => $teams['CLT'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'DG' ],  // 3*5 + 1*2 + 1*3 = 20 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['COL'],
                        'scoring' => [ 'TR', 'TR', 'CV', 'DG' ],  // 2*5 + 1*2 + 1*3 = 15 pts
                    ],
                    'A' => [
                        'team' => $teams['CHA'],
                        'scoring' => [ 'TR' ],  // 1*5 = 5 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['GRE'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'DG', 'DG', 'DG' ],  // 3*5 + 3*3 = 24 pts
                    ],
                    'A' => [
                        'team' => $teams['CF'],
                        'scoring' => [ 'DG' ],  // 1*3 = 3 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['ASH'],
                        'scoring' => [ 'TR', 'CV', 'DG' ],  // 1*5 + 1*2 + 1*3 = 10 pts
                    ],
                    'A' => [
                        'team' => $teams['AUG'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'DG', 'DG', 'DG' ],  // 4*5 + 3*3 = 29 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CF'],
                        'scoring' => [ 'TR', 'CV', 'CV', 'DG' ],  // 1*5 + 2*2 + 1*3 = 12 pts
                    ],
                    'A' => [
                        'team' => $teams['ASH'],
                        'scoring' => [ 'TR', 'TR', 'CV' ],  // 2*5 + 1*2 = 12 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CHA'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV' ],  // 4*5 + 2*2 = 24 pts
                    ],
                    'A' => [
                        'team' => $teams['GRE'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 3*5 + 3*2 = 21 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CLT'],
                        'scoring' => [ 'TR', 'DG' ],  // 1*5 + 1*2 = 8 pts
                    ],
                    'A' => [
                        'team' => $teams['COL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'DG', 'DG', 'DG' ],  // 3*5 + 1*2 + 2*3 = 21 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['GRE'],
                        'scoring' => [ 'TR', 'TR', 'CV', 'CV', 'DG' ],  // 2*5 + 2*2 + 1*3 = 17 pts
                    ],
                    'A' => [
                        'team' => $teams['CLT'],
                        'scoring' => [ 'TR', 'TR', 'CV', 'DG' ],  // 2*5 + 1*2 + 1*3 = 15 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['ASH'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 6*5 + 3*2 = 36 pts
                    ],
                    'A' => [
                        'team' => $teams['CHA'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'DG', 'DG', 'DG', 'DG', 'DG' ],  // 7*5 + 5*3 = 50 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['AUG'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 3*5 + 3*2 = 21 pts
                    ],
                    'A' => [
                        'team' => $teams['CF'],
                        'scoring' => [ 'TR', 'TR', 'CV', 'DG', 'DG', 'DG' ],  // 2*5 + 1*2 + 3*3 = 21 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CHA'],
                        'scoring' => [ 'TR', 'TR', 'CV', 'CV', 'DG' ],  // 2*5 + 2*2 + 1*3 = 17 pts
                    ],
                    'A' => [
                        'team' => $teams['AUG'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'DG' ],  // 3*5 + 1*2 + 1*3 = 20 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CLT'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV' ],  // 3*5 + 2*2 = 19 pts
                    ],
                    'A' => [
                        'team' => $teams['ASH'],
                        'scoring' => [ 'TR', 'CV', 'DG' ],  // 1*5 + 1*2 + 1*3 = 10 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['COL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'DG', 'DG', 'DG' ],  // 4*5 + 2*2 + 3*3 = 33 pts
                    ],
                    'A' => [
                        'team' => $teams['GRE'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'DG' ],  // 3*5 + 1*2 + 1*3 = 20 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['ASH'],
                        'scoring' => [ 'DG', 'DG' ],  // 3*2 = 6 pts
                    ],
                    'A' => [
                        'team' => $teams['COL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV', 'CV', 'DG', 'DG', 'DG', 'DG' ],  // 8*5 + 4*2 + 4*3 = 60 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['AUG'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV' ],  // 4*5 + 2*2 = 24 pts
                    ],
                    'A' => [
                        'team' => $teams['CLT'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV', 'CV', 'DG' ],  // 3*5 + 3*2 + 1*3 = 24 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CF'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV' ],  // 4*5 + 1*2 = 22 pts
                    ],
                    'A' => [
                        'team' => $teams['CHA'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'CV', 'CV' ],  // 3*5 + 2*2 = 19 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['CLT'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'DG', 'DG', 'DG' ],  // 3*5 + 3*3 = 24 pts
                    ],
                    'A' => [
                        'team' => $teams['CF'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV', 'CV', 'CV', 'CV', 'DG', 'DG' ],  // 6*5 + 6*2 + 2*3 = 48 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['COL'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'DG', 'DG', 'DG' ],  // 4*5 + 1*2 + 3*3 = 31 pts
                    ],
                    'A' => [
                        'team' => $teams['AUG'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'DG', 'DG' ],  // 4*5 + 2*2 + 2*3 = 30 pts
                    ],
                ],
            ],
            [
                'status' => Match::STATUS_FINISHED,
                'teams' => [
                    'H' => [
                        'team' => $teams['GRE'],
                        'scoring' => [ 'TR', 'TR', 'TR', 'TR', 'TR', 'TR', 'CV', 'CV', 'CV' ],  // 6*5 + 3*2 = 36 pts
                    ],
                    'A' => [
                        'team' => $teams['ASH'],
                        'scoring' => [ 'TR', 'CV', 'DG' ],  // 1*5 + 1*2 + 1*3 = 10 pts
                    ],
                ],
            ],
        ];
    }

}
