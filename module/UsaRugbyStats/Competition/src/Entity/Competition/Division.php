<?php
namespace UsaRugbyStats\Competition\Entity\Competition;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

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
     * Team Memberships
     * 
     * @var Collection
     */
    protected $teamMemberships;

    
    public function __construct()
    {
        $this->teamMemberships = new ArrayCollection();
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
    public function setCompetition(Competition $u = NULL)
    {
        $this->competition = $u;
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
     * @param Collection $teamMemberships
     * @return self
     */
    public function setTeamMemberships(Collection $teamMemberships)
    {
        $this->teamMemberships->clear();
        $this->addTeamMemberships($teamMemberships);
        return $this;
    }
    
    /**
     * @param Collection $teamMemberships
     * @return self
     */
    public function addTeamMemberships(Collection $teamMemberships)
    {
        if(count($teamMemberships)){
            foreach($teamMemberships as $ra){
                $this->addTeamMembership($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param TeamMembership $obj
     * @return self
     */
    public function addTeamMembership(TeamMembership $obj)
    {
        if ( ! $this->hasTeamMembership($obj) ) {
            $obj->setDivision($this);
            $obj->setCompetition($this->getCompetition());
            $this->teamMemberships->add($obj);
        }
        return $this;
    }
    
    /**
     * @param Collection $teamMemberships
     * @return self
     */
    public function removeTeamMemberships(Collection $teamMemberships)
    {
        if(count($teamMemberships)){
            foreach($teamMemberships as $ra){
                $this->removeTeamMembership($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param TeamMembership $obj
     * @return self
     */
    public function removeTeamMembership(TeamMembership $obj)
    {
        $obj->setDivision(NULL);
        $obj->setCompetition(NULL);
        $this->teamMemberships->removeElement($obj);
        return $this;
    }
    
    /**
     * @param TeamMembership $obj
     * @return bool
     */
    public function hasTeamMembership(TeamMembership $obj)
    {
        return $this->teamMemberships->contains($obj);
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
