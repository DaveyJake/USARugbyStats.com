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
        $mockHomeTeam->shouldReceive('setName')->withArgs(['H']);
        $mockHomeTeam->shouldReceive('getName')->andReturn('H');
        $mockHomeTeam->shouldReceive('get->setValue')->withArgs(['H']);
        $mockAwayTeam = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldset');
        $mockAwayTeam->shouldReceive('setName')->withArgs(['A']);
        $mockAwayTeam->shouldReceive('getName')->andReturn('A');
        $mockAwayTeam->shouldReceive('get->setValue')->withArgs(['A']);
        $mockSignatures = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchSignatureFieldset');

        $fieldset = new MatchFieldset($mockObjectManager, $mockHomeTeam, $mockAwayTeam, $mockSignatures);

        $this->assertEquals('match', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('competition'));
        $this->assertTrue($fieldset->has('description'));
        $this->assertTrue($fieldset->has('date'));
        $this->assertTrue($fieldset->has('status'));

        $this->assertTrue($fieldset->has('teams'));
        $this->assertTrue($fieldset->get('teams')->has('H'));
        $this->assertSame($mockHomeTeam, $fieldset->get('teams')->get('H'));
        $this->assertTrue($fieldset->get('teams')->has('A'));
        $this->assertSame($mockAwayTeam, $fieldset->get('teams')->get('A'));

        $this->assertInstanceOf('Zend\Form\Element\Collection', $fieldset->get('signatures'));
        $this->assertSame($mockSignatures, $fieldset->get('signatures')->getTargetElement());
    }
}
