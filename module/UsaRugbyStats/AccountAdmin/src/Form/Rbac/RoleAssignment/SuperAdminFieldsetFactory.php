<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignmentHydrator;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin;

class SuperAdminFieldsetFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $om = $sm->get('zfcuser_doctrine_em');
        $fieldset = new SuperAdminFieldset($om);
        $fieldset->setHydrator(new RoleAssignmentHydrator($om));
        $fieldset->setObject(new SuperAdmin());

        return $fieldset;
    }
}
