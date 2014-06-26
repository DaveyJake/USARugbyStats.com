<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use UsaRugbyStats\Competition\Service\AbstractService;

class MatchService extends AbstractService
{

    /**
     * List of available MatchTeamEvent types
     *
     * @var array
     */
    protected $availableMatchTeamEventTypes;

    public function setAvailableMatchTeamEventTypes($set)
    {
        $this->availableMatchTeamEventTypes = $set;

        return $this;
    }

    public function getAvailableMatchTeamEventTypes()
    {
        return $this->availableMatchTeamEventTypes;
    }

}
