<?php
namespace UsaRugbyStatsTest\Competition\Entity\Team;

use Mockery;
use UsaRugbyStats\Competition\Entity\Team\Member;

class MembershipTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new Member();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetTeam()
    {
        $team = Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        $obj = new Member();

        // Test setting to an instance of Team class
        $obj->setTeam($team);
        $this->assertSame($team, $obj->getTeam());

        // Test setting to null (disassociate from team)
        $obj->setTeam(null);
        $this->assertNull($obj->getTeam());
    }

    public function testGetSetRole()
    {
        $role = Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');

        $obj = new Member();

        // Test setting to an instance of Role class
        $obj->setRole($role);
        $this->assertSame($role, $obj->getRole());

        // Test setting to null (disassociate from team)
        $obj->setRole(null);
        $this->assertNull($obj->getRole());
    }

    /**
     * @dataProvider providerGetSetMembershipStatus
     */
    public function testGetSetMembershipStatus($status, $valid)
    {
        $obj = new Member();
        $obj->setMembershipStatus($status);
        $this->assertEquals($valid ? $status : null, $obj->getMembershipStatus());
    }

    /**
     * Data Provider for testGetSetMembershipStatus
     *
     * @return array
     */
    public function providerGetSetMembershipStatus()
    {
        return [
            [null, true],
            [0, true],
            [1, true],
            [2, true],
            [3, true],
            [4, true],
            [99, false],
            ['', false],
        ];
    }
}
