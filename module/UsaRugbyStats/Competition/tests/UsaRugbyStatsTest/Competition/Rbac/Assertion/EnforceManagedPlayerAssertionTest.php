<?php
namespace UsaRugbyStatsTest\Competition\Rbac\Assertion;

use UsaRugbyStats\Competition\Rbac\Assertion\EnforceManagedPlayerAssertion;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\TeamMembership;

class EnforceManagedPlayerAssertionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->authService = \Mockery::mock('ZfcRbac\Service\AuthorizationService');

        $this->adminUser = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface');
        $this->playerUser = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface');

        $this->assertion = new EnforceManagedPlayerAssertion();

        $this->mockTeamOne = new Team();
        $this->mockTeamOne->setId(1);
        $this->mockTeamTwo = new Team();
        $this->mockTeamTwo->setId(2);
        $this->mockTeamThree = new Team();
        $this->mockTeamThree->setId(3);

        $this->mockMemberships = new ArrayCollection();
        $memberOne = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $memberOne->shouldReceive('getTeam')->andReturn($this->mockTeamOne);
        $memberTwo = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team\Member');
        $memberTwo->shouldReceive('getTeam')->andReturn($this->mockTeamThree);
        $this->mockMemberships->add($memberOne);
        $this->mockMemberships->add($memberTwo);
    }

    public function testAssertWhenAuthorizedUserIsNotAnRbacUser()
    {
        $this->authService->shouldReceive('getIdentity')->andReturn(null);
        $this->assertFalse($this->assertion->assert($this->authService));
    }

    public function testAssertWhenPlayerIsNotAnRbacUser()
    {
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);
        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertFalse($this->assertion->assert($this->authService, null));
    }

    public function testAssertAlwaysTrueWhenAuthorizedUserIsSuperAdmin()
    {
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(true);
        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertTrue($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertPlayerWithoutAnyMemberhsipRolesResultsInNoPermission()
    {
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn(null);

        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);
        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertFalse($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertPlayerWithoutAnyTeamsResultsInNoPermission()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn(new ArrayCollection());
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);
        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertFalse($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertEnforcementOfCompetitionAdminPrivilegeWhenCompetitionHasPlayerTeam()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn($this->mockMemberships);
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $mockComp = new Competition();
        $mockComp->addTeamMembership((new TeamMembership())->setTeam($this->mockTeamOne));
        $mockComps = new ArrayCollection();
        $mockComps->add($mockComp);

        $mockCompAdmin = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin');
        $mockCompAdmin->shouldReceive('getManagedCompetitions')->once()->andReturn($mockComps);

        $this->adminUser->shouldReceive('getRoleAssignment')->with('competition_admin')->once()->andReturn($mockCompAdmin);
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);

        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertTrue($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertEnforcementOfCompetitionAdminPrivilegeWhenCompetitionDoesNotHavePlayerTeam()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn($this->mockMemberships);
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $mockComp = new Competition();
        $mockComp->addTeamMembership((new TeamMembership())->setTeam($this->mockTeamTwo));
        $mockComps = new ArrayCollection();
        $mockComps->add($mockComp);

        $mockCompAdmin = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\CompetitionAdmin');
        $mockCompAdmin->shouldReceive('getManagedCompetitions')->twice()->andReturn($mockComps);

        $this->adminUser->shouldReceive('getRoleAssignment')->with('competition_admin')->twice()->andReturn($mockCompAdmin);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('team_admin')->twice()->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('union_admin')->twice()->andReturn(null);
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);

        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertFalse($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertEnforcementOfTeamAdminPrivilegeWhenAdminManagesPlayersTeam()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn($this->mockMemberships);
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $mockTeamAdmin = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin');
        $mockTeamAdmin->shouldReceive('hasManagedTeam')->once()->andReturn(true);

        $this->adminUser->shouldReceive('getRoleAssignment')->with('team_admin')->andReturn($mockTeamAdmin);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('competition_admin')->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('union_admin')->andReturn(null);
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);

        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertTrue($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertEnforcementOfTeamAdminPrivilegeWhenAdminDoesNotManagePlayersTeam()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn($this->mockMemberships);
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $this->adminUser->shouldReceive('getRoleAssignment')->with('team_admin')->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('competition_admin')->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('union_admin')->andReturn(null);
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);

        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertFalse($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertEnforcementOfUnionAdminPrivilegeWhenAdminManagesPlayersTeam()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn($this->mockMemberships);
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $mockUnionAdmin = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin');
        $mockUnionAdmin->shouldReceive('hasManagedTeam')->once()->andReturn(true);

        $this->adminUser->shouldReceive('getRoleAssignment')->with('union_admin')->andReturn($mockUnionAdmin);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('competition_admin')->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('team_admin')->andReturn(null);
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);

        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertTrue($this->assertion->assert($this->authService, $this->playerUser));
    }

    public function testAssertEnforcementOfUnionAdminPrivilegeWhenAdminDoesNotManagePlayersTeam()
    {
        $role = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member');
        $role->shouldReceive('getMemberships')->andReturn($this->mockMemberships);
        $this->playerUser->shouldReceive('getRoleAssignment')->andReturn($role);

        $this->adminUser->shouldReceive('getRoleAssignment')->with('team_admin')->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('competition_admin')->andReturn(null);
        $this->adminUser->shouldReceive('getRoleAssignment')->with('union_admin')->andReturn(null);
        $this->adminUser->shouldReceive('hasRole')->with('super_admin')->andReturn(false);

        $this->authService->shouldReceive('getIdentity')->andReturn($this->adminUser);
        $this->assertFalse($this->assertion->assert($this->authService, $this->playerUser));
    }
}
