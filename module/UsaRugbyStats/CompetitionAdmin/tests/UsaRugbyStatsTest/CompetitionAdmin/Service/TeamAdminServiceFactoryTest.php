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

        $mockObjectRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager    = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockObjectRepository);
        $sl->setService('zfcuser_doctrine_em', $mockObjectManager);

        $mockCreateForm = Mockery::mock('Zend\Form\FormInterface');
        $mockUpdateForm = Mockery::mock('Zend\Form\FormInterface');

        $sl->setService('usarugbystats_competition-admin_team_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition-admin_team_updateform', $mockUpdateForm);

        $factory = new TeamAdminServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\CompetitionAdmin\Service\TeamAdminService', $obj);
        $this->assertSame($mockObjectManager, $obj->getObjectManager());
        $this->assertSame($mockObjectRepository, $obj->getRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
    }
}
