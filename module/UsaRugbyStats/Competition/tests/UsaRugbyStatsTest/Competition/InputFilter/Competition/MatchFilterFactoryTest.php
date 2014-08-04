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

        $mockTeamInputFilter = Mockery::mock('UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamFilter');
        $mockSignatureInputFilter = Mockery::mock('UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchSignatureFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_team_inputfilter', $mockTeamInputFilter);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_signature_inputfilter', $mockSignatureInputFilter);
    }

    public function testCreateService()
    {
        $factory = new MatchFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\Competition\MatchFilter', $object);
    }
}
