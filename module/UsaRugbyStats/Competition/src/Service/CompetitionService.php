<?php
namespace UsaRugbyStats\Competition\Service;

use UsaRugbyStats\Application\Service\AbstractService;

class CompetitionService extends AbstractService
{
    public function findLeagueCompetitionForTeam($team)
    {
        return $this->getRepository()->findLeagueCompetitionForTeam($team);
    }
}
