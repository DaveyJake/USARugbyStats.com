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
}
