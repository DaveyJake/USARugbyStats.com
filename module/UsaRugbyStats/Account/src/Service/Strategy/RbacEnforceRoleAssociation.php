<?php
namespace UsaRugbyStats\Account\Service\Strategy;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use UsaRugbyStats\Account\Entity\Rbac\Role;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment;

class RbacEnforceRoleAssociation implements EventSubscriber
{
    protected $objectManager;
    
    public function __construct(ObjectManager $om)
    {
        $this->objectManager = $om;
    }

    public function getSubscribedEvents()
    {
        return array(Events::prePersist);
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        $roleRepository = $this->objectManager->getRepository('UsaRugbyStats\Account\Entity\Rbac\Role');
        
        if ( $entity instanceof RoleAssignment ) {

            $roleName = $entity->getDiscriminator();
            $roleObj = $roleRepository->findOneBy(array('name' => $roleName));
            
            if ( $roleObj instanceof Role) {
                $entity->setRole($roleObj);
            }
        }
    }
}

