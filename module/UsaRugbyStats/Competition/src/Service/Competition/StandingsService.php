<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\TeamRecord;
use Doctrine\Common\Collections\ArrayCollection;

class StandingsService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    public function getStandingsFor(Competition $competition)
    {
        $params = compact('competition');
        $results = $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $params);
        if ( $results->stopped() ) {
            return $results->last();
        }

        $teamRecords = $this->getTeamRecordsFor($competition)->toArray();
        $sortData = [];
        foreach ($teamRecords as $key=>$item) {
            $item instanceof TeamRecord;
            $sortData['points'][$key] = $item->getTotalPoints();
            $sortData['scoreDiff'][$key] = $item->getScoreDifferential();
            $sortData['totalGames'][$key] = $item->getTotalGames();
        }
        if (count($sortData) > 0) {
            array_multisort(
                $sortData['points'], SORT_DESC,
                $sortData['scoreDiff'], SORT_DESC,
                $sortData['totalGames'], SORT_ASC,
                $teamRecords
            );
        }

        $params['result'] = new ArrayCollection($teamRecords);
        $results = $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $params);

        return $params['result'];
    }

    public function getTeamRecordsFor(Competition $competition)
    {
        $coll = new ArrayCollection();

        if ( count($competition->getMatches()) == 0 ) {
            return $coll;
        }

        foreach ( $competition->getMatches() as $match ) {
            if ( ! $match->isComplete() ) {
                continue;
            }

            // Load the home team's record object
            $homeSide = $match->getHomeTeam();
            $homeTeam = $homeSide->getTeam()->getId();
            $homeTeamRecord = $coll->containsKey($homeTeam)
                ? $coll->get($homeTeam)
                : (new TeamRecord())->setCompetition($competition)->setTeam($homeSide->getTeam());

            // Load the away team's record object
            $awaySide = $match->getAwayTeam();
            $awayTeam = $awaySide->getTeam()->getId();
            $awayTeamRecord = $coll->containsKey($awayTeam)
                ? $coll->get($awayTeam)
                : (new TeamRecord())->setCompetition($competition)->setTeam($awaySide->getTeam());

            //=======================================================
            // Home Side
            //=======================================================

            $homeTeamRecord->addScoreInFavor($match->isHomeForfeit() ? 0 : $homeSide->getScore());
            $homeTeamRecord->addScoreAgainst($awaySide->getScore());

             // If we forfeited, erase the accumulated score in favor
            if ( $match->isHomeForfeit() ) {
                $homeTeamRecord->addForfeit();
            }

            // Handle game result
            switch ( $match->getWinningSide() ) {
                case 'H':
                    $homeTeamRecord->addHomeWin();
                    break;
                case 'A':
                    $homeTeamRecord->addHomeLoss();
                    // If score was within 7 we get a loss bonus
                    if ( ! $match->isHomeForfeit() && $homeSide->getScore() + 7 >= $awaySide->getScore() ) {
                        $homeTeamRecord->addLossBonus();
                    }
                    break;
                case 'D':
                    $homeTeamRecord->addHomeTie();
                    break;
            }

            // Count the number of tries recorded
            $triesInGame = $homeSide->getEvents()->filter(function ($e) {
                return $e->getDiscriminator() == 'score' && $e->isTry();
            })->count();

            // Add the number of tries to the team's record (if AF then min. # of tries we have is 4)
            $homeTeamRecord->addTries(max($triesInGame, $match->getStatus() == 'AF' ? 4 : 0));

            // If the opposite team forfeits or we get 4 or more tries in the game we get a bonus
            if ( $triesInGame >= 4 || ( $match->getStatus() == 'AF' && $triesInGame < 4) ) {
                $homeTeamRecord->addTryBonus();
            }

            //=======================================================
            // Away Side
            //=======================================================

            $awayTeamRecord->addScoreInFavor($match->isAwayForfeit() ? 0 : $awaySide->getScore());
            $awayTeamRecord->addScoreAgainst($homeSide->getScore());

            // If we forfeited, erase the accumulated score in favor
            if ( $match->isAwayForfeit() ) {
                $awayTeamRecord->addForfeit();
            }

            // Handle game result
            switch ( $match->getWinningSide() ) {
                case 'A':
                    $awayTeamRecord->addAwayWin();
                    break;
                case 'H':
                    $awayTeamRecord->addAwayLoss();
                    // If score was within 7 we get a loss bonus
                    if ( ! $match->isAwayForfeit() && $awaySide->getScore() + 7 >= $homeSide->getScore() ) {
                        $awayTeamRecord->addLossBonus();
                    }
                    break;
                case 'D':
                    $awayTeamRecord->addAwayTie();
                    break;
            }

            // Count the number of tries recorded
            $triesInGame = $awaySide->getEvents()->filter(function ($e) {
                return $e->getDiscriminator() == 'score' && $e->isTry();
            })->count();

            // Add the number of tries to the team's record (if HF then min. # of tries we have is 4)
            $awayTeamRecord->addTries(max($triesInGame, $match->getStatus() == 'HF' ? 4 : 0));

            // If the opposite team forfeits or we get 4 or more tries in the game we get a bonus
            if ( $triesInGame >= 4 || ( $match->getStatus() == 'HF' && $triesInGame < 4) ) {
                $awayTeamRecord->addTryBonus();
            }

            //=======================================================

            $coll->set($homeTeam, $homeTeamRecord);
            $coll->set($awayTeam, $awayTeamRecord);
        }

        return $coll;
    }

}
