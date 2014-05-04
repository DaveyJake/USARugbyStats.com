<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\TeamFieldsetFactory;

class TeamFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
    }

    public function testCreateService()
    {
        $factory = new TeamFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\TeamFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Team', $object->getObject());
    }
}
