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
    public function setUnion(Union $u)
    {
        $this->union = $u;
        return $this;
    }

    /**
     * String representation of this Team object
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

}
