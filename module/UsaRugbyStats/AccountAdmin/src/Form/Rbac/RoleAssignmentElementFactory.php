<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollection;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator;

/**
 * Factory for creating the collection for managing RBAC role assignments of an account 
 * 
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class RoleAssignmentElementFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $element = new NonuniformCollection();
        $element->setName('roleAssignments');
        $element->setTargetElement(array(
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset'),
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset'),
        ));
        return $element;
    }
}