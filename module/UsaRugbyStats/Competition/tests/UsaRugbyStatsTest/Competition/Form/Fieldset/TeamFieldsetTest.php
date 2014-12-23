<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\TeamFieldset;

class TeamFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockTeamFieldset = Mockery::mock('Zend\Form\FieldsetInterface');

        $fieldset = new TeamFieldset($mockObjectManager, $mockTeamFieldset);

        $this->assertEquals('team', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('name'));
        $this->assertTrue($fieldset->has('abbreviation'));
        $this->assertTrue($fieldset->has('union'));
        $this->assertTrue($fieldset->has('website'));
        $this->assertTrue($fieldset->has('email'));
        $this->assertTrue($fieldset->has('facebookHandle'));
        $this->assertTrue($fieldset->has('twitterHandle'));
        $this->assertTrue($fieldset->has('city'));
        $this->assertTrue($fieldset->has('state'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('union'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Union', $fieldset->get('union')->getOption('target_class'));
    }
}
