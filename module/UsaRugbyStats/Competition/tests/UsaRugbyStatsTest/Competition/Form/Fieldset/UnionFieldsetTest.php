<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\UnionFieldset;

class UnionFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockTeamFieldset = Mockery::mock('Zend\Form\FieldsetInterface');

        $fieldset = new UnionFieldset($mockObjectManager, $mockTeamFieldset);

        $this->assertEquals('union', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('name'));
        $this->assertTrue($fieldset->has('teams'));
        $this->assertInstanceOf('Zend\Form\Element\Collection', $fieldset->get('teams'));
        $this->assertSame($mockTeamFieldset, $fieldset->get('teams')->getTargetElement());
    }
}
