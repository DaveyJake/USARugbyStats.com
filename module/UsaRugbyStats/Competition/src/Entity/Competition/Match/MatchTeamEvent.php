<?php
namespace UsaRugbyStats\Competition\Entity\Competition\Match;

use UsaRugbyStats\Competition\Entity\Competition\Match;

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

    public function setMatch(Match $match = NULL)
    {
        $this->match = $match;

        return $this;
    }

    public function getTeam()
    {
        return $this->team;
    }

    public function setTeam(MatchTeam $team = NULL)
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

    abstract public function getDiscriminator();
}