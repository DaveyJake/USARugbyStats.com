<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;

/**
 * Competition Team Membership
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class TeamMembership
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * The competition this game is part of
     *
     * @var Competition
     */
    protected $competition;

    /**
     * Division
     *
     * @var Division
     */
    protected $division;

    /**
     * Home Team
     *
     * @var Team
     */
    protected $team;

    /**
     * Membership Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Membership Identifier
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
     * Team this membership applies to
     *
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set the team this membership applies to
     *
     * @param  Team $u
     * @return self
     */
    public function setTeam(Team $u = NULL)
    {
        $this->team = $u;

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
     * Division of the above competition this team is a member of
     *
     * @return Division
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set the division this team is a member of
     *
     * @param  Division $u
     * @return self
     */
    public function setDivision(Division $u = NULL)
    {
        $this->division = $u;
        $this->setCompetition(is_null($u) ? NULL : $u->getCompetition());

        return $this;
    }

    /**
     * String representation
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getTeam()->getName();
    }

}
