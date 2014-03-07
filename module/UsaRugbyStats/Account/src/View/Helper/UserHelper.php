<?php
namespace UsaRugbyStats\Account\View\Helper;

use ZfcUser\View\Helper\ZfcUserIdentity;

class UserHelper extends ZfcUserIdentity
{
    protected $entityClass = 'ZfcUser\Entity\UserInterface';
    
    /**
     * __invoke
     *
     * @access public
     * @return \ZfcUser\Entity\UserInterface
     */
    public function __invoke()
    {
        if ($this->getAuthService()->hasIdentity()) {
            return $this->getAuthService()->getIdentity();
        }
        
        $factory = new \ProxyManager\Factory\NullObjectFactory();
        return $factory->createProxy($this->getEntityClass());
    }   

    public function setEntityClass($class)
    {
        $this->entityClass = $class;
        return $this;
    }
    
    public function getEntityClass()
    {
        return $this->entityClass;
    }
}