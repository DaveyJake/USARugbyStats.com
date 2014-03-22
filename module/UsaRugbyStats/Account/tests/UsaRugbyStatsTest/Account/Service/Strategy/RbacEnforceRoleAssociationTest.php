<?php
namespace UsaRugbyStatsTest\Account\Controller\Plugin;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation;

class RbacEnforceRoleAssociationTest extends ServiceManagerTestCase
{
    public function testAutoregistersForPrePersistEvent()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $obj = new RbacEnforceRoleAssociation($mockObjectManager);
        $this->assertInternalType('array', $obj->getSubscribedEvents());
        $this->assertContains(\Doctrine\ORM\Events::prePersist, $obj->getSubscribedEvents());
    }
    
    public function testPrePersistWithNoEntity()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive(Mockery::any())->never();
        
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);
        
        $obj = new RbacEnforceRoleAssociation($mockObjectManager);        
        $this->assertInstanceOf('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation', $obj);
        
        $event = Mockery::mock('Doctrine\Common\Persistence\Event\LifecycleEventArgs');
        $event->shouldReceive('getObject')->andReturnNull();
        
        $obj->prePersist($event);
    }
    
    public function testPrePersistWithNonRoleAssignmentEntity()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive(Mockery::any())->never();
    
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);
    
        $obj = new RbacEnforceRoleAssociation($mockObjectManager);
        $this->assertInstanceOf('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation', $obj);
    
        $event = Mockery::mock('Doctrine\Common\Persistence\Event\LifecycleEventArgs');
        $event->shouldReceive('getObject')->andReturn(new \stdClass);
    
        $obj->prePersist($event);
    }

    public function testPrePersistWithRoleAssignmentEntity()
    {
        $mockRole = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\Role');
        
        $mockRoleAssignment = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $mockRoleAssignment->shouldReceive('getDiscriminator')->andReturn('foo_bar');
        $mockRoleAssignment->shouldReceive('setRole')->withArgs([$mockRole])->andReturnNull();
        
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('findOneBy')->withArgs([['name' => 'foo_bar']])->andReturn($mockRole);
    
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);
    
        $obj = new RbacEnforceRoleAssociation($mockObjectManager);
        $this->assertInstanceOf('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation', $obj);
    
        $event = Mockery::mock('Doctrine\Common\Persistence\Event\LifecycleEventArgs');
        $event->shouldReceive('getObject')->andReturn($mockRoleAssignment);
    
        $obj->prePersist($event);
    }

    public function testPrePersistRoleAssignmentEntityWithNonexistentRole()
    {
        $mockRoleAssignment = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment');
        $mockRoleAssignment->shouldReceive('getDiscriminator')->andReturn('foo_bar');
        $mockRoleAssignment->shouldReceive('setRole')->never();
    
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('findOneBy')->withArgs([['name' => 'foo_bar']])->andReturnNull();
    
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);
    
        $obj = new RbacEnforceRoleAssociation($mockObjectManager);
        $this->assertInstanceOf('UsaRugbyStats\Account\Service\Strategy\RbacEnforceRoleAssociation', $obj);
    
        $event = Mockery::mock('Doctrine\Common\Persistence\Event\LifecycleEventArgs');
        $event->shouldReceive('getObject')->andReturn($mockRoleAssignment);
    
        $obj->prePersist($event);
    }
    
}