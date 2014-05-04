<?php
namespace UsaRugbyStatsTest\Competition\Form;

use Mockery;
use UsaRugbyStats\Competition\Form\UnionCreateFormFactory;

class UnionCreateFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('setUseAsBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('useAsBaseFieldset')->andReturn(true);
        $mockFieldset->shouldReceive('isBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('getName')->andReturn('union');
        $mockFieldset->shouldReceive('getElements')->andReturn(array());
        $mockFieldset->shouldReceive('getFieldsets')->andReturn(array());

        $mockInputFilter = Mockery::mock('Zend\InputFilter\InputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('usarugbystats_competition_union_fieldset', $mockFieldset);
        $this->serviceManager->setService('usarugbystats_competition_union_inputfilter', $mockInputFilter);
    }

    public function testCreateService()
    {
        $factory = new UnionCreateFormFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Form\FormInterface', $obj);
        $this->assertEquals('create-union', $obj->getName());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_union_inputfilter'), $obj->getInputFilter()->get('union'));
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_union_fieldset'), $obj->getFieldsets()['union']);
        $this->assertTrue($obj->has('submit'));
    }

}
