<?php
namespace UsaRugbyStats\Competition\Repository;

use Doctrine\ORM\EntityRepository;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionRepository extends EntityRepository
{
    public function findLeagueCompetitionForTeam($team)
    {
        $teamid = $team instanceof Team ? $team->getId() : (int)$team;

        $dql = <<<DQL
            SELECT
                DISTINCT c
            FROM
                UsaRugbyStats\Competition\Entity\Competition\TeamMembership ctm
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition c
                    WITH c.id = ctm.competition
            WHERE
                ctm.team = :team
                AND c.type = :type

DQL;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('team', $teamid);
        $query->setParameter('type', Competition::TYPE_LEAGUE);
        $query->setMaxResults(1);
        $resultset = $query->getResult();

        return array_pop($resultset);
    }
}
