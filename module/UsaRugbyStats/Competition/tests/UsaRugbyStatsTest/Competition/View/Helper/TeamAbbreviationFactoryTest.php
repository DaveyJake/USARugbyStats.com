<?php
namespace UsaRugbyStatsTest\Competition\InputFilter;

use Mockery;
use UsaRugbyStats\Competition\View\Helper\TeamAbbreviationFactory;

class TeamAbbreviationFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockService = Mockery::mock('UsaRugbyStats\Competition\Service\TeamService');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('usarugbystats_competition_team_service', $mockService);
    }

    public function testCreateService()
    {
        $factory = new TeamAbbreviationFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\View\Helper\TeamAbbreviation', $object);
        $this->assertInstanceOf('UsaRugbyStats\Competition\Service\TeamService', $object->getTeamService());
    }
}
