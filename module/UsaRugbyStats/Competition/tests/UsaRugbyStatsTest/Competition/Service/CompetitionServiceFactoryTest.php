<?php
namespace UsaRugbyStatsTest\Competition\Service;

use Mockery;
use Zend\ServiceManager\ServiceManager;
use UsaRugbyStats\Competition\Service\CompetitionServiceFactory;

class CompetitionServiceFactoryTest extends \PHPUnit_Framework_TestCase
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

        $sl->setService('usarugbystats_competition_competition_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition_competition_updateform', $mockUpdateForm);

        $sl->setService('Config', []);

        $factory = new CompetitionServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\CompetitionService', $obj);
        $this->assertSame($mockObjectManager, $obj->getObjectManager());
        $this->assertSame($mockObjectRepository, $obj->getRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
        $this->assertNull($obj->getExtensionManager());
    }

    public function testCreateWithServiceExtensionManagerEnabled()
    {
        $sl = new ServiceManager();
        $sl->setAllowOverride(true);

        $mockObjectRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager    = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockObjectRepository);
        $sl->setService('zfcuser_doctrine_em', $mockObjectManager);

        $mockCreateForm = Mockery::mock('Zend\Form\FormInterface');
        $mockUpdateForm = Mockery::mock('Zend\Form\FormInterface');

        $sl->setService('usarugbystats_competition_competition_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition_competition_updateform', $mockUpdateForm);

        $sl->setService('Config', ['usarugbystats' => ['service_extensions' => ['usarugbystats_competition_competition_service' => []]]]);

        $factory = new CompetitionServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\CompetitionService', $obj);
        $this->assertSame($mockObjectManager, $obj->getObjectManager());
        $this->assertSame($mockObjectRepository, $obj->getRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
        $this->assertNotNull($obj->getExtensionManager());
    }
}
