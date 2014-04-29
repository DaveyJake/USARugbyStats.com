<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\MatchFieldsetFactory;

class MatchFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));

        $mockMatchTeamFieldset = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldset');
        $mockMatchTeamFieldset->shouldReceive('setName')->andReturnSelf();
        $mockMatchTeamFieldset->shouldReceive('getName')->andReturn('homeTeam');
        $mockMatchTeamFieldset->shouldReceive('get->setValue')->andReturnSelf();
        $this->serviceManager->setService(
            'usarugbystats_competition_competition_match_team_fieldset',
            $mockMatchTeamFieldset
        );

        $this->serviceManager->setService(
            'usarugbystats_competition_competition_match_signature_fieldset',
            Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchSignatureFieldset')
        );

        $this->serviceManager->setService(
            'usarugbystats_competition_competition_match_event_fieldset',
            Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEventFieldset')
        );
    }

    public function testCreateService()
    {
        $factory = new MatchFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\Competition\MatchFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Match', $object->getObject());
    }
}
