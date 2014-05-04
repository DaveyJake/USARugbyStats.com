<?php
namespace UsaRugbyStatsTest\Competition\Form;

use Mockery;
use UsaRugbyStats\Competition\Form\CompetitionCreateFormFactory;

class CompetitionCreateFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('setUseAsBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('useAsBaseFieldset')->andReturn(true);
        $mockFieldset->shouldReceive('isBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('getName')->andReturn('competition');
        $mockFieldset->shouldReceive('getElements')->andReturn(array());
        $mockFieldset->shouldReceive('getFieldsets')->andReturn(array());

        $mockInputFilter = Mockery::mock('Zend\InputFilter\InputFilter');

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('usarugbystats_competition_competition_fieldset', $mockFieldset);
        $this->serviceManager->setService('usarugbystats_competition_competition_inputfilter', $mockInputFilter);
    }

    public function testCreateService()
    {
        $factory = new CompetitionCreateFormFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Form\FormInterface', $obj);
        $this->assertEquals('create-competition', $obj->getName());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_competition_inputfilter'), $obj->getInputFilter()->get('competition'));
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_competition_fieldset'), $obj->getFieldsets()['competition']);
        $this->assertTrue($obj->has('submit'));
    }

}
