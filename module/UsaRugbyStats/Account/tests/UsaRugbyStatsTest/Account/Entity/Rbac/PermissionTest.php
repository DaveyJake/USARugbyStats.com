<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Rbac\Permission;

class PermissionTest extends ServiceManagerTestCase
{
    public function testConstructorSetsPermissionName()
    {
        $obj = new Permission('foo');
        $this->assertEquals('foo', $obj->getName());
    }

    public function testConstructorSetPermissionNameNullWhenNotSpecified()
    {
        $obj = new Permission();
        $this->assertNull($obj->getName());
    }

    public function testIdentifier()
    {
        $obj = new Permission();
        $this->assertNull($obj->getId());
    
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    
        $obj->setId(NULL);
        $this->assertNull($obj->getId());
    }
    
    public function testName()
    {
        $obj = new Permission();
        $this->assertNull($obj->getName());
    
        $obj->setName('foobar');
        $this->assertEquals('foobar', $obj->getName());
    
        $obj->setName(NULL);
        $this->assertNull($obj->getName());
    }
    
    public function testToString()
    {
        $obj = new Permission('foo');
        $this->assertEquals('foo', (string)$obj);
    }
}