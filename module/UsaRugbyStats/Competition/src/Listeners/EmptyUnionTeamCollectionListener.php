<?php
namespace UsaRugbyStats\Competition\Listeners;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Form\FormInterface;

class EmptyUnionTeamCollectionListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach('form.bind', array($this, 'run'), -9999); // pre
    }

    public function run(EventInterface $e)
    {
        $entity = $e->getParams()->entity;
        $data = @$e->getParams()->data ?: array();

        // Only apply this hack if the collection is in the validation group
        $vg = $e->getParams()->form->getValidationGroup();
        if ( $vg != FormInterface::VALIDATE_ALL && !isset($vg['union']['teams']) ) {
            return;
        }

        // @HACK to fix GH-15 (Can't empty an existing Collection)
        if ( !isset($data['union']['teams']) || empty($data['union']['teams']) ) {
            $entity->removeTeams($entity->getTeams());
        }
    }
}
