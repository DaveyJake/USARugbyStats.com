<?php
namespace UsaRugbyStats\Account\Listeners;

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
        $this->listeners[] = $events->attach('insert', array($this, 'setCommentForEvent'), 2); //pre
        $this->listeners[] = $events->attach('update', array($this, 'setCommentForEvent'), 2); //pre
    }

    public function setCommentForEvent(EventInterface $e)
    {
        $this->auditService->setComment('ACCOUNT_PROFILE_UPDATE');
    }
}
