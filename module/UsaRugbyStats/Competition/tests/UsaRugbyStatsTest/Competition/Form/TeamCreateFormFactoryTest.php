<?php
namespace UsaRugbyStatsTest\Competition\Form;

use Mockery;
use UsaRugbyStats\Competition\Form\TeamCreateFormFactory;

class TeamCreateFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockFieldset->shouldReceive('setUseAsBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('useAsBaseFieldset')->andReturn(true);
        $mockFieldset->shouldReceive('isBaseFieldset')->andReturnSelf();
        $mockFieldset->shouldReceive('getName')->andReturn('team');
        $mockFieldset->shouldReceive('getElements')->andReturn(array());
        $mockFieldset->shouldReceive('getFieldsets')->andReturn(array());

        $mockInputFilter = Mockery::mock('Zend\InputFilter\InputFilter');

        $mockAdministratorFieldset = Mockery::mock('Zend\Form\FieldsetInterface');
        $mockAdministratorFieldset->shouldReceive('getElements')->andReturn(array());

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('usarugbystats_competition_team_fieldset', $mockFieldset);
        $this->serviceManager->setService('usarugbystats_competition_team_administrator_fieldset', $mockAdministratorFieldset);
        $this->serviceManager->setService('usarugbystats_competition_team_inputfilter', $mockInputFilter);
    }

    public function testCreateService()
    {
        $factory = new TeamCreateFormFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Form\FormInterface', $obj);
        $this->assertEquals('create-team', $obj->getName());
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_team_inputfilter'), $obj->getInputFilter()->get('team'));
        $this->assertSame($this->serviceManager->get('usarugbystats_competition_team_fieldset'), $obj->getFieldsets()['team']);
        $this->assertTrue($obj->has('submit'));
    }

}
