<?php
namespace UsaRugbyStatsTest\RemoteDataSync\Jobs;

use UsaRugbyStats\RemoteDataSync\Jobs\SyncPlayer;
use Zend\Log\Logger;
use UsaRugbyStats\Account\Entity\Account;
use UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member;
use UsaRugbyStats\Account\Entity\Rbac\Role;

class SyncPlayerTest extends AbstractJobTest
{
    public function setUp()
    {
        $this->mockServiceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorAwareInterface');

        $this->mockEventManager = \Mockery::mock('Zend\EventManager\SharedEventManagerInterface');

        $this->mockUserService = \Mockery::mock('UsaRugbyStats\AccountAdmin\Service\UserService');

        $this->mockCreateForm = \Mockery::mock('Zend\Form\Form');

        $this->mockEditForm = \Mockery::mock('Zend\Form\Form');

        $this->mockLogger = new Logger();
        $this->mockLoggerWriter = new \Zend\Log\Writer\Mock();
        $this->mockLogger->addWriter($this->mockLoggerWriter);

        $this->job = new SyncPlayer();
        $this->job->setLogger($this->mockLogger);
        $this->job->setUserService($this->mockUserService);
        $this->job->setUserCreateForm($this->mockCreateForm);
        $this->job->setUserUpdateForm($this->mockEditForm);

        $this->job->args = [];
        $this->job->job = new \stdClass();
        $this->job->job->serviceLocator = $this->mockServiceLocator;
        $this->job->job->sharedEventManager = $this->mockEventManager;
    }

    public function testGetTeamServiceProxiesToServiceLocatorForLoad()
    {
        $service = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $this->mockServiceLocator->shouldReceive('get')->withArgs(['usarugbystats_competition_team_service'])->once()->andReturn($service);
        $this->assertSame($service, $this->job->getTeamService());
    }

    public function testExecuteFailsWhenNoPlayerDetailsAreSpecified()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->job->perform();
    }

    public function testUserAccountLoadedByProvidingIdDirectly()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');
        $this->mockUserService->shouldReceive('getUserMapper->findById')->withArgs([42])->once()->andReturn($acct);
        $this->job->args = ['player_id' => 42];
        $this->assertSame($acct, $this->job->loadReferencedUserAccount());
    }

    public function testUserAccountLoadedByProvidingIdDirectlyAndSavesRemoteId()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');
        $acct->shouldReceive('setRemoteId')->once();

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->withArgs([42])->once()->andReturn($acct);
        $mockMapper->shouldReceive('update')->once();
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->job->args = ['player_id' => 42, 'player_data' => ['ID' => '9999']];

        $this->assertSame($acct, $this->job->loadReferencedUserAccount());
    }

    public function testUserAccountLoadFailsIfInvalidIdIsProvided()
    {
        $this->mockUserService->shouldReceive('getUserMapper->findById')->withArgs([42])->once()->andReturnNull();
        $this->job->args = ['player_id' => 42];
        $this->assertNull($this->job->loadReferencedUserAccount());
    }

    public function testUserAccountLoadedByRemoteIdFromPlayerDataIfNoUserIdSpecifiedDirectly()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->never();
        $mockMapper->shouldReceive('findByRemoteId')->withArgs(['4242'])->once()->andReturn($acct);
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->job->args = ['player_data' => ['ID' => '4242']];
        $this->assertSame($acct, $this->job->loadReferencedUserAccount());
    }

    public function testUserAccountLoadedByEmailFromPlayerDataIfNoUserIdSpecifiedDirectly()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->never();
        $mockMapper->shouldReceive('findByRemoteId')->andReturnNull();
        $mockMapper->shouldReceive('findByEmail')->withArgs(['foo@bar.com'])->once()->andReturn($acct);
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->job->args = ['player_data' => ['Email' => 'foo@bar.com']];
        $this->assertSame($acct, $this->job->loadReferencedUserAccount());
    }

    public function testUserAccountLoadedByEmailFromPlayerDataIfNoUserIdSpecifiedDirectlyAndSavesRemoteId()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');
        $acct->shouldReceive('setRemoteId')->once();

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->never();
        $mockMapper->shouldReceive('findByRemoteId')->andReturnNull();
        $mockMapper->shouldReceive('findByEmail')->withArgs(['foo@bar.com'])->once()->andReturn($acct);
        $mockMapper->shouldReceive('update')->once();
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->job->args = ['player_data' => ['ID' => '9999', 'Email' => 'foo@bar.com']];
        $this->assertSame($acct, $this->job->loadReferencedUserAccount());
    }

    public function testNewUserAccountIsCreatedIfNoExistingAccountIsFound()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');

        $this->mockUserService->shouldReceive('create')->once()->andReturn($acct);

        $this->job->args = ['player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
            'club_ID'    => '42',
        ]];

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->never();
        $mockMapper->shouldReceive('findByRemoteId')->andReturnNull();
        $mockMapper->shouldReceive('findByEmail')->withArgs(['foo@bar.com'])->once()->andReturnNull();
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->assertSame($acct, $this->job->loadReferencedUserAccount());
    }

    public function testCreateNewUserAccountThrowExceptionOnFailure()
    {
        $this->mockUserService->shouldReceive('create')->once()->andReturnNull();
        $this->mockCreateForm->shouldReceive('getMessages');

        $this->setExpectedException('RuntimeException');

        $this->assertNull($this->job->createNewUserAccount([
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
            'club_ID'    => '42',
        ]));
    }

    public function testExceptionIsThrownWhenNoAccountIsLoadedOrCreated()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->andReturnNull();
        $mockMapper->shouldReceive('findByRemoteId')->andReturnNull();
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->setExpectedException('RuntimeException');

        $this->job->args = [ 'player_id' => 42 ];
        $this->assertSame($acct, $this->job->perform());
    }

    public function testAccountProfileIsUpdatedWhenExistingAccountIsLoadedFailsWhenProfileUpdateFails()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');
        $acct->shouldReceive('getId')->andReturn(42);
        $acct->shouldReceive('setRemoteId')->once();

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->withArgs([42])->once()->andReturn($acct);
        $mockMapper->shouldReceive('update');
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->mockUserService->shouldReceive('edit')->once()->andReturnNull();

        $this->mockEditForm->shouldReceive('bind');
        $this->mockEditForm->shouldReceive('isValid');
        $this->mockEditForm->shouldReceive('getData')->andReturn([]);
        $this->mockEditForm->shouldReceive('getMessages')->andReturn([]);

        $this->job->args = ['player_id' => 42, 'player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
        ]];

        $this->setExpectedException('RuntimeException');

        $this->job->perform();
    }

    public function testAccountProfileIsUpdatedWhenExistingAccountIsLoaded()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');
        $acct->shouldReceive('getId')->andReturn(42);
        $acct->shouldReceive('setRemoteId')->once();

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->withArgs([42])->once()->andReturn($acct);
        $mockMapper->shouldReceive('update');
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->mockUserService->shouldReceive('edit')->once()->andReturn($acct);

        $this->mockEditForm->shouldReceive('bind');
        $this->mockEditForm->shouldReceive('isValid');
        $this->mockEditForm->shouldReceive('getData')->andReturn([]);

        // We don't want to test the extprofile stuff here
        $this->mockServiceLocator->shouldReceive('has')->andReturn(false);

        $this->job->args = ['player_id' => 42, 'player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
        ]];

        $this->job->perform();
    }

    public function testAccountProfileUpdateTriggersUpdateOfExtprofileWhenItIsPresent()
    {
        $acct = \Mockery::mock('UsaRugbyStats\Account\Entity\Account');
        $acct->shouldReceive('getId')->andReturn(42);
        $acct->shouldReceive('setRemoteId')->once();

        $mockMapper = \Mockery::mock('ZfcUser\Mapper\UserInterface');
        $mockMapper->shouldReceive('findById')->withArgs([42])->once()->andReturn($acct);
        $mockMapper->shouldReceive('update');
        $this->mockUserService->shouldReceive('getUserMapper')->andReturn($mockMapper);

        $this->mockUserService->shouldReceive('edit')->once()->andReturn($acct);

        $this->mockEditForm->shouldReceive('bind');
        $this->mockEditForm->shouldReceive('isValid');
        $this->mockEditForm->shouldReceive('getData')->andReturn([]);

        $mockProfileForm = \Mockery::mock('Zend\Form\FormInterface');
        $mockProfileForm->shouldReceive('has')->withArgs(['extprofile'])->andReturn(true);
        $mockProfileForm->shouldReceive('isValid')->andReturn(true);
        $mockProfileForm->shouldReceive('getData')->andReturnNull();
        $mockProfileForm->shouldIgnoreMissing();

        $mockProfileService = \Mockery::mock('LdcUserProfile\Service\ProfileService');
        $mockProfileService->shouldReceive('constructFormForUser')->andReturn($mockProfileForm);
        $mockProfileService->shouldReceive('save');

        // We don't want to test the extprofile stuff here
        $this->mockServiceLocator->shouldReceive('has')->withArgs(['ldc-user-profile_service'])->andReturn(true);
        $this->mockServiceLocator->shouldReceive('get')->withArgs(['ldc-user-profile_service'])->andReturn($mockProfileService);

        $this->job->args = ['player_id' => 42, 'player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
            'Telephone'  => '+11234567890',
        ]];

        $this->job->perform();
    }

    public function testUpdateClubMembershipStatusForInvalidTeamShortCircuits()
    {
        $player = new Account();

        $mockTeamService = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $mockTeamService->shouldReceive('findByRemoteID')->andReturnNull();

        $this->job->setTeamService($mockTeamService);

        $this->job->args = ['player_id' => 42, 'player_data' => ['club_ID' => '99999']];

        $this->job->updateClubMembershipStatus($player, NULL, $this->job->args['player_data']);

        $lastEvent = array_pop($this->mockLoggerWriter->events);
        $this->assertStringStartsWith(' ** No local club record', $lastEvent['message']);
    }

    public function testUpdateClubMembershipStatusHappyCaseWithEmptyAccount()
    {
        $player = new Account();

        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');

        $mockTeamService = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $mockTeamService->shouldReceive('findByRemoteID')->andReturn($mockTeam);

        $this->job->setTeamService($mockTeamService);

        $this->job->args = ['player_id' => 42, 'player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
            'Membership_Status' => '2',
            'club_ID'    => '99999',
        ]];

        $this->job->updateClubMembershipStatus($player, NULL, $this->job->args['player_data']);

        $obj = $player->getRoleAssignment('member');
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member', $obj);
        $this->assertCount(1, $obj->getMemberships());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Team\Member', $obj->getMemberships()->current());
        $this->assertSame($mockTeam, $obj->getMemberships()->current()->getTeam());
        $this->assertEquals(2, $obj->getMemberships()->current()->getMembershipStatus());
    }

    public function testUpdateClubMembershipStatusHappyCaseWithAccountThatAlreadyHasADifferentTeamMembership()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getId')->andReturn(23);
        $mockTeam->shouldReceive('getRemoteId')->andReturn(99999);

        $mockTeamOther = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeamOther->shouldReceive('getId')->andReturn(82);
        $mockTeamOther->shouldReceive('getRemoteId')->andReturn(424242);

        $membership = new \UsaRugbyStats\Competition\Entity\Team\Member();
        $membership->setTeam($mockTeamOther);
        $membership->setMembershipStatus(4);

        $player = new Account();
        $role = new Member();
        $role->setRole(new Role('member'));
        $role->addMembership($membership);
        $player->addRoleAssignment($role);

        $mockTeamService = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $mockTeamService->shouldReceive('findByRemoteID')->andReturn($mockTeam);

        $this->job->setTeamService($mockTeamService);

        $this->job->args = ['player_id' => 42, 'player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
            'Membership_Status' => '2',
            'club_ID'    => '99999',
        ]];

        $this->job->updateClubMembershipStatus($player, NULL, $this->job->args['player_data']);

        $obj = $player->getRoleAssignment('member');
        $this->assertSame($role, $obj);
        $this->assertCount(2, $obj->getMemberships());

        $testobj = $obj->getMemberships()->current();
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Team\Member', $testobj);
        $this->assertSame($membership, $testobj);
        $this->assertSame($mockTeamOther, $testobj->getTeam());
        $this->assertEquals(4, $testobj->getMembershipStatus());

        $testobj = $obj->getMemberships()->next();
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Team\Member', $testobj);
        $this->assertSame($mockTeam, $testobj->getTeam());
        $this->assertEquals(2, $testobj->getMembershipStatus());
    }

    public function testUpdateClubMembershipStatusHappyCaseWithAccountThatAlreadyHasTheReferencedTeamMembership()
    {
        $mockTeam = \Mockery::mock('UsaRugbyStats\Competition\Entity\Team');
        $mockTeam->shouldReceive('getId')->andReturn(23);
        $mockTeam->shouldReceive('getRemoteId')->andReturn(99999);

        $membership = new \UsaRugbyStats\Competition\Entity\Team\Member();
        $membership->setTeam($mockTeam);
        $membership->setMembershipStatus(4);

        $player = new Account();
        $role = new Member();
        $role->setRole(new Role('member'));
        $role->addMembership($membership);
        $player->addRoleAssignment($role);

        $mockTeamService = \Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');
        $mockTeamService->shouldReceive('findByRemoteID')->andReturn($mockTeam);

        $this->job->setTeamService($mockTeamService);

        $this->job->args = ['player_id' => 42, 'player_data' => [
            'ID'         => '123456',
            'Email'      => 'foo@bar.com',
            'First_Name' => 'Testy',
            'Last_Name'  => 'McTesterson',
            'Membership_Status' => '2',
            'club_ID'    => '99999',
        ]];

        $this->job->updateClubMembershipStatus($player, NULL, $this->job->args['player_data']);

        $obj = $player->getRoleAssignment('member');
        $this->assertSame($role, $obj);
        $this->assertCount(1, $obj->getMemberships());
        $this->assertSame($membership, $obj->getMemberships()->current());
        $this->assertSame($mockTeam, $obj->getMemberships()->current()->getTeam());
        $this->assertEquals(2, $obj->getMemberships()->current()->getMembershipStatus());
    }
}
