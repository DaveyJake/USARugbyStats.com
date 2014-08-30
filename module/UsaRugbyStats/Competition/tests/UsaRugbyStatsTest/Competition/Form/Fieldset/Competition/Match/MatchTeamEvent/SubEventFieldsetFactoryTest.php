<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\SubEventFieldsetFactory;

class SubEventFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager->shouldReceive('getRepository', $mockRepository);
        
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
    }

    public function testCreateService()
    {
        $factory = new SubEventFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\SubEventFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent', $object->getObject());
    }
}
