<?php
namespace UsaRugbyStats\AccountAdmin\Form\Element;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\BaseInputFilter;
use Zend\InputFilter\Exception;

class NonuniformCollectionInputFilter extends CollectionInputFilter
{

    /**
     * @var array<BaseInputFilter>
     */
    protected $inputFilter;

    /**
     * Set the input filters to use when looping the data
     *
     * @param  array<BaseInputFilter|array|Traversable> $filterSet
     * @throws Exception\RuntimeException
     * @return CollectionInputFilter
     */
    public function setInputFilter($filterSet)
    {
        foreach ($filterSet as $key=>$inputFilter) {
            if (is_array($inputFilter) || $inputFilter instanceof \Traversable) {
                $inputFilter = $this->getFactory()->createInputFilter($inputFilter);
            }

            if (!$inputFilter instanceof BaseInputFilter) {
                throw new Exception\RuntimeException(sprintf(
                    '%s expects an instance of %s; received "%s"',
                    __METHOD__,
                    'Zend\InputFilter\BaseInputFilter',
                    (is_object($inputFilter) ? get_class($inputFilter) : gettype($inputFilter))
                ));
            }

            $this->inputFilter[$key] = $inputFilter;
        }

        return $this;
    }

    /**
     * Get the input filters used when looping the data
     *
     * @return array<BaseInputFilter>
     */
    public function getInputFilter()
    {
        return $this->inputFilter;
    }

    /**
     * Get the input filter by key
     *
     * @return BaseInputFilter
     * @throws RuntimeException
     */
    public function getInputFilterFor($key)
    {
        if ( isset($this->inputFilter[$key]) ) {
            return $this->inputFilter[$key];
        }
        throw new \RuntimeException('No input filter found for ' . $key);
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        $valid = true;

        if ($this->getCount() < 1) {
            if ($this->isRequired) {
                $valid = false;
            }
        }

        if (count($this->data) < $this->getCount()) {
            $valid = false;
        }

        if (empty($this->data)) {
            $this->clearValues();
            $this->clearRawValues();

            return $valid;
        }

        foreach ($this->data as $key => $data) {
            if (!is_array($data)) {
                $data = array();
            }
            $inputFilter = $this->getInputFilterFor($data['__class__']);
            $inputFilter->setData($data);

            if (null !== $this->validationGroup) {
                $inputFilter->setValidationGroup($this->validationGroup[$key]);
            }

            if ($inputFilter->isValid()) {
                $this->validInputs[$key] = $inputFilter->getValidInput();
            } else {
                $valid = false;
                $this->collectionMessages[$key] = $inputFilter->getMessages();
                $this->invalidInputs[$key] = $inputFilter->getInvalidInput();
            }

            $this->collectionValues[$key] = $inputFilter->getValues();
            $this->collectionRawValues[$key] = $inputFilter->getRawValues();
        }

        return $valid;
    }

}
