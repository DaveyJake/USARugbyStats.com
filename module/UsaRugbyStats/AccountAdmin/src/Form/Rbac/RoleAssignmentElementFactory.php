<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollection;

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
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $element = new NonuniformCollection();
        $element->setName('roleAssignments');
        $element->setTargetElement(array(
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset'),
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset'),
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset'),
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset'),
            'UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset'),
        ));

        return $element;
    }
}
