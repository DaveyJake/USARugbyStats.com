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

        $factory = new CompetitionServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\CompetitionService', $obj);
        $this->assertSame($mockObjectManager, $obj->getCompetitionObjectManager());
        $this->assertSame($mockObjectRepository, $obj->getCompetitionRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
    }
}
