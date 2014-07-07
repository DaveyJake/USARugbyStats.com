<?php
namespace UsaRugbyStats\Competition\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

/**
 * Team
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Team
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
     * The union this team is a member of
     *
     * @var Union
     */
    protected $union;

    /**
     * Email Address of the team
     *
     * @var string
     */
    protected $email;

    /**
     * Website Address of the team
     *
     * @var string
     */
    protected $website;

    /**
     * Facebook handle of the team
     *
     * @var string
     */
    protected $facebookHandle;

    /**
     * Twitter handle of the team
     *
     * @var string
     */
    protected $twitterHandle;

    /**
     * Team Memberships
     *
     * @var Collection
     */
    protected $teamMemberships;

    public function __construct()
    {
        $this->teamMemberships = new ArrayCollection();
    }

    public function __clone()
    {
        $this->teamMemberships = new ArrayCollection();
    }

    /**
     * Team Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Team Identifier
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
     * Team Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Team Name
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
     * Union this team is a member of
     *
     * @return Union
     */
    public function getUnion()
    {
        return $this->union;
    }

    /**
     * Set the union this team is a member of
     *
     * @param  Union $u
     * @return self
     */
    public function setUnion(Union $u = null)
    {
        $this->union = $u;

        return $this;
    }

    /**
     * @return the $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return the $website
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param string $url
     */
    public function setWebsite($url)
    {
        $this->website = $url;

        return $this;
    }

    /**
     * @return the $facebookHandle
     */
    public function getFacebookHandle()
    {
        return $this->facebookHandle;
    }

    /**
     * @param string $username
     */
    public function setFacebookHandle($username)
    {
        $this->facebookHandle = $username;

        return $this;
    }

    /**
     * @return the $twitterHandle
     */
    public function getTwitterHandle()
    {
        return $this->twitterHandle;
    }

    /**
     * @param string $username
     */
    public function setTwitterHandle($username)
    {
        $this->twitterHandle = $username;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeamMemberships()
    {
        return $this->teamMemberships;
    }

    /**
     * @param  Collection $comps
     * @return self
     */
    public function setTeamMemberships(Collection $comps)
    {
        $this->teamMemberships->clear();
        $this->addTeamMemberships($comps);

        return $this;
    }

    /**
     * @param  Collection $comps
     * @return self
     */
    public function addTeamMemberships(Collection $comps)
    {
        if (count($comps)) {
            foreach ($comps as $comp) {
                $this->addTeamMembership($comp);
            }
        }

        return $this;
    }

    /**
     * @param  TeamMembership $comp
     * @return self
     */
    public function addTeamMembership(TeamMembership $comp)
    {
        if ( ! $this->hasTeamMembership($comp) ) {
            $comp->setTeam($this);
            $this->teamMemberships->add($comp);
        }

        return $this;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function removeTeamMemberships(Collection $comps)
    {
        if (count($comps)) {
            foreach ($comps as $comp) {
                $this->removeTeamMembership($comp);
            }
        }

        return $this;
    }

    /**
     * @param  TeamMembership $comp
     * @return self
     */
    public function removeTeamMembership(TeamMembership $comp)
    {
        $comp->setTeam(null);
        $this->teamMemberships->removeElement($comp);

        return $this;
    }

    /**
     * @param  TeamMembership $comp
     * @return bool
     */
    public function hasTeamMembership(TeamMembership $comp)
    {
        return $this->teamMemberships->contains($comp);
    }

    /**
     * String representation of this Team object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

}
