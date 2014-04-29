<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamFieldset;

class MatchTeamFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('getName')->andReturn('player');
        $mockCollection = Mockery::mock('Zend\Form\Element\Collection');
        $mockCollection->shouldReceive('getName')->andReturn('events');

        $fieldset = new MatchTeamFieldset($mockObjectManager, $mockFieldset, $mockCollection);

        $this->assertEquals('match-team', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));

        $this->assertTrue($fieldset->has('team'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('team'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Team', $fieldset->get('team')->getOption('target_class'));

        $this->assertTrue($fieldset->has('players'));
        $this->assertInstanceOf('Zend\Form\Element\Collection', $fieldset->get('players'));
        $this->assertSame($mockFieldset, $fieldset->get('players')->getTargetElement());

        $this->assertSame($mockCollection, $fieldset->get('events'));
    }
}
