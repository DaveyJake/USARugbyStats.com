<?php
namespace UsaRugbyStatsTest\Competition\InputFilter\Competition\Match;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamPlayerFilterFactory;

class MatchTeamPlayerFilterFactoryTest extends \PHPUnit_Framework_TestCase
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
        $factory = new MatchTeamPlayerFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamPlayerFilter', $object);
    }
}
