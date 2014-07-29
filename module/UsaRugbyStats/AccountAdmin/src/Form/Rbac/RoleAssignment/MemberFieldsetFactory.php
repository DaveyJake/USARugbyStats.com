<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignmentHydrator;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;

class MemberFieldsetFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $teamMemberFieldset = $sm->get('usarugbystats_competition_team_member_fieldset');
        $teamMemberFieldset->remove('role');

        $om = $sm->get('zfcuser_doctrine_em');
        $fieldset = new MemberFieldset($teamMemberFieldset);
        $fieldset->setHydrator(new RoleAssignmentHydrator($om));
        $fieldset->setObject(new Member());

        return $fieldset;
    }
}
