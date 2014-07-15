<?php
namespace UsaRugbyStats\Application\Listener;

use Zend\EventManager\EventInterface;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Form\ElementInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Form\FieldsetInterface;
use Zend\Form\Element\Collection;
use LdcZendFormCTI\Form\Element\NonuniformCollection;

class ConstructCompleteValidationGroupForForm implements SharedListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    public function attachShared(SharedEventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(
            'Zend\Form\Form',
            'getValidationGroup.post',
            [$this, 'getValidationGroup'],
            99999
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
        if ( ! is_null($e->getParams()->validationGroup) ) {
            return;
        }

        $e->getParams()->validationGroup = ArrayUtils::merge(
            $this->process($e->getTarget()->getElements()),
            $this->process($e->getTarget()->getFieldsets())
        );

        $e->getTarget()->setValidationGroup($e->getParams()->validationGroup);
    }

    protected function process($set)
    {
        if ( ! is_array($set) && ! $set instanceof \Traversable ) {
            return [];
        }

        $names = [];
        foreach ($set as $elementOrFieldset) {
            // Don't push down any further if it's a NonuniformCollection
            // as validation group only support uniform collection contents
            if ($elementOrFieldset instanceof NonuniformCollection) {
                array_push($names, $elementOrFieldset->getName());
                continue;
            }
            if ($elementOrFieldset instanceof Collection) {
                $targetElement = $elementOrFieldset->getTargetElement();
                $names[$elementOrFieldset->getName()] = ArrayUtils::merge(
                    $this->process($targetElement->getElements()),
                    $this->process($targetElement->getFieldsets())
                );
                continue;
            }
            if ($elementOrFieldset instanceof FieldsetInterface) {
                $names[$elementOrFieldset->getName()] = ArrayUtils::merge(
                    $this->process($elementOrFieldset->getElements()),
                    $this->process($elementOrFieldset->getFieldsets())
                );
                continue;
            }
            if ($elementOrFieldset instanceof ElementInterface) {
                array_push($names, $elementOrFieldset->getName());
                continue;
            }
        }

        return $names;
    }

}
