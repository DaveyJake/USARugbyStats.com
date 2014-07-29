<?php
namespace UsaRugbyStats\Account\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;

class AccountRepository extends EntityRepository
{
    public function findAllCurrentMembersForTeam($team)
    {
        $team_id = $team instanceof Team ? $team->getID() : (int) $team;

        if (empty($team_id)) {
            return new ArrayCollection();
        }

        $dql = <<<DQL
            SELECT
                DISTINCT aa
            FROM
                UsaRugbyStats\Competition\Entity\Team\Member ctm
                LEFT JOIN UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member arram
                    WITH arram.id = ctm.role
                LEFT JOIN UsaRugbySTats\Account\Entity\Account aa
                    WITH aa.id = arram.account
            WHERE
                ctm.team = :team_id
                AND ctm.membershipStatus = '2'
DQL;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('team_id', $team_id);

        return new ArrayCollection($query->getResult());
    }
}
