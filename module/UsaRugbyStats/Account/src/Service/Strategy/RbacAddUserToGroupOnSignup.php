<?php
namespace UsaRugbyStats\Account\Service\Strategy;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\MvcEvent;
use UsaRugbyStats\Account\Entity\Rbac\AccountRole;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;

class RbacAddUserToGroupOnSignup extends AbstractListenerAggregate
{
    protected $groupNames = array();
    protected $objectManager;
    
    public function __construct(ObjectManager $om)
    {
        $this->objectManager = $om;
    }
    
    public function setGroups(array $gn)
    {
        $this->groupNames = array_unique($gn);
        return $this;
    }
    
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'register.post',
            [$this, 'addUserToGroup']
        );
    }

    /**
     * @param  MvcEvent $e
     * @return void
     */
    public function addUserToGroup(Event $e)
    {
        $user = $e->getParam('user');
        $svcRole = $this->objectManager->getRepository('UsaRugbyStats\Account\Entity\Rbac\Role');
        
        foreach ( $this->groupNames as $roleName )
        {
            $role = $svcRole->findOneBy(['name' => $roleName]);
            
            $assignment = new AccountRole();
            $assignment->setAccount($user);
            $assignment->setRole($role);
            
            $user->addRoleAssignment($assignment);
        }

        $e->getTarget()->getUserMapper()->update($user);
    }
}
