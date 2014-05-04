<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamEvent\CardEventFieldset;

class CardEventFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');

        $fieldset = new CardEventFieldset($om);

        $this->assertEquals('card', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('minute'));
        $this->assertTrue($fieldset->has('event'));

        $this->assertTrue($fieldset->has('player'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('player'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer', $fieldset->get('player')->getOption('target_class'));

        $this->assertTrue($fieldset->has('type'));

        // @TODO test input filter
    }
}
