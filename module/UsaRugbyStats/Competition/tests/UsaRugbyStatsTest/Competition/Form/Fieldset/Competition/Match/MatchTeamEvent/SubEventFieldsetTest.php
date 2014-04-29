<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\SubEventFieldset;

class SubEventFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $fieldset = new SubEventFieldset($om);

        $this->assertEquals('sub', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('minute'));
        $this->assertTrue($fieldset->has('event'));

        $this->assertTrue($fieldset->has('playerOn'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('playerOn'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer', $fieldset->get('playerOn')->getOption('target_class'));

        $this->assertTrue($fieldset->has('playerOff'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('playerOff'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer', $fieldset->get('playerOff')->getOption('target_class'));

        $this->assertTrue($fieldset->has('type'));

        // @TODO test input filter
    }
}
