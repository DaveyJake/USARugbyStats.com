<?php
namespace UsaRugbyStatsTest\Account\Controller\Plugin;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use Doctrine\Common\Collections\ArrayCollection;


class TeamAdminTest extends ServiceManagerTestCase
{
    public function testSetManagedTeams()
    {
        $obj = new TeamAdmin();
        $collection = $obj->getManagedTeams();
        
        $team0 = Mockery::mock('UsaRugbyStats\Application\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Application\Entity\Team');
        
        $newCollection = new ArrayCollection();
        $newCollection->add($team0);
        $newCollection->add($team1);
        
        // Do the add
        $obj->setManagedTeams($newCollection);
        
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedTeams());
        $this->assertEquals(2, $obj->getManagedTeams()->count());
    }
    
    public function testAddManagedTeam()
    {
        $obj = new TeamAdmin();
    
        $team0 = Mockery::mock('UsaRugbyStats\Application\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Application\Entity\Team');
    
        // Add one to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team0);
        
        // Do teh add
        $obj->addManagedTeam($team1);
    
        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedTeams());
        $this->assertEquals(2, $obj->getManagedTeams()->count());
    }
    
    public function testHasManagedTeam()
    {
        $obj = new TeamAdmin();
    
        $team0 = Mockery::mock('UsaRugbyStats\Application\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Application\Entity\Team');
    
        // Add roles to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team0);
    
        $this->assertTrue($obj->hasManagedTeam($team0));
        $this->assertFalse($obj->hasManagedTeam($team1));
    }

}