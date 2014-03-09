<?php
namespace UsaRugbyStats\Account\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(table="teams")
 */
class Team 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected $hidden;
    
    /**
     * @ORM\Column(name="user_create", type="string", length=64)
     */
    protected $userCreate;
    
    /**
     * @ORM\Column(type="string", length=36)
     */
    protected $uuid;
    
    /**
     * @ORM\Column(type="string", length="60")
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", length="60")
     */
    protected $short;

    /**
     * @ORM\Column(type="blob")
     */
    protected $resources;
    
    /**
     * @ORM\Column(name="logo_url", type="string", length="1024")
     */
    protected $logoUrl;
    
    /**
     * @ORM\Column(type="string", length="1024")
     */
    protected $description;
    
    /**
     * @ORM\Column(type="string", length="64")
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length="10")
     */
    protected $status;
    
    /**
     * @ORM\Column(name="group_above_uuid", type="string", length="36")
     */
    protected $groupAboveUuid;
}