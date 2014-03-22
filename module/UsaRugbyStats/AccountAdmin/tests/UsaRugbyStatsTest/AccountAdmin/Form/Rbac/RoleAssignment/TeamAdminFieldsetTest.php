<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset;

class TeamAdminFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
         
        $fieldset = new TeamAdminFieldset($om, $or);
        
        $this->assertEquals('team-admin', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));
        
        $inputFilter = $fieldset->getInputFilterSpecification();
        $this->assertArrayHasKey('id', $inputFilter);
        $this->assertArrayHasKey('type', $inputFilter);
    }
    
    public function testGetTeam()
    {
        $team = new \stdClass();
        
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('find')->once()->andReturn($team);
         
        $fieldset = new TeamAdminFieldset($om, $or);
        $this->assertSame($team, $fieldset->getTeam(999));
    }

    public function testGetTeamWithEmptyTeamId()
    {
        $team = new \stdClass();
    
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('find')->never();
         
        $fieldset = new TeamAdminFieldset($om, $or);
        $this->assertNull($fieldset->getTeam(''));
    }
}
