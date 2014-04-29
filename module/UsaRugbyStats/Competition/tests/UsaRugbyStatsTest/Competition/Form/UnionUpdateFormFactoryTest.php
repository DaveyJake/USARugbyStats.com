<?php
namespace UsaRugbyStatsTest\Competition\Form;

use Mockery;
use UsaRugbyStats\Competition\Form\UnionCreateFormFactory;
use UsaRugbyStats\Competition\Form\UnionUpdateFormFactory;

class UnionUpdateFormFactoryTest extends \PHPUnit_Framework_TestCase
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

        $createForm = (new UnionCreateFormFactory())->createService($this->serviceManager);
        $this->serviceManager->setService('usarugbystats_competition_union_createform', $createForm);
    }

    public function testCreateService()
    {
        $factory = new UnionUpdateFormFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Form\FormInterface', $obj);
        $this->assertEquals('update-union', $obj->getName());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_union_inputfilter'), $obj->getInputFilter()->get('union'));
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_union_fieldset'), $obj->getFieldsets()['union']);
        $this->assertTrue($obj->has('submit'));
    }

}
