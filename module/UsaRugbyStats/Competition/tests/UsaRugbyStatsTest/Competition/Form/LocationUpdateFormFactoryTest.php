<?php
namespace UsaRugbyStatsTest\Competition\Form;

use Mockery;
use UsaRugbyStats\Competition\Form\LocationCreateFormFactory;
use UsaRugbyStats\Competition\Form\LocationUpdateFormFactory;

class LocationUpdateFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('setUseAsBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('useAsBaseFieldset')->andReturn(true);
        $mockFieldset->shouldReceive('isBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('getName')->andReturn('location');
        $mockFieldset->shouldReceive('getElements')->andReturn(array());
        $mockFieldset->shouldReceive('getFieldsets')->andReturn(array());

        $mockInputFilter = Mockery::mock('Zend\InputFilter\InputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('usarugbystats_competition_location_fieldset', $mockFieldset);
        $this->serviceManager->setService('usarugbystats_competition_location_inputfilter', $mockInputFilter);

        $createForm = (new LocationCreateFormFactory())->createService($this->serviceManager);
        $this->serviceManager->setService('usarugbystats_competition_location_createform', $createForm);
    }

    public function testCreateService()
    {
        $factory = new LocationUpdateFormFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Form\FormInterface', $obj);
        $this->assertEquals('update-location', $obj->getName());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_location_inputfilter'), $obj->getInputFilter()->get('location'));
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_location_fieldset'), $obj->getFieldsets()['location']);
        $this->assertTrue($obj->has('submit'));
    }

}
