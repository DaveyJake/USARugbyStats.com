<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignmentHydrator;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin;

class UnionAdminFieldsetFactory implements FactoryInterface
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
        $mapper = $om->getRepository('UsaRugbyStats\Competition\Entity\Union');

        $fieldset = new UnionAdminFieldset($om, $mapper);
        $fieldset->setHydrator(new RoleAssignmentHydrator($om));
        $fieldset->setObject(new UnionAdmin());

        return $fieldset;
    }
}
