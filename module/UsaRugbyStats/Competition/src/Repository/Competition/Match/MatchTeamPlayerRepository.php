<?php
namespace UsaRugbyStats\Competition\Repository\Competition\Match;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

class MatchTeamPlayerRepository extends EntityRepository
{

    public function findAllPlayersForMatchTeam($matchTeam)
    {
        return new ArrayCollection(
            $this->findBy(
                [ 'team' => $matchTeam ]
            )
        );
    }

}
