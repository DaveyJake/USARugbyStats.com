<?php
namespace UsaRugbyStats\Competition\Form\Fieldset\Competition\Match;

use Mockery;

class MatchTeamEventFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $fieldset = new LocalMatchTeamEventFieldset('local', $om);
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('minute'));
        $this->assertTrue($fieldset->has('event'));

        //@TODO test input filter
    }

}

class LocalMatchTeamEventFieldset extends MatchTeamEventFieldset {}
