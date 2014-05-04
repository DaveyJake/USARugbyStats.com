<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchSignatureFieldsetFactory;

class MatchSignatureFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
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
        $factory = new MatchSignatureFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchSignatureFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature', $object->getObject());
    }
}
