<?php
namespace UsaRugbyStats\AccountAdmin\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $service = new UserService();
        
        $collection = $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement');
        $classes = $collection->getTargetElement();
        if ( is_array($classes) ) {
            $set = array();
            foreach ( $classes as $item ) {
                $set[$item->getName()] = array(
                    'name'              => $item->getName(),
                    'fieldset_class'    => get_class($item),
                    'entity_class'      => get_class($item->getObject()),
                );
            }        
            $service->setAvailableRoleAssignments($set);
        }
        
        return $service;
    }
}