<?php
namespace UsaRugbyStatsTest\Account\Entity\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin;
use Doctrine\Common\Collections\ArrayCollection;

class TeamAdminTest extends ServiceManagerTestCase
{
    public function testSetManagedTeams()
    {
        $obj = new TeamAdmin();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        // Add one to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team2);

        $newCollection = new ArrayCollection();
        $newCollection->add($team0);
        $newCollection->add($team1);

        // Do the add
        $obj->setManagedTeams($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedTeams());
        $this->assertEquals(2, $obj->getManagedTeams()->count());
        $this->assertTrue($obj->getManagedTeams()->contains($team0));
        $this->assertTrue($obj->getManagedTeams()->contains($team1));
        $this->assertFalse($obj->getManagedTeams()->contains($team2));
    }

    public function testAddManagedTeams()
    {
        $obj = new TeamAdmin();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        // Add one to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team2);

        $newCollection = new ArrayCollection();
        $newCollection->add($team0);
        $newCollection->add($team1);

        // Do the add
        $obj->addManagedTeams($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedTeams());
        $this->assertEquals(3, $obj->getManagedTeams()->count());
        $this->assertTrue($obj->getManagedTeams()->contains($team0));
        $this->assertTrue($obj->getManagedTeams()->contains($team1));
        $this->assertTrue($obj->getManagedTeams()->contains($team2));
    }

    public function testAddManagedTeam()
    {
        $obj = new TeamAdmin();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

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

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        // Add roles to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team0);

        $this->assertTrue($obj->hasManagedTeam($team0));
        $this->assertFalse($obj->hasManagedTeam($team1));
    }

    public function testRemoveManagedTeam()
    {
        $obj = new TeamAdmin();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        // Add both to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team0);
        $collection->add($team1);

        // Do the remove of 0
        $obj->removeManagedTeam($team0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedTeams());
        $this->assertEquals(1, $obj->getManagedTeams()->count());
        $this->assertFalse($obj->getManagedTeams()->contains($team0));
        $this->assertTrue($obj->getManagedTeams()->contains($team1));
    }

    public function testRemoveManagedTeams()
    {
        $obj = new TeamAdmin();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $team2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        // Add them all to the existing collection
        $collection = $obj->getManagedTeams();
        $collection->add($team0);
        $collection->add($team1);
        $collection->add($team2);

        $newCollection = new ArrayCollection();
        $newCollection->add($team0);
        $newCollection->add($team1);

        // Do the remove of 0 and 1
        $obj->removeManagedTeams($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getManagedTeams());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getManagedTeams());
        $this->assertEquals(1, $obj->getManagedTeams()->count());
        $this->assertFalse($obj->getManagedTeams()->contains($team0));
        $this->assertFalse($obj->getManagedTeams()->contains($team1));
        $this->assertTrue($obj->getManagedTeams()->contains($team2));
    }

    public function testGetDiscriminator()
    {
        $obj = new TeamAdmin();
        $this->assertEquals('team_admin', $obj->getDiscriminator());
    }
}
