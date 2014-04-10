<?php
namespace UsaRugbyStats\Competition\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Competition
 * 
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class Competition
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
     * Divisions in this competition
     *
     * @var Collection
     */
    protected $divisions;

    /**
     * Games which make up the competition
     *
     * @var Collection
     */
    protected $games;
    
    /**
     * Teams which are participating
     * 
     * @var Collection
     */
    protected $teams;


    public function __construct()
    {
        $this->divisions = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->games = new ArrayCollection();
    }
    
    /**
     * Competition Identifier 
     * 
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Competition Identifier
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
     * Competition Name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Competition Name
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
     * @return Collection
     */
    public function getDivisions()
    {
        return $this->divisions;
    }
    
    /**
     * @param Collection $divisions
     * @return self
     */
    public function setDivisions(Collection $divisions)
    {
        $this->divisions->clear();
        $this->addDivisions($divisions);
        return $this;
    }
    
    /**
     * @param Collection $divisions
     * @return self
     */
    public function addDivisions(Collection $divisions)
    {
        if(count($divisions)){
            foreach($divisions as $ra){
                $this->addDivision($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param Competition\Division $role
     * @return self
     */
    public function addDivision(Competition\Division $ra)
    {
        if ( ! $this->hasDivision($ra) ) {
            $ra->setCompetition($this);
            $this->divisions->add($ra);
        }
        return $this;
    }
    
    /**
     * @param Collection $divisions
     * @return self
     */
    public function removeDivisions(Collection $divisions)
    {
        if(count($divisions)){
            foreach($divisions as $ra){
                $this->removeDivision($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param Competition\Division $role
     * @return self
     */
    public function removeDivision(Competition\Division $ra)
    {
        $ra->setCompetition(null);
        $this->divisions->removeElement($ra);
        return $this;
    }
    
    /**
     * @param Competition\Division $role
     * @return bool
     */
    public function hasDivision(Competition\Division $ra)
    {
        return $this->divisions->contains($ra);
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
        if ( ! $this->hasTeam($ra) ) {
            $ra->setCompetition($this);
            $this->teams->add($ra);
        }
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
     * @return Collection
     */
    public function getGames()
    {
        return $this->games;
    }
    
    /**
     * @param Collection $games
     * @return self
     */
    public function setGames(Collection $games)
    {
        $this->games->clear();
        $this->addGames($games);
        return $this;
    }
    
    /**
     * @param Collection $games
     * @return self
     */
    public function addGames(Collection $games)
    {
        if(count($games)){
            foreach($games as $ra){
                $this->addGame($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param Competition\Game $role
     * @return self
     */
    public function addGame(Competition\Game $ra)
    {
        if ( ! $this->hasGame($ra) ) {
            $ra->setCompetition($this);
            $this->games->add($ra);
        }
        return $this;
    }
    
    /**
     * @param Collection $games
     * @return self
     */
    public function removeGames(Collection $games)
    {
        if(count($games)){
            foreach($games as $ra){
                $this->removeGame($ra);
            }
        }
        return $this;
    }
    
    /**
     * @param Competition\Game $role
     * @return self
     */
    public function removeGame(Competition\Game $ra)
    {
        $ra->setCompetition(null);
        $this->games->removeElement($ra);
        return $this;
    }
    
    /**
     * @param Competition\Game $role
     * @return bool
     */
    public function hasGame(Competition\Game $ra)
    {
        return $this->games->contains($ra);
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
