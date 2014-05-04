<?php
namespace UsaRugbyStatsTest\Competition\InputFilter\Competition;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\Competition\DivisionFilterFactory;

class DivisionFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    protected $mockInputFilter;

    public function setUp()
    {
        $this->mockInputFilter = Mockery::mock('Zend\InputFilter\InputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('usarugbystats_competition_competition_teammembership_inputfilter', $this->mockInputFilter);
    }

    public function testCreateService()
    {
        $factory = new DivisionFilterFactory();
        $object = $factory->createService($this->serviceManager);
        $this->assertInstanceOf('UsaRugbyStats\Competition\InputFilter\Competition\DivisionFilter', $object);
        $this->assertInstanceOf('Zend\InputFilter\CollectionInputFilter', $object->get('teamMemberships'));
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $object->get('teamMemberships')->getInputFilter());
        $this->assertSame($this->mockInputFilter, $object->get('teamMemberships')->getInputFilter());
    }
}
