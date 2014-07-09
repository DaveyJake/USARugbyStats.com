<?php
namespace UsaRugbyStatsTest\CompetitionAdmin\Form\Fieldset\Union;

use Mockery;
use UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team\AdministratorFieldsetFactory;

class AdministratorFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
    }

    public function testCreateService()
    {
        $factory = new AdministratorFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team\AdministratorFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('stdClass', $object->getObject());
    }
}
