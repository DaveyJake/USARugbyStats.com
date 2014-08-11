<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\CompetitionFieldset;

class CompetitionFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockDivisionFieldset = Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Competition\DivisionFieldset');

        $fieldset = new CompetitionFieldset($mockObjectManager, $mockDivisionFieldset);

        $this->assertEquals('competition', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('name'));
        $this->assertTrue($fieldset->has('type'));
        $this->assertTrue($fieldset->has('variant'));
        $this->assertTrue($fieldset->has('divisions'));
        $this->assertInstanceOf('Zend\Form\Element\Collection', $fieldset->get('divisions'));
        $this->assertSame($mockDivisionFieldset, $fieldset->get('divisions')->getTargetElement());
    }
}
