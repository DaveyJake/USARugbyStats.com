<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\ScoreEventFieldset;

class ScoreEventFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $fieldset = new ScoreEventFieldset($om);

        $this->assertEquals('match-team-event', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('minute'));
        $this->assertTrue($fieldset->has('event'));
        $this->assertTrue($fieldset->has('player'));
        $this->assertTrue($fieldset->has('type'));

        // @TODO test input filter
    }
}
