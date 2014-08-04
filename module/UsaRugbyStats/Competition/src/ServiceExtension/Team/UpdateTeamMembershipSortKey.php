<?php
namespace UsaRugbyStats\Competition\ServiceExtension\Team;

use UsaRugbyStats\Application\Service\ServiceExtensionInterface;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Update the sort key of each membership record to keep the sort order of the select box in sync
 */
class UpdateTeamMembershipSortKey implements ServiceExtensionInterface
{
    /**
     * @var ObjectManager
     */

    protected $objectManager;

    /**
     * @var ObjectRepository
     */
    protected $teamMembershipRepository;

    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()['entity']) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $entity = $e->getParams()['entity'];
        $entity instanceof Team;

        $memberships = $this->getTeamMembershipRepository()->findBy([
            'team' => $entity->getId()
        ]);
        if ( empty($memberships) ) {
            return;
        }

        foreach ($memberships as $item) {
            if (! $item instanceof TeamMembership) {
                continue;
            }
            if ( $item->getSortKey() != $entity->getName() ) {
                $item->setSortKey($entity->getName());
                $this->getObjectManager()->persist($item);
            }
        }

        $this->getObjectManager()->flush();
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param ObjectManager $om
     */
    public function setObjectManager(ObjectManager $om)
    {
        $this->objectManager = $om;

        return $this;
    }

    /**
     * @return ObjectRepository
     */
    public function getTeamMembershipRepository()
    {
        return $this->teamMembershipRepository;
    }

    /**
     * @param ObjectRepository $repo
     */
    public function setTeamMembershipRepository(ObjectRepository $repo)
    {
        $this->teamMembershipRepository = $repo;

        return $this;
    }

}
