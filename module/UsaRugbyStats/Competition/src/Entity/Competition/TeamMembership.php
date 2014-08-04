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
     * Sort Key (the team's name)
     *
     * @var string
     */
    protected $sortKey;

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
    public function setTeam(Team $u = null)
    {
        $this->team = $u;
        $this->sortKey = is_null($u) ? $u : $u->getName();

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
    public function setCompetition(Competition $u = null)
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
    public function setDivision(Division $u = null)
    {
        $this->division = $u;
        $this->setCompetition(is_null($u) ? null : $u->getCompetition());

        return $this;
    }

    /**
     * @return string
     */
    public function getSortKey()
    {
        $team = $this->getTeam();
        if ( !is_null($team) && $this->sortKey != $team->getName() ) {
            $this->sortKey = $this->getTeam()->getName();
        }

        return $this->sortKey;
    }

    /**
     * @param string $sortKey
     */
    public function setSortKey($sortKey)
    {
        $this->sortKey = $sortKey;

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
