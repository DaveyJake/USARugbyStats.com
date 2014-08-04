<?php
namespace UsaRugbyStats\Application\Common;

use Zend\Form\Form;
use Zend\Form\FieldsetInterface;
use DoctrineModule\Form\Element\ObjectSelect;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Form\Element\ObjectRadio;
use Zend\Form\Element\DateTime;
use Zend\Form\FormInterface;
use Zend\Form\Element\Collection;
use Zend\Stdlib\ArrayUtils;

/**
 * Rationale:
 * ##########
 *
 * When binding a complex object to a form and immediately retrieving an array
 * ie: using Form as an extractor:
 *
 * ```
 *     $form->bind($entity);
 *     $form->isValid();
 *     $data = $form->getData(FormInterface::VALUES_AS_ARRAY);
 * ```
 *
 * It's likely that the result will be something that you can't immediately put
 * back into `$form->setData` and have the form validate properly due to some
 * form elements returning from `getValue` something this is incompatible with
 * filters or validators assigned the object's `Input` field in the InputFilter
 * (which assumes that the value would be from a HTML form)
 *
 *   - DateTime, Date, Time core elements
 *   - DoctrineModule's Object(Select|Option|Checkbox) element
 *
 * Solution:
 * #########
 *
 * Override `extract` and `getData` and inject a sanitizer which iterates
 * over the data extracted from the form and replaces any inappropriate
 * objects it finds with a properly serialized equivalent that InputFilter
 * won't shit a brick over
 *
 * @author Adam Lundrigan <adam@lundrigan.ca
 *
 */
class HackedBaseForm extends Form
{

    /**
     * Recursively extract values for elements and sub-fieldsets, and populate form values
     * @HACK nuke stray objects from the result so InputFilter will shut it's pie hole
     *
     * @return array
     */
    protected function extract()
    {
        if (null !== $this->baseFieldset) {
            $name = $this->baseFieldset->getName();
            $values[$name] = $this->baseFieldset->extract();
            $this->nukeStrayObjectsFromTheResult($this, $values);
            $this->baseFieldset->populateValues($values[$name]);
        } else {
            $values = parent::extract();
            $this->nukeStrayObjectsFromTheResult($this, $values);
            $this->populateValues($values);
        }

        return $values;
    }

    /**
     * Retrieve the validated data
     *
     * By default, retrieves normalized values; pass one of the
     * FormInterface::VALUES_* constants to shape the behavior.
     *
     * @HACK nuke stray objects from the result so InputFilter will shut it's pie hole
     * (unless a hydrated object is requested, then we don't do that)
     *
     * @param  int                       $flag
     * @return array|object
     * @throws Exception\DomainException
     */
    public function getData($flag = FormInterface::VALUES_NORMALIZED)
    {
        $result = parent::getData($flag);
        if (($flag !== FormInterface::VALUES_AS_ARRAY) && is_object($this->object)) {
            return $result;
        }

        $this->nukeStrayObjectsFromTheResult($this, $result);

        return $result;
    }

    protected function nukeStrayObjectsFromTheResult($element, &$data)
    {
        foreach ($data as $childKey => &$childValue) {
            if ( ! $element instanceof FieldsetInterface || ! $element->has($childKey) ) {
                if ($element instanceof Collection) {
                    $this->nukeStrayObjectsFromTheResult($element->getTargetElement(), $childValue);
                    continue;
                }

                if ( ! is_object($childValue) ) {
                    continue;
                }

                if ($childValue instanceof \Traversable) {
                    $data[$childKey] = ArrayUtils::iteratorToArray($childValue);
                }

                if ( is_callable(array($childValue, 'getId')) ) {
                    $data[$childKey] = $childValue->getId();
                    continue;
                }

                if ( ! is_scalar($childValue) ) {
                    $childValue = null;
                }
                continue;
            }
            $childElement = $element->get($childKey);

            if ($childElement instanceof FieldsetInterface) {
                $this->nukeStrayObjectsFromTheResult($childElement, $childValue);
                continue;
            }

            if ($childElement instanceof DateTime && $childValue instanceof \DateTime) {
                $data[$childKey] = $childValue->format($childElement->getFormat());
                continue;
            }

            if ($childElement instanceof ObjectSelect || $childElement instanceof ObjectMultiCheckbox || $childElement instanceof ObjectRadio) {
                $data[$childKey] = $childElement->getProxy()->getValue($childValue);
                continue;
            }

            if ( ! is_object($childValue) ) {
                continue;
            }

            if ($childValue instanceof \Traversable) {
                $data[$childKey] = ArrayUtils::iteratorToArray($childValue);
            }

            if ( is_callable(array($childValue, 'getId')) ) {
                $data[$childKey] = $childValue->getId();
                continue;
            }
        }
    }
}
