<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Team;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UpdateTeamMembershipSortKeyFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Authentication
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $om   = $sm->get('zfcuser_doctrine_em');
        $repo = $om->getRepository('UsaRugbyStats\Competition\Entity\Competition\TeamMembership');

        $obj = new UpdateTeamMembershipSortKey();
        $obj->setObjectManager($om);
        $obj->setTeamMembershipRepository($repo);

        return $obj;
    }
}
