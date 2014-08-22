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
use UsaRugbyStats\Application\FeatureFlags\FeatureFlags;

class ExtendedValidationGroupForm extends HackedBaseForm implements EventManagerAwareInterface
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
     * Providing a set of feature flags matching the structure of the form
     * allows greater control over the validation group construction
     *
     * @var FeatureFlags
     */
    protected $featureFlags;

    /**
     * Override getValidationGroup to autogen the full VG if none is provided
     *
     * @see \Zend\Form\Form::getValidationGroup()
     */
    public function getValidationGroup()
    {
        if ( empty($this->validationGroup) ) {
            $this->getEventManager()->trigger('autogenerateValidationGroupForForm.pre', $this, []);
            $this->validationGroup = $this->autogenerateValidationGroupForForm($this);
            $this->getEventManager()->trigger('autogenerateValidationGroupForForm.post', $this, []);
        }

        return $this->validationGroup;
    }

    /**
     * Mutator method to allow external triggering generation of validation group
     */
    public function generateValidationGroup()
    {
        $validationGroup = $this->getValidationGroup();
        if ($validationGroup !== null) {
            $this->prepareValidationGroup($this, $this->data ?: array(), $validationGroup);
            $this->getInputFilter()->setValidationGroup($validationGroup);
        }
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

        $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $argv);

        if ( empty($argv->validationGroup) ) {
            $this->getEventManager()->trigger('autogenerateValidationGroupForForm.pre', $argv->formOrFieldset, []);
            $argv->validationGroup = $this->autogenerateValidationGroupForForm($argv->formOrFieldset);
            $this->getEventManager()->trigger('autogenerateValidationGroupForForm.post', $argv->formOrFieldset, []);
        }

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

    protected function recursivelyStripOutMissingFields(ElementInterface $formOrFieldset, InputFilterInterface $inputFilter, array $data, &$validationGroup)
    {
        if ($formOrFieldset instanceof NonuniformCollection) {
            $filters = $inputFilter->getInputFilter();
            foreach ($formOrFieldset as $collKey => $collElement) {
                if ( !isset($validationGroup[$collElement->getName()]) ) {
                    continue;
                }

                $discr = $data[$collElement->getName()][$formOrFieldset->getDiscriminatorFieldName()];
                $elementInputFilter = $filters[$discr];
                $elementData = @$data[$collElement->getName()] ?: array();
                $elementValidationGroup = $validationGroup[$collElement->getName()];

                $this->recursivelyStripOutMissingFields($collElement, $elementInputFilter, $elementData, $elementValidationGroup);
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

    protected function recursivelyPrepareValidationGroup(FieldsetInterface $formOrFieldset, array $data, array &$validationGroup, $fieldName = '')
    {
        $selfFieldName = $this->buildFieldNameString($fieldName, $formOrFieldset);

        foreach ($validationGroup as $key => $value) {
            if ($formOrFieldset->has($key)) {
                $fieldset = $formOrFieldset->byName[$key];
            } elseif ( is_scalar($value) && $formOrFieldset->has($value) ) {
                $fieldset = $formOrFieldset->get($value);
                $key = $value;
            } else {
                continue;
            }

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

                $validationGroup[$key] = $value = $values;
            }

            $elementFieldName = $this->buildFieldNameString($selfFieldName, $fieldset);

            if ( !empty($elementFieldName) && $this->isFlagOff($elementFieldName) ) {
                if ( isset($validationGroup[$key]) ) {
                    unset($validationGroup[$key]);
                } elseif ( in_array($key, $validationGroup, true) ) {
                    unset($validationGroup[array_search($key, $validationGroup, true)]);
                }
                continue;
            }

            // @see ZF2-6363
            if (!isset($data[$key])) {
                $data[$key] = array();
            }

            if (! $fieldset instanceof FieldsetInterface) {
                continue;
            }

            $this->recursivelyPrepareValidationGroup($fieldset, $data[$key], $validationGroup[$key], $selfFieldName);
        }
    }

    protected function buildFieldNameString($root, ElementInterface $nextElement)
    {
        if ($nextElement instanceof FormInterface) {
            return $root;
        }

        return empty($root)
            ? $nextElement->getName()
            : "{$root}.{$nextElement->getName()}";
    }

    protected function autogenerateValidationGroupForForm($element, $fieldName = '')
    {
        if ( ! is_array($element) && ! $element instanceof \Traversable ) {
            $element = (array) $element;
        }

        $names = [];
        foreach ($element as $elementOrFieldset) {
            $selfFieldName = $this->buildFieldNameString($fieldName, $elementOrFieldset);
            if ( $this->isFlagOff($selfFieldName) ) {
                continue;
            }

            // Don't push down any further if it's a NonuniformCollection
            // as validation group only support uniform collection contents
            if ($elementOrFieldset instanceof NonuniformCollection) {
                $targetElement = $elementOrFieldset->getTargetElement();
                $names[$elementOrFieldset->getName()] = array();
                foreach ($targetElement as $key => $prototype) {
                    $names[$elementOrFieldset->getName()][$key] = ArrayUtils::merge(
                        $this->autogenerateValidationGroupForForm($prototype->getElements(), $selfFieldName . '.*'),
                        $this->autogenerateValidationGroupForForm($prototype->getFieldsets(), $selfFieldName . '.*')
                    );
                }
            } elseif ($elementOrFieldset instanceof Collection) {
                $targetElement = $elementOrFieldset->getTargetElement();
                $names[$elementOrFieldset->getName()] = ArrayUtils::merge(
                    $this->autogenerateValidationGroupForForm($targetElement->getElements(), $selfFieldName . '.*'),
                    $this->autogenerateValidationGroupForForm($targetElement->getFieldsets(), $selfFieldName . '.*')
                );
            } elseif ($elementOrFieldset instanceof FieldsetInterface) {
                $names[$elementOrFieldset->getName()] = ArrayUtils::merge(
                    $this->autogenerateValidationGroupForForm($elementOrFieldset->getElements(), $selfFieldName),
                    $this->autogenerateValidationGroupForForm($elementOrFieldset->getFieldsets(), $selfFieldName)
                );
            } elseif ($elementOrFieldset instanceof ElementInterface) {
                array_push($names, $elementOrFieldset->getName());
            }
        }

        return $names;
    }

    /**
     * @return FeatureFlags
     */
    public function getFeatureFlags()
    {
        return $this->featureFlags;
    }

    /**
     * @param eatureFlags $ff
     */
    public function setFeatureFlags(FeatureFlags $ff = null)
    {
        $this->featureFlags = $ff;

        return $this;
    }

    protected function hasFlag($flag)
    {
        if ( ! $this->getFeatureFlags() ) {
            return false;
        }

        return $this->getFeatureFlags()->has($flag);
    }

    protected function isFlagOff($flag)
    {
        if ( ! $this->getFeatureFlags() || ! $this->hasFlag($flag) ) {
            return false;
        }

        return $this->getFeatureFlags()->$flag->is_off();
    }

}
