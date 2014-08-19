<?php
namespace UsaRugbyStatsTest\Competition\Service;

use Mockery;
use Zend\ServiceManager\ServiceManager;
use UsaRugbyStats\Competition\Service\TeamServiceFactory;

class TeamServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $sl = new ServiceManager();
        $sl->setAllowOverride(true);

        $mockObjectManager    = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $mockTeamAdminRoleAssignmentRepository = Mockery::mock('UsaRugbyStats\Account\Repository\Rbac\RoleAssignment\TeamAdminRepository');
        $mockObjectManager->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin'])->andReturn($mockTeamAdminRoleAssignmentRepository);

        $mockTeamRepository = Mockery::mock('UsaRugbyStats\Account\Repository\Rbac\RoleAssignment\TeamAdminRepository');
        $mockObjectManager->shouldReceive('getRepository')->withArgs(['UsaRugbyStats\Competition\Entity\Team'])->andReturn($mockTeamRepository);

        $sl->setService('zfcuser_doctrine_em', $mockObjectManager);

        $mockCreateForm = Mockery::mock('Zend\Form\FormInterface');
        $mockUpdateForm = Mockery::mock('Zend\Form\FormInterface');

        $sl->setService('usarugbystats_competition_team_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition_team_updateform', $mockUpdateForm);
        $sl->setService('config', []);

        $factory = new TeamServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\TeamService', $obj);
        $this->assertSame($mockObjectManager, $obj->getObjectManager());
        $this->assertSame($mockTeamRepository, $obj->getRepository());
        $this->assertSame($mockTeamAdminRoleAssignmentRepository, $obj->getTeamAdminRoleAssignmentRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
    }
}
