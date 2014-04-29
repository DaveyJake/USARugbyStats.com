<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldsetFactory;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;

class MatchTeamFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockCardFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockCardFieldset->shouldReceive('getObject')->andReturn(new CardEvent());
        $mockSubFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockSubFieldset->shouldReceive('getObject')->andReturn(new SubEvent());
        $mockScoreFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockScoreFieldset->shouldReceive('getObject')->andReturn(new ScoreEvent());

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_teamplayer_fieldset', $mockFieldset);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_teamevent_cardfieldset', $mockCardFieldset);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_teamevent_subfieldset', $mockSubFieldset);
        $this->serviceManager->setService('usarugbystats_competition_competition_match_teamevent_scorefieldset', $mockScoreFieldset);
    }

    public function testCreateService()
    {
        $factory = new MatchTeamFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam', $object->getObject());

        $this->assertInstanceOf('Zend\Form\Element\Collection', $object->get('players'));
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollection', $object->get('events'));
    }
}
