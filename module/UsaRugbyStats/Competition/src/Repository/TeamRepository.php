<?php
namespace UsaRugbyStats\Competition\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class TeamRepository extends EntityRepository
{

    public function findAllTeamsInCompetition($competition)
    {
        if ( empty($competition) ) {
            return $this->findAll();
        }

        $qb = $this->createQueryBuilder('Team');
        $qb->distinct(true);
        $qb->leftJoin('UsaRugbyStats\Competition\Entity\Competition\TeamMembership', 'TeamMembership', 'WITH', 'Team.id = TeamMembership.team');
        $qb->where($qb->expr()->eq('TeamMembership.competition', $competition));

        return new ArrayCollection($qb->getQuery()->getResult());
    }

}
