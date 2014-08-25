<?php
namespace UsaRugbyStats\Application\Common;

use Zend\Form\Form;
use Zend\Form\FieldsetInterface;
use DoctrineModule\Form\Element\ObjectSelect as DoctrineObjectSelect;
use DoctrineModule\Form\Element\ObjectMultiCheckbox;
use DoctrineModule\Form\Element\ObjectRadio;
use Zend\Form\FormInterface;
use Zend\Form\Element\Collection;
use LdcZendFormCTI\Form\Element\NonuniformCollection;

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
            $this->nukeStrayObjectsFromTheResult($this, $values, $this->getName());
            $this->baseFieldset->populateValues($values[$name] ?: array());
        } else {
            $values = parent::extract();
            $this->nukeStrayObjectsFromTheResult($this, $values, $this->getName());
            $this->populateValues($values ?: array());
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

        $this->nukeStrayObjectsFromTheResult($this, $result, $this->getName());

        return $result;
    }

    protected function nukeStrayObjectsFromTheResult($element, &$data, $tracker = 'root')
    {
        // If the value is scalar, our work is done
        if ( is_scalar($data) ) {
            return;
        }

        // The chain is broken!
        if ( $element == NULL || empty($data) ) {
            $data = null;

            return;
        }

        // If it's a collection we bore down into it's contents
        if ($element instanceof NonuniformCollection) {
            foreach ($data as $k => &$v) {
                $discr = $v[$element->getDiscriminatorFieldName()];
                $childElement = $element->has($k) ? $element->get($k) : $element->getTargetElement()[$discr];
                $this->nukeStrayObjectsFromTheResult($childElement, $v, "{$tracker}.{$k}");
            }

            return;
        }

        // If it's a collection we bore down into it's contents
        if ($element instanceof Collection) {
            foreach ($data as $k => &$v) {
                $childElement = $element->has($k) ? $element->get($k) : $element->getTargetElement();
                $this->nukeStrayObjectsFromTheResult($childElement, $v, "{$tracker}.{$k}");
            }

            return;
        }

        // If it's an array or traversable we treat it like a fieldset
        if ( is_array($data) || $data instanceof \Traversable ) {
            foreach ($data as $k => &$v) {
                $childElement = $element instanceof FieldsetInterface && $element->has($k) ? $element->get($k) : null;
                $this->nukeStrayObjectsFromTheResult($childElement, $v, "{$tracker}.{$k}");
                continue;
            }

            return;
        }

        // If it's a datetime form element, format the output appropriately
        if ($element instanceof \Zend\Form\Element\DateTime && $data instanceof \DateTime) {
            $data = $data->format($element->getFormat());

            return;
        }

        // If it's a Doctrine form element pull the raw value from the proxy
        // ($element->getValue() returns an entity)
        if ($element instanceof DoctrineObjectSelect || $element instanceof ObjectMultiCheckbox || $element instanceof ObjectRadio) {
            $data = $element->getProxy()->getValue($data);

            return;
        }

        // If the object has an ID getter, use it
        if ( is_object($data) && method_exists($data, 'getId') ) {
            $data = $data->getId();

            return;
        }

        // If the object has a string transformer, use it
        if ( is_object($data) && method_exists($data, '__toString') ) {
            $data = (string) $data;

            return;
        }

        // You can't handle this data!
        unset($data);
    }
}
