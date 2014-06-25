<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LdcZendFormCTI\Form\Element\NonuniformCollection;

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
        $element->setDiscriminatorFieldName('type');
        $element->setTargetElement(array(
            'member' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset'),
            'team_admin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset'),
            'competition_admin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset'),
            'union_admin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset'),
            'super_admin' => $sm->get('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset'),
        ));
        $element->setShouldCreateTemplate(true);

        return $element;
    }
}
