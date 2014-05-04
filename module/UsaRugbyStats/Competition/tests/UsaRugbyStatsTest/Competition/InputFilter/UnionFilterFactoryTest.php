<?php
namespace UsaRugbyStatsTest\Competition\InputFilter;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\UnionFilterFactory;

class UnionFilterFactoryTest extends \PHPUnit_Framework_TestCase
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
        $factory = new UnionFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\UnionFilter', $object);
    }
}
