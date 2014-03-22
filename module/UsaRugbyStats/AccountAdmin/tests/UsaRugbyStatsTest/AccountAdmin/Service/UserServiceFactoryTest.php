<?php
namespace UsaRugbyStatsTest\AccountAdmin\Service;

use Mockery;
use UsaRugbyStats\AccountAdmin\Service\UserServiceFactory;

class UserServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateWhenThereAreNoRoleAssignmentTypesAvailable()
    {
        $mockElement = Mockery::mock('Zend\Form\Element\Collection');
        $mockElement->shouldReceive('getTargetElement')->andReturn(array());
        
        $mockLocator = Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockLocator->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement'])->andReturn($mockElement);
        
        $factory = new UserServiceFactory();
        $obj = $factory->createService($mockLocator);
        
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Service\UserService', $obj);
        $this->assertEmpty($obj->getAvailableRoleAssignments());
    }
    
    public function testCreateWhenThereAreRoleAssignmentTypesAvailable()
    {
        $mockType = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockType->shouldReceive('getName')->andReturn('foo');
        $mockType->shouldReceive('getObject')->andReturn(new \stdClass());
        
        $mockElement = Mockery::mock('Zend\Form\Element\Collection');
        $mockElement->shouldReceive('getTargetElement')->andReturn(array($mockType));
    
        $mockLocator = Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $mockLocator->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement'])->andReturn($mockElement);
    
        $factory = new UserServiceFactory();
        $obj = $factory->createService($mockLocator);
    
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Service\UserService', $obj);
        
        $set = $obj->getAvailableRoleAssignments();
        $this->assertInternalType('array', $set);
        $this->assertCount(1, $set);
        $this->assertArrayHasKey('foo', $set);
        $this->assertArrayHasKey('name', $set['foo']);
        $this->assertEquals('foo', $set['foo']['name']);
        $this->assertArrayHasKey('fieldset_class', $set['foo']);
        $this->assertEquals(get_class($mockType), $set['foo']['fieldset_class']);
        $this->assertArrayHasKey('entity_class', $set['foo']);
        $this->assertEquals('stdClass', $set['foo']['entity_class']);
    }
}