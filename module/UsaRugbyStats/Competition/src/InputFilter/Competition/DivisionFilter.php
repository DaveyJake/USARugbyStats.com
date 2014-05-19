<?php
namespace UsaRugbyStats\Competition\InputFilter\Competition;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Competition Division Input Filter
 *
 * @author Adam Lundrigan <adam@lundrigan.ca>
 */
class DivisionFilter extends InputFilter
{
    public function __construct(InputFilterInterface $ifTeamMembership)
    {

        $this->add(array(
            'name'       => 'id',
            'required'   => false,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'Digits'),
            ),
        ));

        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'validators' => array(),
            'filters'   => array(
                array('name' => 'StringTrim'),
            ),
        ));

        $cif = new TeamMembershipCollectionFilter($ifTeamMembership);
        $this->add($cif, 'teamMemberships');
    }
}
