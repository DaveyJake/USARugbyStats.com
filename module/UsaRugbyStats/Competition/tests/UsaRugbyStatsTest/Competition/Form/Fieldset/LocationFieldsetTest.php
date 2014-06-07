<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\LocationFieldset;

class LocationFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockLocationFieldset = Mockery::mock('Zend\Form\FieldsetInterface');

        $fieldset = new LocationFieldset($mockObjectManager, $mockLocationFieldset);

        $this->assertEquals('location', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('name'));
        $this->assertTrue($fieldset->has('address'));
        $this->assertTrue($fieldset->has('coordinates'));
    }
}
