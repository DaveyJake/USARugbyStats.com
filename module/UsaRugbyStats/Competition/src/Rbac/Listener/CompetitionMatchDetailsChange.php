<?php
namespace UsaRugbyStats\Competition\Rbac\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use ZfcRbac\Service\AuthorizationService;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class CompetitionMatchDetailsChange implements SharedListenerAggregateInterface
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
            'getValidationGroup.post',
            [$this, 'getValidationGroup']
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

    public function getValidationGroup(EventInterface $e)
    {
        $context = $e->getTarget()->getObject();
        if ( ! $context instanceof Match || $context->getId() == NULL ) {
            $context = null;
        }

        // Ditch match details fields from the form if we don't have permission to change them
        if ( ! $this->getAuthorizationService()->isGranted('competition.competition.match.details.change', $context) ) {
            $fieldset = $e->getTarget()->get('match');
            foreach ( $fieldset->getElements() as $element ) {
                $index = array_search($element->getName(), $e->getParams()->validationGroup['match']);
                if ($index !== false) {
                    unset($e->getParams()->validationGroup['match'][$index]);
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
