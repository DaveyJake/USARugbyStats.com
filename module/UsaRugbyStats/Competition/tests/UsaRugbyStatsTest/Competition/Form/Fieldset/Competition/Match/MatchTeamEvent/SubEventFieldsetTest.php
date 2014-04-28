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
        $this->assertTrue($fieldset->has('playerOff'));
        $this->assertTrue($fieldset->has('type'));

        // @TODO test input filter
    }
}
