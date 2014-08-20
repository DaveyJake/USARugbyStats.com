<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition\Match;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamPlayerFieldset;
use UsaRugbyStats\Competition\Entity\Competition;

class MatchTeamPlayerFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldIgnoreMissing();

        $fieldset = new MatchTeamPlayerFieldset($mockObjectManager);

        $this->assertEquals('team-player', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('number'));
        $this->assertTrue($fieldset->has('position'));
        $this->assertTrue($fieldset->has('isFrontRow'));
        $this->assertTrue($fieldset->has('player'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('player'));
        $this->assertEquals('UsaRugbyStats\Account\Entity\Account', $fieldset->get('player')->getOption('target_class'));
    }

    public function testSetVariantTo15sChangesPositionSelect()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldIgnoreMissing();

        $fieldset = new MatchTeamPlayerFieldset($mockObjectManager);
        $fieldset->setVariant(Competition::VARIANT_FIFTEENS);

        $this->assertTrue($fieldset->has('position'));
        $this->assertEquals(MatchTeamPlayerFieldset::$positions[Competition::VARIANT_FIFTEENS], $fieldset->get('position')->getValueOptions());
    }

    public function testSetVariantTo7sChangesPositionSelect()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldIgnoreMissing();

        $fieldset = new MatchTeamPlayerFieldset($mockObjectManager);
        $fieldset->setVariant(Competition::VARIANT_SEVENS);

        $this->assertTrue($fieldset->has('position'));
        $this->assertEquals(MatchTeamPlayerFieldset::$positions[Competition::VARIANT_SEVENS], $fieldset->get('position')->getValueOptions());
    }
}
