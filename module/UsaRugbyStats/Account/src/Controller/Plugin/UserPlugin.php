<?php
namespace UsaRugbyStats\Account\Controller\Plugin;

use ZfcUser\Controller\Plugin\ZfcUserAuthentication;

class UserPlugin extends ZfcUserAuthentication
{
    protected $entityClass;
    
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
    
    public function isAuthenticated()
    {
        return $this->getAuthService()->hasIdentity();
    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
        return $this;
    }

}
