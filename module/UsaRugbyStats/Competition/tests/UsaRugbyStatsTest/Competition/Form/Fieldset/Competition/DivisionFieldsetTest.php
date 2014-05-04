<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Competition;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldset;

class DivisionFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockTeamFieldset = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\TeamMembershipFieldset');

        $fieldset = new DivisionFieldset($mockObjectManager, $mockTeamFieldset);

        $this->assertEquals('division', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('name'));
        $this->assertTrue($fieldset->has('teamMemberships'));
        $this->assertInstanceOf('Zend\Form\Element\Collection', $fieldset->get('teamMemberships'));
        $this->assertSame($mockTeamFieldset, $fieldset->get('teamMemberships')->getTargetElement());
    }
}
