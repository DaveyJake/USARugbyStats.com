<?php
namespace UsaRugbyStats\Competition\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Application\Entity\AccountInterface;
use Doctrine\Common\Collections\Collection;

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
        $qb->leftJoin('UsaRugbyStats\Competition\Entity\Competition\Division', 'Division', 'WITH', 'TeamMembership.division = Division.id');
        $qb->where($qb->expr()->eq('Division.competition', $competition));

        return new ArrayCollection($qb->getQuery()->getResult());
    }

    public function findAllForPlayer($players)
    {
        // Extract the list of players to query for
        $playerids = [];
        if ( $players instanceof AccountInterface ) {
            $playerids[] = $players->getId();
        } elseif ( $players instanceof Collection || $players instanceof \Traversable ) {
            foreach ( $players as $player ) {
                if ( ! $player instanceof AccountInterface ) {
                    continue;
                }
                $playerids[] = $player->getId();
            }
        } else {
            return new ArrayCollection();
        }

        $dql = <<<DQL
            SELECT
                DISTINCT t
            FROM
                UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer cmtp
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam cmt
                    WITH cmt.id = cmtp.team
                LEFT JOIN UsaRugbyStats\Competition\Entity\Team t
                    WITH t.id = cmt.team
            WHERE
                cmtp.player IN (:players)
            ORDER BY
                t.name ASC
DQL;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('players', $playerids);
        $result = $query->getResult();

        return new ArrayCollection($result);
    }

}
