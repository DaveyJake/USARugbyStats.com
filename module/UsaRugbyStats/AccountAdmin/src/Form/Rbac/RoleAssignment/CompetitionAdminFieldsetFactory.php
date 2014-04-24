<?php
namespace UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator;

class CompetitionAdminFieldsetFactory implements FactoryInterface
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
        $mapper = $om->getRepository('UsaRugbyStats\Competition\Entity\Competition');

        $fieldset = new CompetitionAdminFieldset($om, $mapper);
        $fieldset->setHydrator(new NonuniformCollectionHydrator($om));
        $fieldset->setObject(new CompetitionAdmin());

        return $fieldset;
    }
}
