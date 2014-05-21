<?php
namespace UsaRugbyStats\Application\Common;

use Zend\InputFilter\CollectionInputFilter;

/**
 * Modifies CollectionInputFilter::isValid to clone the provided
 * base input filter for each element of the collection
 * @see https://github.com/zendframework/zf2/issues/6304
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class NestedCollectionInputFilter extends CollectionInputFilter
{
    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        //$inputFilter = $this->getInputFilter();  // Removed
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
            $inputFilter = clone $this->getInputFilter();  // Added
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