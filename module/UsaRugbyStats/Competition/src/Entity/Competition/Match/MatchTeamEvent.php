<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match;

use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Application\Entity\AccountInterface;

/**
 * Match <-> Match Event Association Entity
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
abstract class MatchTeamEvent
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var Match
     */
    protected $match;

    /**
     * @var MatchTeam
     */
    protected $team;

    /**
     * @var string
     */
    protected $minute;

    /**
     * Running Score
     *
     * @var unknown
     */
    protected $runningScore = array('H' => 0, 'A' => 0);

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getMatch()
    {
        return $this->match;
    }

    public function setMatch(Match $match = null)
    {
        $this->match = $match;

        return $this;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam(MatchTeam $team = null)
    {
        $this->team = $team;

        return $this;
    }

    public function getMinute()
    {
        return $this->minute;
    }

    public function setMinute($minute)
    {
        $this->minute = (int) $minute;

        return $this;
    }

    public function getEvent()
    {
        return $this->getDiscriminator();
    }

    public function getRunningScore()
    {
        if (!isset($this->runningScore['H'])) {
            $this->runningScore['H'] = 0;
        }
        if (!isset($this->runningScore['A'])) {
            $this->runningScore['A'] = 0;
        }

        return $this->runningScore;
    }

    public function setRunningScore(array $sides)
    {
        if ( array_keys($sides) == array('H','A') ) {
            $this->setRunningScoreForSide('H', $sides['H']);
            $this->setRunningScoreForSide('A', $sides['A']);
        }

        return $this;
    }

    public function setRunningScoreForSide($side, $score)
    {
        if ( ! in_array($side, array('H','A')) ) {
            return $this;
        }
        if ( is_numeric($score) && $score >= 0 ) {
            $this->runningScore[$side] = $score;
        }

        return $this;
    }

    abstract public function hasPlayer(AccountInterface $player);

    abstract public function getDiscriminator();
}
