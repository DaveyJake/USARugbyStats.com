<?php
namespace UsaRugbyStats\Application\Common;

use Zend\Form\Form;
use Zend\Form\FieldsetInterface;
use LdcZendFormCTI\Form\Element\NonuniformCollection;
use Zend\Form\Element\Collection;
use Zend\Stdlib\ArrayUtils;
use Zend\Form\ElementInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\Form\FormInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\CollectionInputFilter;

class ExtendedValidationGroupForm extends Form implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;

    /**
     * Set the default value to empty array value so that
     * prepareValidationGroup is always triggered
     *
     * @var array
     */
    protected $validationGroup = array();

    /**
     * Set the validation group (set of values to validate)
     *
     * We've overridden it to not allow setting validationGroup to non-Array
     * (empty array triggers autogeneration of full validation group)
     *
     * @throws Exception\InvalidArgumentException
     * @return Form|FormInterface
     */
    public function setValidationGroup()
    {
        parent::setValidationGroup();

        if ( ! is_array($this->validationGroup) ) {
            $this->validationGroup = array();
        }

        return $this;
    }

    /**
     * Prepare the validation group in case Collection elements were used (this function also handle the case where elements
     * could have been dynamically added or removed from a collection using JavaScript)
     *
     * @param  FieldsetInterface $formOrFieldset
     * @param  array             $data
     * @param  array             $validationGroup
     * @throws RuntimeException  when first argument is not a form
     */
    protected function prepareValidationGroup(FieldsetInterface $formOrFieldset, array $data, array &$validationGroup)
    {
        if (! $formOrFieldset instanceof FormInterface) {
            throw new \RuntimeException('prepareValidationGroup can only be called on objects implementing FormInterface');
        }

        $argv = new \ArrayObject();
        $argv->formOrFieldset = &$formOrFieldset;
        $argv->data = &$data;
        $argv->validationGroup = &$validationGroup;

        if ( empty($argv->validationGroup) ) {
            $argv->validationGroup = $this->autogenerateValidationGroupForForm($argv->formOrFieldset);
        }

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);
        $this->recursivelyPrepareValidationGroup(
            $argv->formOrFieldset,
            $argv->data,
            $argv->validationGroup
        );
        $this->recursivelyStripOutMissingFields(
            $argv->formOrFieldset,
            $argv->formOrFieldset->getInputFilter(),
            $argv->data,
            $argv->validationGroup
        );
        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $argv);
    }

    protected function recursivelyStripOutMissingFields(ElementInterface $formOrFieldset, InputFilterInterface $inputFilter, array $data, array &$validationGroup)
    {
        if ($formOrFieldset instanceof NonuniformCollection) {
            $filters = $inputFilter->getInputFilter();
            foreach ($formOrFieldset as $collKey => $collElement) {
                if ( !isset($validationGroup[$collElement->getName()]) ) {
                    continue;
                }
                $discr = $data[$collKey][$formOrFieldset->getDiscriminatorFieldName()];
                $this->recursivelyStripOutMissingFields($collElement, $filters[$discr], @$data[$collElement->getName()] ?: array(), $validationGroup[$collElement->getName()]);
            }

            return;
        }

        if ($formOrFieldset instanceof Collection) {
            foreach ($formOrFieldset as $collKey => $collElement) {
                if ( !isset($validationGroup[$collElement->getName()]) ) {
                    continue;
                }
                $this->recursivelyStripOutMissingFields($collElement, $inputFilter instanceof CollectionInputFilter ? $inputFilter->getInputFilter() : $inputFilter, @$data[$collElement->getName()] ?: array(), $validationGroup[$collElement->getName()]);
            }

            return;
        }

        if ($formOrFieldset instanceof FieldsetInterface) {
            foreach ( $formOrFieldset->getFieldsets() as $fieldset ) {
                if ( !isset($validationGroup[$fieldset->getName()]) ) {
                    continue;
                }
                // If the fieldset isn't in the input filter, ditch it
                if ( !$inputFilter->has($fieldset->getName()) ) {
                    unset($validationGroup[$fieldset->getName()]);
                    continue;
                }
                $this->recursivelyStripOutMissingFields($fieldset, $inputFilter->get($fieldset->getName()), @$data[$fieldset->getName()] ?: array(), $validationGroup[$fieldset->getName()]);
            }
            foreach ( $formOrFieldset->getElements() as $element ) {
                $this->recursivelyStripOutMissingFields($element, $inputFilter, $data, $validationGroup);
            }

            return;
        }

        if ($formOrFieldset instanceof ElementInterface) {
            // If the element isn't in the input filter, ditch it
            if ( ! $inputFilter->has($formOrFieldset->getName()) ) {
                if ( isset($validationGroup[$formOrFieldset->getName()]) ) {
                    unset($validationGroup[$formOrFieldset->getName()]);

                    return;
                }
                $index = array_search($formOrFieldset->getName(), $validationGroup, true);
                if ($index !== false) {
                    unset($validationGroup[$index]);

                    return;
                }
            }
        }
    }

    protected function recursivelyPrepareValidationGroup(FieldsetInterface $formOrFieldset, array $data, array &$validationGroup)
    {
        foreach ($validationGroup as $key => &$value) {
            if (!$formOrFieldset->has($key)) {
                continue;
            }

            $fieldset = $formOrFieldset->byName[$key];

            if ($fieldset instanceof Collection) {
                // This looks to be what causes ZF2 #4492 (can't empty existing collection)
                if (!isset($data[$key]) && $fieldset->getCount() == 0) {
                    unset ($validationGroup[$key]);
                    continue;
                }

                $values = array();

                if (isset($data[$key])) {
                    foreach (array_keys($data[$key]) as $cKey) {
                        if ($fieldset instanceof NonuniformCollection) {
                            $instanceType = $data[$key][$cKey][$fieldset->getDiscriminatorFieldName()];
                            $values[$cKey] = $value[$instanceType];
                            continue;
                        }
                        $values[$cKey] = $value;
                    }
                }

                $value = $values;
            }

            // @see ZF2-6363
            if (!isset($data[$key])) {
                $data[$key] = array();
            }

            $this->recursivelyPrepareValidationGroup($fieldset, $data[$key], $validationGroup[$key]);
        }
    }

    protected function autogenerateValidationGroupForForm($element)
    {
        if ( ! is_array($element) && ! $element instanceof \Traversable ) {
            $element = (array) $element;
        }

        $names = [];
        foreach ($element as $elementOrFieldset) {
            // Don't push down any further if it's a NonuniformCollection
            // as validation group only support uniform collection contents
            if ($elementOrFieldset instanceof NonuniformCollection) {
                $targetElement = $elementOrFieldset->getTargetElement();
                $names[$elementOrFieldset->getName()] = array();
                foreach ($targetElement as $key => $prototype) {
                    $names[$elementOrFieldset->getName()][$key] = ArrayUtils::merge(
                        $this->autogenerateValidationGroupForForm($prototype->getElements()),
                        $this->autogenerateValidationGroupForForm($prototype->getFieldsets())
                    );
                }
            } elseif ($elementOrFieldset instanceof Collection) {
                $targetElement = $elementOrFieldset->getTargetElement();
                $names[$elementOrFieldset->getName()] = ArrayUtils::merge(
                    $this->autogenerateValidationGroupForForm($targetElement->getElements()),
                    $this->autogenerateValidationGroupForForm($targetElement->getFieldsets())
                );
            } elseif ($elementOrFieldset instanceof FieldsetInterface) {
                $names[$elementOrFieldset->getName()] = ArrayUtils::merge(
                    $this->autogenerateValidationGroupForForm($elementOrFieldset->getElements()),
                    $this->autogenerateValidationGroupForForm($elementOrFieldset->getFieldsets())
                );
            } elseif ($elementOrFieldset instanceof ElementInterface) {
                array_push($names, $elementOrFieldset->getName());
            }
        }

        return $names;
    }
}
