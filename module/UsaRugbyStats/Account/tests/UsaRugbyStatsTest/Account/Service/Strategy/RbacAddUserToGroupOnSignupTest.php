<?php
namespace UsaRugbyStatsTest\Service\Strategy;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use Zend\EventManager\Event;
use UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup;
use UsaRugbyStats\Account\Entity\Rbac\Role;
use UsaRugbyStats\Account\Entity\Account;

class RbacAddUserToGroupOnSignupTest extends ServiceManagerTestCase
{
    public function testAddUserToGroup()
    {
        $user = new Account();

        $repo = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $repo->shouldReceive('findOneBy')->withArgs([['name' => 'member']])->once()->andReturn(new Role('member'));
        $repo->shouldReceive('findOneBy')->withArgs([['name' => 'super_admin']])->once()->andReturn(new Role('super_admin'));

        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $om->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Account\Entity\Rbac\Role'])->once()->andReturn($repo);

        $target = Mockery::mock('ZfcUser\Service\User');
        $target->shouldReceive('getUserMapper->update')->withArgs([$user])->once()->andReturnNull();

        $e = new Event(NULL, $target, ['user' => $user]);

        $obj = new RbacAddUserToGroupOnSignup($om);
        $obj->setGroups(['member', 'super_admin']);
        $obj->addUserToGroup($e);

        $this->assertCount(2, $user->getRoleAssignments());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment', $user->getRoleAssignments()[0]);
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\Role', $user->getRoleAssignments()[0]->getRole());
        $this->assertEquals('member', $user->getRoleAssignments()[0]->getRole()->getName());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment', $user->getRoleAssignments()[1]);
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\Role', $user->getRoleAssignments()[1]->getRole());
        $this->assertEquals('super_admin', $user->getRoleAssignments()[1]->getRole()->getName());
    }

    public function testAddUserToNonexistentGroupIsIgnored()
    {
        $user = new Account();

        $repo = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $repo->shouldReceive('findOneBy')->withArgs([['name' => 'foo']])->once()->andReturnNull();

        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $om->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Account\Entity\Rbac\Role'])->once()->andReturn($repo);

        $target = Mockery::mock('ZfcUser\Service\User');
        $target->shouldReceive(Mockery::any())->never();

        $e = new Event(NULL, $target, ['user' => $user]);

        $obj = new RbacAddUserToGroupOnSignup($om);
        $obj->setGroups(['foo']);
        $obj->addUserToGroup($e);

        $this->assertCount(0, $user->getRoleAssignments());
    }
}
