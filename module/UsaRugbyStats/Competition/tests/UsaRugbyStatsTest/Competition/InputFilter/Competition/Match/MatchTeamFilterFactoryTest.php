<?php
namespace UsaRugbyStatsTest\Competition\InputFilter\Competition\Match;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamFilterFactory;

class MatchTeamFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $mockPlayerCollection = Mockery::mock('Zend\InputFilter\CollectionInputFilter');
        $mockEventsCollection = Mockery::mock('Zend\InputFilter\CollectionInputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_teamplayer_collectionfilter', $mockPlayerCollection);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_teamevent_collectionfilter', $mockEventsCollection);
    }

    public function testCreateService()
    {
        $factory = new MatchTeamFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamFilter', $object);
    }
}
