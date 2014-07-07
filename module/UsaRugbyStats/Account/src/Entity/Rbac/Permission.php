<?php
namespace UsaRugbyStats\Account\Entity\Rbac;

use Rbac\Permission\PermissionInterface;

class Permission implements PermissionInterface
{
    protected $id;
    protected $name;

    public function __construct($name = null)
    {
        $this->setName($name);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

}
