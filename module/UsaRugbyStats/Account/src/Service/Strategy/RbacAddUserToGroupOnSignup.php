<?php
namespace UsaRugbyStats\Account\Service\Strategy;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\Mvc\MvcEvent;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\Event;
use Zend\Filter\Word\SeparatorToCamelCase;
use UsaRugbyStats\Account\Entity\Rbac\Role;

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
        $this->listeners[] = $events->attach(
            'create.post',
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

        $filter = new SeparatorToCamelCase('_');

        $changed = false;
        foreach ($this->groupNames as $roleName) {
            $role = $svcRole->findOneBy(['name' => $roleName]);
            if (! $role instanceof Role) {
                continue;
            }
            if ( $user->hasRole($role) ) {
                continue;
            }

            $className = 'UsaRugbyStats\\Account\\Entity\\Rbac\\RoleAssignment\\' . $filter->filter($roleName);
            if ( ! class_exists($className) ) {
                continue;
            }
            $assignment = new $className();
            $assignment->setAccount($user);
            $assignment->setRole($role);

            $user->addRoleAssignment($assignment);
            $changed = true;
        }
        if ($changed) {
            $e->getTarget()->getUserMapper()->update($user);
        }
    }
}
