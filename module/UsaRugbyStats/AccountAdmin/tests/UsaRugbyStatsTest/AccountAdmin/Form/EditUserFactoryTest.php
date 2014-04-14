<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\EditUserFactory;

class EditUserFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockCollection = Mockery::mock('Zend\Form\Element\Collection');
        $mockCollection->shouldReceive('getName')->andReturn('roleAssignments');
        $mockCollection->shouldReceive('getObject')->andReturnNull();
        $mockCollection->shouldReceive('prepareFieldset')->andReturnNull();
        $mockCollection->shouldReceive('useAsBaseFieldset')->andReturnNull();

        $mockFilter = Mockery::mock('Zend\InputFilter\InputFilterInterface');
        $mockFilter->shouldReceive('has')->andReturn(false);
        $mockFilter->shouldReceive('add')->andReturnNull();
        $mockFilter->shouldReceive('get')->andReturnNull();
        $mockFilter->shouldReceive('getObject')->andReturnNull();

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_module_options', new \ZfcUser\Options\ModuleOptions());
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('zfcuseradmin_module_options', new \ZfcUserAdmin\Options\ModuleOptions);
        $this->serviceManager->setService('zfcuseradmin_edituser_filter', $mockFilter);
        $this->serviceManager->setService('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement', $mockCollection);
    }

    public function testCreateService()
    {
        $factory = new EditUserFactory();
        $obj = $factory->createService($this->serviceManager);

        $filter = $this->serviceManager->get('zfcuseradmin_edituser_filter');

        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Entity\AccountHydrator', $obj->getHydrator());
        $this->assertSame($filter, $obj->getInputFilter());
    }

}
