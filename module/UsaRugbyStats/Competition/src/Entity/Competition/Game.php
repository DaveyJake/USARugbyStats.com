<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;

/**
 * Competition Game
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Game
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * The competition this game is part of
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
     * Game Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Game Identifier
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
     * Game Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Game Name
     *
     * @param  string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * Home team for this game
     *
     * @return HomeTeam
     */
    public function getHomeTeam()
    {
        return $this->homeTeam;
    }

    /**
     * Set the home team for this game
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
     * Away team for this game
     *
     * @return AwayTeam
     */
    public function getAwayTeam()
    {
        return $this->awayTeam;
    }

    /**
     * Set the away team for this game
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
     * String representation of this Game object
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
