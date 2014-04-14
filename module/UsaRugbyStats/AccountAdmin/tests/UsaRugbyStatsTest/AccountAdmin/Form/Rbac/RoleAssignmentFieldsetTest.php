<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac;

use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset;

class RoleAssignmentFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $fieldset = new LocalRoleAssignmentFieldset();
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));

        $inputFilter = $fieldset->getInputFilterSpecification();
        $this->assertArrayHasKey('id', $inputFilter);
        $this->assertArrayHasKey('type', $inputFilter);
    }

}

class LocalRoleAssignmentFieldset extends RoleAssignmentFieldset {}
