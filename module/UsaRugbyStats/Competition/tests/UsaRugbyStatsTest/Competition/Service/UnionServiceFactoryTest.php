<?php
namespace UsaRugbyStatsTest\Competition\Service;

use Mockery;
use Zend\ServiceManager\ServiceManager;
use UsaRugbyStats\Competition\Service\UnionServiceFactory;

class UnionServiceFactoryTest extends \PHPUnit_Framework_TestCase
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

        $sl->setService('usarugbystats_competition_union_createform', $mockCreateForm);
        $sl->setService('usarugbystats_competition_union_updateform', $mockUpdateForm);

        $factory = new UnionServiceFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\UnionService', $obj);
        $this->assertSame($mockObjectManager, $obj->getUnionObjectManager());
        $this->assertSame($mockObjectRepository, $obj->getUnionRepository());
        $this->assertSame($mockCreateForm, $obj->getCreateForm());
        $this->assertSame($mockUpdateForm, $obj->getUpdateForm());
    }
}
