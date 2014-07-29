<?php
namespace UsaRugbyStats\Competition\Rbac\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionUpdateDivisions implements SharedListenerAggregateInterface
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
            'UsaRugbyStats\Competition\Form\CompetitionCreateForm',
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
        if ( ! $context instanceof Competition || $context->getId() == NULL ) {
            $context = null;
        }

        // Ditch union.teams from the form if we don't have permission to change it
        if ( ! $this->getAuthorizationService()->isGranted('competition.competition.update.divisions', $context) ) {
            unset($e->getParams()->validationGroup['competition']['divisions']);
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
