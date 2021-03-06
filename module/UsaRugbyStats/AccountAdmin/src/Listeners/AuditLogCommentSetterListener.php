<?php
namespace UsaRugbyStats\AccountAdmin\Listeners;

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
        $this->listeners[] = $events->attach('create', array($this, 'setCommentForEvent'), 2); //pre
        $this->listeners[] = $events->attach('edit', array($this, 'setCommentForEvent'), 2); //pre
        //@TODO $this->listeners[] = $events->attach('remove', array($this, 'setCommentForEvent'), 2); //pre
    }

    public function setCommentForEvent(EventInterface $e)
    {
        $entity = $e->getParam('user');
        $entityId = $entity->getId();
        $entityClassSlug = trim(strtoupper(str_replace('\\', '_', preg_replace('{^UsaRugbyStats\\\\Account\\\Entity\\\\}is', '', get_class($entity)))), ' _');

        $this->auditService->setComment(
            implode('_', array(
               'ACCOUNTADMIN',
                $entityClassSlug,
                $e->getName() == 'remove' ? 'DELETE' : (empty($entityId) ? 'CREATE' : 'UPDATE')
            ))
        );
    }
}
