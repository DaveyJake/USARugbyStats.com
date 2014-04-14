<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset;

class UnionAdminFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');

        $fieldset = new UnionAdminFieldset($om, $or);

        $this->assertEquals('union-admin', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));

        $inputFilter = $fieldset->getInputFilterSpecification();
        $this->assertArrayHasKey('id', $inputFilter);
        $this->assertArrayHasKey('type', $inputFilter);
    }

    public function testGetUnion()
    {
        $union = new \stdClass();

        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('find')->once()->andReturn($union);

        $fieldset = new UnionAdminFieldset($om, $or);
        $this->assertSame($union, $fieldset->getUnion(999));
    }

    public function testGetUnionWithEmptyUnionId()
    {
        $union = new \stdClass();

        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('find')->never();

        $fieldset = new UnionAdminFieldset($om, $or);
        $this->assertNull($fieldset->getUnion(''));
    }
}
