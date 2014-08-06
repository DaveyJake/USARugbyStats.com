<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition\Match;

use Zend\InputFilter\CollectionInputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Match Signature Collection Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class MatchSignatureCollectionFilter extends CollectionInputFilter
{
    public function __construct(InputFilterInterface $ifMatchSignature)
    {
        $this->setInputFilter($ifMatchSignature);
    }

    public function isValid()
    {
        parent::isValid();

        // @TODO better way to ensure collection has unique signature types?
        $values = $this->getValues();
        $types = [];
        foreach ($values as $key=>$signature) {
            if ( in_array($signature['type'], $types) ) {
                $this->collectionMessages[$key] = ['type' => ['Already added!']];
                $result = false;
            }
            array_push($types, $signature['type']);
        }

        return $result;
    }

}
