<?php
namespace UsaRugbyStats\Competition\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
     * Competitions this team is participating in
     *  
     * @var Collection
     */
    protected $competitions;
    
    
    public function __construct()
    {
        $this->competitions = new ArrayCollection();
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
     * @param integer $id
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
     * @param string $name
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
     * @param Union $u
     * @return self
     */
    public function setUnion(Union $u = NULL)
    {
        $this->union = $u;
        return $this;
    }    

    /**
     * @return Collection
     */
    public function getCompetitions()
    {
        return $this->competitions;
    }
    
    /**
     * @param Collection $comps
     * @return self
     */
    public function setCompetitions(Collection $comps)
    {
        $this->competitions->clear();
        $this->addCompetitions($comps);
        return $this;
    }
    
    /**
     * @param Collection $comps
     * @return self
     */
    public function addCompetitions(Collection $comps)
    {
        if(count($comps)){
            foreach($comps as $comp){
                $this->addCompetition($comp);
            }
        }
        return $this;
    }
    
    /**
     * @param Competition $comp
     * @return self
     */
    public function addCompetition(Competition $comp)
    {
        if ( ! $this->hasCompetition($comp) ) {
            $this->competitions->add($comp);
        }
        return $this;
    }
    
    /**
     * @param Collection $teams
     * @return self
     */
    public function removeCompetitions(Collection $comps)
    {
        if(count($comps)){
            foreach($comps as $comp){
                $this->removeCompetition($comp);
            }
        }
        return $this;
    }
    
    /**
     * @param Competition $comp
     * @return self
     */
    public function removeCompetition(Competition $comp)
    {
        $this->competitions->removeElement($comp);
        return $this;
    }
    
    /**
     * @param Competition$comp$role
     * @return bool
     */
    public function hasCompetition(Competition $comp)
    {
        return $this->competitions->contains($comp);
    }

    /**
     * String representation of this Competition object
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
