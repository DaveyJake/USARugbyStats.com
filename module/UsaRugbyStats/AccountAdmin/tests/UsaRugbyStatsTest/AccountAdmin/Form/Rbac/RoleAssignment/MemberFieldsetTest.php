<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset;

class MemberFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $fieldset = new MemberFieldset(Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Team\MemberFieldset'));

        $this->assertEquals('member', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));

        $inputFilter = $fieldset->getInputFilterSpecification();
        $this->assertArrayHasKey('id', $inputFilter);
        $this->assertArrayHasKey('type', $inputFilter);
    }

}
