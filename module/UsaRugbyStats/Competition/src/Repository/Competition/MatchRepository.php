<?php
namespace UsaRugbyStats\Competition\Repository\Competition;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Team;
use Doctrine\Common\Collections\Collection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Union;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchRepository extends EntityRepository
{

    public function findAllForTeam($teams)
    {
        // Extract the list of teams to query for
        $teamids = [];
        if ($teams instanceof Team) {
            $teamids[] = $teams->getId();
        } elseif ($teams instanceof Collection || $teams instanceof \Traversable) {
            foreach ($teams as $team) {
                if (! $team instanceof Team) {
                    continue;
                }
                $teamids[] = $team->getId();
            }
        } else {
            return new ArrayCollection();
        }

        $dql = <<<DQL
            SELECT
                DISTINCT cm
            FROM
                UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam cmt
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition\Match cm
                    WITH cm.id = cmt.match
            WHERE
                cmt.team IN (:teams)
            ORDER BY
                cm.date ASC
DQL;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('teams', $teamids);
        $result = $query->getResult();

        return (new ArrayCollection($result))->filter(function($i) {
            return $i instanceof Match;
        });
    }

    public function findAllForCompetition($comps)
    {
        // Extract the list of competitions to query for
        $compids = [];
        if ($comps instanceof Competition) {
            $compids[] = $comps->getId();
        } elseif ($comps instanceof Collection || $comps instanceof \Traversable) {
            foreach ($comps as $comp) {
                if (! $comp instanceof Competition) {
                    continue;
                }
                $compids[] = $comp->getId();
            }
        } else {
            return new ArrayCollection();
        }

        $dql = <<<DQL
            SELECT
                DISTINCT cm
            FROM
                UsaRugbyStats\Competition\Entity\Competition\Match cm
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition c
                    WITH c.id = cm.competition
            WHERE
                c.id IN (:comps)
            ORDER BY
                cm.date ASC
DQL;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('comps', $compids);
        $result = $query->getResult();

        return (new ArrayCollection($result))->filter(function($i) {
            return $i instanceof Match;
        });
    }

    public function findAllForUnion($unions)
    {
        // Extract the list of unions to query for
        $unionids = [];
        if ($unions instanceof Union) {
            $unionids[] = $unions->getId();
        } elseif ($unions instanceof Collection || $unions instanceof \Traversable) {
            foreach ($unions as $union) {
                if (! $union instanceof Union) {
                    continue;
                }
                $unionids[] = $union->getId();
            }
        } else {
            return new ArrayCollection();
        }

        $dql = <<<DQL
            SELECT
                DISTINCT cm
            FROM
                UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam cmt
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition\Match cm
                    WITH cm.id = cmt.match
                LEFT JOIN UsaRugbyStats\Competition\Entity\Team t
                    WITH t.id = cmt.team
            WHERE
                t.union IN (:unions)
            ORDER BY
                cm.date ASC
DQL;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('unions', $unionids);
        $result = $query->getResult();

        return (new ArrayCollection($result))->filter(function($i) {
            return $i instanceof Match;
        });
    }

    public function findAllForPlayer($players)
    {
        // Extract the list of players to query for
        $playerids = [];
        if ($players instanceof AccountInterface) {
            $playerids[] = $players->getId();
        } elseif ($players instanceof Collection || $players instanceof \Traversable) {
            foreach ($players as $player) {
                if (! $player instanceof AccountInterface) {
                    continue;
                }
                $playerids[] = $player->getId();
            }
        } else {
            return new ArrayCollection();
        }

        $dql = <<<DQL
            SELECT
                DISTINCT cm
            FROM
                UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer cmtp
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam cmt
                    WITH cmt.id = cmtp.team
                LEFT JOIN UsaRugbyStats\Competition\Entity\Competition\Match cm
                    WITH cm.id = cmt.match
            WHERE
                cmtp.player IN (:players)
            ORDER BY
                cm.date ASC
DQL;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('players', $playerids);
        $result = $query->getResult();

        return (new ArrayCollection($result))->filter(function($i) {
            return $i instanceof Match;
        });
    }
}
