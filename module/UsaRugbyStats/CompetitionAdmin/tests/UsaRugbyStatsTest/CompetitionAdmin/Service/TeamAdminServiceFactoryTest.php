<?php
namespace UsaRugbyStatsTest\CompetitionAdmin\Service;

use Mockery;
use Zend\ServiceManager\ServiceManager;
use UsaRugbyStats\CompetitionAdmin\Service\TeamAdminServiceFactory;

class TeamAdminServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $sl = new ServiceManager();
        $sl->setAllowOverride(true);

        $mockObjectManager    = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $mockAccountRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Account\Entity\Account'])->andReturn($mockAccountRepository);

        $mockTeamAdminRoleAssignmentRepository = Mockery::mock('UsaRugbyStats\Account\Repository\Rbac\RoleAssignment\TeamAdminRepository');
        $mockObjectManager->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin'])->andReturn($mockTeamAdminRoleAssignmentRepository);

        $mockTeamRepository = Mockery::mock('UsaRugbyStats\Account\Repository\Rbac\RoleAssignment\TeamAdminRepository');
        $mockObjectManager->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Competition\Entity\Team'])->andReturn($mockTeamRepository);

        $sl->setService('zfcuser_doctrine_em', $mockObjectManager);

        $mockCreateForm = Mockery::mock('Zend\Form\FormInterface');
        $mockUpdateForm = Mockery::mock('Zend\Form\FormInterface');

        $sl->setService('usarugbystats_competition-admin_team_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition-admin_team_updateform', $mockUpdateForm);
        $sl->setService('config', []);

        $factory = new TeamAdminServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\CompetitionAdmin\Service\TeamAdminService', $obj);
        $this->assertSame($mockObjectManager, $obj->getObjectManager());
        $this->assertSame($mockTeamRepository, $obj->getRepository());
        $this->assertSame($mockTeamAdminRoleAssignmentRepository, $obj->getTeamAdminRoleAssignmentRepository());
        $this->assertSame($mockAccountRepository, $obj->getAccountRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
    }
}
