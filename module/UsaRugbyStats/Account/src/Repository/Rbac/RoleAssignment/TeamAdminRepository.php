<?php
namespace UsaRugbyStats\Account\Repository\Rbac\RoleAssignment;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class TeamAdminRepository extends EntityRepository
{
    public function findByTeam($team_id)
    {
        $qb = $this->createQueryBuilder('rrata');
        $qb->where(':tid MEMBER OF rrata.managedTeams');
        $qb->setParameter('tid', $team_id);

        return new ArrayCollection($qb->getQuery()->getResult());
    }
}
