<?php
namespace UsaRugbyStats\Competition\Rbac\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchTeamChange implements SharedListenerAggregateInterface
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
            $context = null;
        }

        foreach ( $context->getTeams() as $matchTeam ) {
            // Ditch the team selector if we don't have permission to change it
            if ( ! $this->getAuthorizationService()->isGranted('competition.competition.match.team.change', $matchTeam) ) {
                $index = array_search('team', $e->getParams()->validationGroup['match']['teams'][$matchTeam->getType()]);
                if ($index !== false) {
                    unset($e->getParams()->validationGroup['match']['teams'][$matchTeam->getType()][$index]);
                }
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
