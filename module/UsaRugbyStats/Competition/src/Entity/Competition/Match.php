<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;

/**
 * Competition Match
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Match
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $description;

    /**
     * The competition this match is part of
     *
     * @var Competition
     */
    protected $competition;

    /**
     * Home Team
     *
     * @var Team
     */
    protected $homeTeam;

    /**
     * Away Team
     *
     * @var Team
     */
    protected $awayTeam;

    /**
     * Match Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Match Identifier
     *
     * @param  integer $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Match Description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Match Description
     *
     * @param  string $desc
     * @return self
     */
    public function setDescription($desc)
    {
        $this->description = $desc;

        return $this;
    }

    /**
     * Competition this team is a member of
     *
     * @return Competition
     */
    public function getCompetition()
    {
        return $this->competition;
    }

    /**
     * Set the competition this team is a member of
     *
     * @param  Competition $u
     * @return self
     */
    public function setCompetition(Competition $u = NULL)
    {
        $this->competition = $u;

        return $this;
    }

    /**
     * Home team for this match
     *
     * @return HomeTeam
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set the home team for this match
     *
     * @param  Team $u
     * @return self
     */
    public function setHomeTeam(Team $u)
    {
        $this->homeTeam = $u;

        return $this;
    }

    /**
     * Away team for this match
     *
     * @return AwayTeam
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set the away team for this match
     *
     * @param  Team $u
     * @return self
     */
    public function setAwayTeam(Team $u)
    {
        $this->awayTeam = $u;

        return $this;
    }

    /**
     * String representation of this Match object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getHomeTeam()->getName() . ' v. ' . $this->getAwayTeam()->getName();
    }

}
