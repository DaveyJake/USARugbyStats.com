<?php
namespace UsaRugbyStatsTest\Competition\InputFilter\Competition;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\Competition\MatchFilterFactory;

class MatchFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $mockInputFilter = Mockery::mock('UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_team_inputfilter', $mockInputFilter);
    }

    public function testCreateService()
    {
        $factory = new MatchFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\Competition\MatchFilter', $object);
    }
}
