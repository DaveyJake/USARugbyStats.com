<?php
namespace UsaRugbyStats\Competition\Rbac\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchTeamEventsChange implements SharedListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * @var \ZfcRbac\Service\AuthorizationService
     */
    protected $authorizationService;

    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'UsaRugbyStats\Competition\Form\Competition\MatchCreateForm',
            'prepareValidationGroup.post',
            [$this, 'prepareValidationGroup']
        );
    }

    /**
     * {@inheritDoc}
     */
    public function detachShared(SharedEventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $callback) {
            if ($events->detach($callback)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function prepareValidationGroup(EventInterface $e)
    {
        $context = $e->getTarget()->getObject();
        if ( ! $context instanceof Match || $context->getId() == NULL ) {
            return;
        }

        foreach ( $context->getTeams() as $matchTeam ) {
            // Ditch the events collection from the team form if we don't have permission to alter the events
            if ( ! $this->getAuthorizationService()->isGranted('competition.competition.match.team.events.change', $matchTeam) ) {
                unset($e->getParams()->validationGroup['match']['teams'][$matchTeam->getType()]['events']);
            }
        }
    }

    /**
     * @return \ZfcRbac\Service\AuthorizationService
     */
    public function getAuthorizationService()
    {
        return $this->authorizationService;
    }

    /**
     * @param \ZfcRbac\Service\AuthorizationService $svc
     */
    public function setAuthorizationService(AuthorizationService $svc)
    {
        $this->authorizationService = $svc;

        return $this;
    }

}
