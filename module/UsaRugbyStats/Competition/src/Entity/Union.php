<?php
namespace UsaRugbyStats\Competition\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Union
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Union
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
     * Teams which are a member of this union
     *
     * @var Collection
     */
    protected $teams;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function __clone()
    {
        $this->teams = new ArrayCollection();
    }

    /**
     * Union Identifier
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Union Identifier
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
     * Union Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Union Name
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
     * @return Collection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function setTeams(Collection $teams)
    {
        $this->teams->clear();
        $this->addTeams($teams);

        return $this;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function addTeams(Collection $teams)
    {
        if (count($teams)) {
            foreach ($teams as $ra) {
                $this->addTeam($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Team $role
     * @return self
     */
    public function addTeam(Team $ra)
    {
        if ( ! $this->hasTeam($ra) ) {
            $ra->setUnion($this);
            $this->teams->add($ra);
        }

        return $this;
    }

    /**
     * @param  Collection $teams
     * @return self
     */
    public function removeTeams(Collection $teams)
    {
        if (count($teams)) {
            foreach ($teams as $ra) {
                $this->removeTeam($ra);
            }
        }

        return $this;
    }

    /**
     * @param  Team $role
     * @return self
     */
    public function removeTeam(Team $ra)
    {
        $ra->setUnion(NULL);
        $this->teams->removeElement($ra);

        return $this;
    }

    /**
     * @param  Team $role
     * @return bool
     */
    public function hasTeam(Team $ra)
    {
        return $this->teams->contains($ra);
    }

    /**
     * String representation of this Union object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

}
