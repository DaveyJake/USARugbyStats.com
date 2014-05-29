<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;

/**
 * Team Record in Competition
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class TeamRecord
{

    /**
     * @var Competition
     */
    protected $competition;

    /**
     * @var Team
     */
    protected $team;

    protected $homeWins = 0;
    protected $homeLosses = 0;
    protected $homeTies = 0;
    protected $awayWins = 0;
    protected $awayLosses = 0;
    protected $awayTies = 0;
    protected $lossBonuses = 0;
    protected $forfeits = 0;
    protected $totalGames = 0;
    protected $scoreInFavor = 0;
    protected $scoreAgainst = 0;
    protected $tryBonuses = 0;

    public function getTotalPoints()
    {
        $amounts = $this->getCompetition()->getPointStructure();

        //@TODO
        return array_sum([
            $this->getHomeWins() * $amounts['win'],
            $this->getHomeLosses() * $amounts['loss'],
            $this->getHomeTies() * $amounts['tie'],
            $this->getForfeits() * $amounts['forfeit'],
            $this->getTryBonuses() * $amounts['try_bonus'],
            $this->getLossBonuses() * $amounts['loss_bonus'],
        ]);
    }

    public function getTotalWins()
    {
        return $this->getHomeWins() + $this->getAwayWins();
    }

    public function getTotalLosses()
    {
        return $this->getHomeLosses() + $this->getAwayLosses();
    }

    public function getTotalTies()
    {
        return $this->getHomeTies() + $this->getAwayTies();
    }

    public function getWinPercentage()
    {
        return number_format(
            $this->getTotalGames() > 0
                ? $this->getTotalWins() / $this->getTotalLosses()
                : 0,
            3
        );
    }

    public function getHomeWins()
    {
        return $this->homeWins;
    }

    public function getHomeLosses()
    {
        return $this->homeLosses;
    }

    public function getHomeTies()
    {
        return $this->homeTies;
    }

    public function getAwayWins()
    {
        return $this->awayWins;
    }

    public function getAwayLosses()
    {
        return $this->awayLosses;
    }

    public function getAwayTies()
    {
        return $this->awayTies;
    }

    public function getLossBonuses()
    {
        return $this->lossBonuses;
    }

    public function getForfeits()
    {
        return $this->forfeits;
    }

    public function getTotalGames()
    {
        return $this->totalGames;
    }

    public function getScoreInFavor()
    {
        return $this->scoreInFavor;
    }

    public function getScoreAgainst()
    {
        return $this->scoreAgainst;
    }

    public function getScoreDifferential()
    {
        return $this->getScoreInFavor() - $this->getScoreAgainst();
    }

    public function getTryBonuses()
    {
        return $this->tryBonuses;
    }

    public function addHomeWin()
    {
        $this->homeWins++;
        $this->totalGames++;

        return $this;
    }

    public function addHomeLoss()
    {
        $this->homeLosses++;
        $this->totalGames++;

        return $this;
    }

    public function addHomeTie()
    {
        $this->homeTies++;
        $this->totalGames++;

        return $this;
    }

    public function addAwayWin()
    {
        $this->awayWins++;
        $this->totalGames++;

        return $this;
    }

    public function addAwayLoss()
    {
        $this->awayLosses++;
        $this->totalGames++;

        return $this;
    }

    public function addAwayTie()
    {
        $this->awayTies++;
        $this->totalGames++;

        return $this;
    }

    public function addLossBonus()
    {
        $this->lossBonuses++;

        return $this;
    }

    public function addForfeit()
    {
        $this->forfeits++;
        $this->totalGames++;

        return $this;
    }

    public function setScoreInFavor($pts)
    {
        $this->scoreInFavor = 0;

        return $this;
    }

    public function addScoreInFavor($pts)
    {
        $this->scoreInFavor += $pts;

        return $this;
    }

    public function addScoreAgainst($pts)
    {
        $this->scoreAgainst += $pts;

        return $this;
    }

    public function addTryBonus()
    {
        $this->tryBonuses++;

        return $this;
    }

    public function setCompetition(Competition $comp)
    {
        $this->competition = $comp;

        return $this;
    }

    public function getCompetition()
    {
        return $this->competition;
    }

    public function setTeam(Team $t)
    {
        $this->team = $t;

        return $this;
    }

    public function getTeam()
    {
        return $this->team;
    }

}
