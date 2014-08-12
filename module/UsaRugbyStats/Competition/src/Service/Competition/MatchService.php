<?php
namespace UsaRugbyStats\Competition\Service\Competition;

use UsaRugbyStats\Application\Service\AbstractService;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Location;
use Doctrine\Common\Collections\Criteria;
use DoctrineModule\Paginator\Adapter\Selectable;
use Zend\Paginator\Paginator;

class MatchService extends AbstractService
{

    /**
     * List of available MatchTeamEvent types
     *
     * @var array
     */
    protected $availableMatchTeamEventTypes;

    public function attachDefaultListeners()
    {
        $this->getEventManager()->attach('save', function (EventInterface $e) {
            $e->getParams()->entity->recalculateScore();
        }, -99999);
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
