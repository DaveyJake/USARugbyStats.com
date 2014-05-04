<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\MatchFieldset;

class MatchFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockHomeTeam = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldset');
        $mockHomeTeam->shouldReceive('setName')->withArgs(['homeTeam']);
        $mockHomeTeam->shouldReceive('getName')->andReturn('homeTeam');
        $mockHomeTeam->shouldReceive('get->setValue')->withArgs(['H']);
        $mockAwayTeam = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldset');
        $mockAwayTeam->shouldReceive('setName')->withArgs(['awayTeam']);
        $mockAwayTeam->shouldReceive('getName')->andReturn('awayTeam');
        $mockAwayTeam->shouldReceive('get->setValue')->withArgs(['A']);
        $mockSignatures = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchSignatureFieldset');

        $fieldset = new MatchFieldset($mockObjectManager, $mockHomeTeam, $mockAwayTeam, $mockSignatures);

        $this->assertEquals('match', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('competition'));
        $this->assertTrue($fieldset->has('description'));
        $this->assertTrue($fieldset->has('date'));
        $this->assertTrue($fieldset->has('status'));

        $this->assertTrue($fieldset->has('homeTeam'));
        $this->assertSame($mockHomeTeam, $fieldset->get('homeTeam'));

        $this->assertTrue($fieldset->has('awayTeam'));
        $this->assertSame($mockAwayTeam, $fieldset->get('awayTeam'));

        $this->assertInstanceOf('Zend\Form\Element\Collection', $fieldset->get('signatures'));
        $this->assertSame($mockSignatures, $fieldset->get('signatures')->getTargetElement());
    }
}
