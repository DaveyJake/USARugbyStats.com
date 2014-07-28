<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Team;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use UsaRugbyStats\Competition\Entity\Team\Member;

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
        $om = $sm->get('zfcuser_doctrine_em');
        $fieldset = new MemberFieldset($om);
        $fieldset->setHydrator(new DoctrineObject($om));
        $fieldset->setObject(new Member());

        return $fieldset;
    }
}
