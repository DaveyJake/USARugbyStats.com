<?php
namespace UsaRugbyStats\Account\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;

class AccountRepository extends EntityRepository
{
    public function findAllMembersForTeam($team)
    {
        return $this->findAllMembersForTeamWithStatus($team);
    }

    public function findAllCurrentMembersForTeam($team)
    {
        return $this->findAllMembersForTeamWithStatus($team, 2);
    }

    public function findAllMembersForTeamWithStatus($team, $status = null)
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
            ORDER BY aa.displayName ASC
DQL;

        if ( !empty($status) ) {
            $dql .= "AND ctm.membershipStatus IN :statuses";
        }

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('team_id', $team_id);

        if ( !empty($status) ) {
            $query->setParameter('statuses', (array) $status);
        }

        return new ArrayCollection($query->getResult());
    }
}
