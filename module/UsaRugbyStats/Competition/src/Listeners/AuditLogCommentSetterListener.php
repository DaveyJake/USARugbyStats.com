<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use SoliantEntityAudit\Service\AuditService;

class AuditLogCommentSetterListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    protected $auditService;

    public function __construct(AuditService $obj)
    {
        $this->auditService = $obj;
    }

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('save', array($this, 'setCommentForEvent'), 2); //pre
        $this->listeners[] = $events->attach('remove', array($this, 'setCommentForEvent'), 2); //pre
    }

    public function setCommentForEvent(EventInterface $e)
    {
        $entity = $e->getParam('entity');
        $entityClassSlug = trim(strtoupper(str_replace('\\', '_', preg_replace('{^UsaRugbyStats\\\\Competition\\\Entity\\\\}is', '', get_class($entity)))), ' _');

        switch ( $entityClassSlug )
        {
        	case 'COMPETITION':
        	    $entityClassSlug = 'COMP';
                break;
            case 'COMPETITION_MATCH':
                $entityClassSlug = 'COMPMATCH';
                break;
        }

        $this->auditService->setComment(
            implode('_', array(
        	   'COMPETITION',
                $entityClassSlug,
                $e->getName() == 'remove' ? 'DELETE' : (empty($entity->getId()) ? 'CREATE' : 'UPDATE')
            ))
        );
    }
}
