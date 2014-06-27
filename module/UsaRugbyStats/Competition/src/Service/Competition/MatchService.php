<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use UsaRugbyStats\Application\Service\AbstractService;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchService extends AbstractService
{

    /**
     * List of available MatchTeamEvent types
     *
     * @var array
     */
    protected $availableMatchTeamEventTypes;

    public function save($entity)
    {
        if ($entity instanceof Match) {
            $entity->recalculateScore();
        }

        return parent::save($entity);
    }

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
