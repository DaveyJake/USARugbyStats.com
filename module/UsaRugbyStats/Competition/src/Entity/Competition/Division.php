<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;

/**
 * Competition Division
 * 
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Division
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
     * The competition this division is a member of
     * 
     * @var Competition
     */
    protected $competition;
    
    /**
     * Teams which are competing in this division
     *  
     * @var Collection
     */
    protected $teams;
    
    
    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }
    
    /**
     * Division Identifier 
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Division Identifier
     * 
     * @param integer $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Division Name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Division Name
     *
     * @param string $name
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
     * @param Competition $u
     * @return self
     */
    public function setCompetition(Competition $u)
    {
        $this->competition = $u;
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
     * @param Collection $teams
     * @return self
     */
    public function setTeams(Collection $teams)
    {
        $this->teams->clear();
        $this->addTeams($teams);
        return $this;
    }
    
    /**
     * @param Collection $teams
     * @return self
     */
    public function addTeams(Collection $teams)
    {
        if(count($teams)){
            foreach($teams as $ra){
                $this->addTeam($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param Team $role
     * @return self
     */
    public function addTeam(Team $ra)
    {
        $this->teams->add($ra);
        return $this;
    }
    
    /**
     * @param Collection $teams
     * @return self
     */
    public function removeTeams(Collection $teams)
    {
        if(count($teams)){
            foreach($teams as $ra){
                $this->removeTeam($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param Team $role
     * @return self
     */
    public function removeTeam(Team $ra)
    {
        $this->teams->removeElement($ra);
        return $this;
    }
    
    /**
     * @param Team $role
     * @return bool
     */
    public function hasTeam(Team $ra)
    {
        return $this->teams->contains($ra);
    }

    /**
     * String representation of this Division object
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
