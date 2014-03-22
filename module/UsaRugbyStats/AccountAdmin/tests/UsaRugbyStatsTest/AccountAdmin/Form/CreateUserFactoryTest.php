<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\CreateUserFactory;

class CreateUserFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    
    public function setUp()
    {
        $mockCollection = Mockery::mock('Zend\Form\Element\Collection');
        $mockCollection->shouldReceive('getName')->andReturn('roleAssignments');
        $mockCollection->shouldReceive('getObject')->andReturnNull();
        $mockCollection->shouldReceive('getElements')->andReturn(array());
        $mockCollection->shouldReceive('getFieldsets')->andReturn(array());
        $mockCollection->shouldReceive('getTargetElement')->andReturnNull();
        $mockCollection->shouldReceive('prepareFieldset')->andReturnNull();
        $mockCollection->shouldReceive('useAsBaseFieldset')->andReturnNull();
        
        $mockOptions = Mockery::mock('ZfcUserAdmin\Options\ModuleOptions[getCreateUserAutoPassword]');
        
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_module_options', new \ZfcUser\Options\ModuleOptions());
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService('zfcuser_user_mapper', Mockery::mock('ZfcUser\Mapper\UserInterface'));
        $this->serviceManager->setService('zfcuseradmin_module_options', $mockOptions);
        $this->serviceManager->setService('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElement', $mockCollection);
    }
    
    public function testCreateService()
    {
        $mock = $this->serviceManager->get('zfcuseradmin_module_options');
        $mock->shouldReceive('getCreateUserAutoPassword')->andReturn(false);
        
        $factory = new CreateUserFactory();
        $obj = $factory->createService($this->serviceManager);
        
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Entity\AccountHydrator', $obj->getHydrator());
        $this->assertInstanceOf('Zend\InputFilter\InputFilterInterface', $obj->getInputFilter());
    }

    public function testFormAndFilterHaveAllTheRequiredBitsInDefaultConfiguration()
    {
        $mock = $this->serviceManager->get('zfcuseradmin_module_options');
        $mock->shouldReceive('getCreateUserAutoPassword')->andReturn(true);
    
        $factory = new CreateUserFactory();
        $obj = $factory->createService($this->serviceManager);
        
        $this->assertFalse($obj->has('password'));
        $this->assertFalse($obj->has('passwordVerify'));
        $this->assertTrue($obj->has('roleAssignments'));
        
        $filter = $obj->getInputFilter();        
        $this->assertInstanceOf('ZfcUser\Form\RegisterFilter', $filter);
        $this->assertInstanceOf('Zend\Validator\ValidatorInterface', $filter->getEmailValidator());
        $this->assertInstanceOf('Zend\Validator\ValidatorInterface', $filter->getUsernameValidator());
    
        $this->assertFalse($filter->has('password'));
        $this->assertFalse($filter->has('passwordVerify'));
        $this->assertTrue($filter->has('roleAssignments'));
    }
    
    public function testFormAndFilterHavePasswordElementsWhenPasswordAutogenIsDisabled()
    {
        $mock = $this->serviceManager->get('zfcuseradmin_module_options');
        $mock->shouldReceive('getCreateUserAutoPassword')->andReturn(false);
    
        $factory = new CreateUserFactory();
        $obj = $factory->createService($this->serviceManager);

        $this->assertTrue($obj->has('password'));
        $this->assertTrue($obj->has('passwordVerify'));
        
        $filter = $obj->getInputFilter();        
        $this->assertInstanceOf('ZfcUser\Form\RegisterFilter', $filter);
        $this->assertInstanceOf('Zend\Validator\ValidatorInterface', $filter->getEmailValidator());
        $this->assertInstanceOf('Zend\Validator\ValidatorInterface', $filter->getUsernameValidator());
    
        $this->assertTrue($filter->has('password'));
        $this->assertTrue($filter->has('passwordVerify'));
        $this->assertTrue($filter->has('roleAssignments'));
    }

}