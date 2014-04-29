<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

class TeamMembershipTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new TeamMembership();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testCanBeConvertedToString()
    {
        $mockTeam = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getName')->andReturn('Foobar');

        $obj = new TeamMembership();
        $obj->setTeam($mockTeam);

        $this->assertTrue(method_exists($obj, '__toString'));
        (string) $obj;
    }

    public function testGetSetCompetition()
    {
        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        $obj = new TeamMembership();

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(NULL);
        $this->assertNull($obj->getCompetition());
    }

    public function testGetSetTeam()
    {
        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        $obj = new TeamMembership();

        // Test setting to an instance of Team class
        $obj->setTeam($comp);
        $this->assertSame($comp, $obj->getTeam());

        // Test setting to null (disassociate from team)
        $obj->setTeam(NULL);
        $this->assertNull($obj->getTeam());
    }

    public function testGetSetDivision()
    {
        $div = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Division');
        $div->shouldReceive('getCompetition')->andReturnNull();

        $obj = new TeamMembership();

        // Test setting to an instance of Competition\Division class
        $obj->setDivision($div);
        $this->assertSame($div, $obj->getDivision());

        // Test setting to null (disassociate from division)
        $obj->setDivision(NULL);
        $this->assertNull($obj->getDivision());
    }
}
