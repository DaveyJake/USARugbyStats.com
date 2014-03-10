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
}