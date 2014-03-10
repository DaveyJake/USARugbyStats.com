<?php
namespace UsaRugbyStats\Application\Entity;

class Team 
{
    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @var bool
     */
    protected $hidden;
    
    /**
     * @var string
     */
    protected $userCreate;
    
    /**
     * @var string
     */
    protected $uuid;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $short;

    /**
     * @var string
     */
    protected $resources;
    
    /**
     * @var string
     */
    protected $logoUrl;
    
    /**
     * @var string
     */
    protected $description;
    
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $status;
    
    /**
     * @var string
     */
    protected $groupAboveUuid;
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHidden()
    {
        return $this->hidden;
    }

    public function setHidden($hidden)
    {
        $this->hidden = $hidden;
    }

    public function getUserCreate()
    {
        return $this->userCreate;
    }

    public function setUserCreate($userCreate)
    {
        $this->userCreate = $userCreate;
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getShort()
    {
        return $this->short;
    }

    public function setShort($short)
    {
        $this->short = $short;
    }

    public function getResources()
    {
        return $this->resources;
    }

    public function setResources($resources)
    {
        $this->resources = $resources;
    }

    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getGroupAboveUuid()
    {
        return $this->groupAboveUuid;
    }

    public function setGroupAboveUuid($groupAboveUuid)
    {
        $this->groupAboveUuid = $groupAboveUuid;
    }

}
