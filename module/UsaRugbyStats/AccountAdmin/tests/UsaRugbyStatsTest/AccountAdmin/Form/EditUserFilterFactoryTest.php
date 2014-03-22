<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\EditUserFilterFactory;

class EditUserFilterFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    
    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_module_options', new \ZfcUser\Options\ModuleOptions());
        $this->serviceManager->setService('zfcuser_user_mapper', Mockery::mock('ZfcUser\Mapper\UserInterface'));
        $this->serviceManager->setService('zfcuseradmin_module_options', Mockery::mock('ZfcUserAdmin\Options\ModuleOptions'));
    }

    public function testFilterHasAllTheRequiredBitsInDefaultConfiguration()
    {
        $mock = $this->serviceManager->get('zfcuseradmin_module_options');
        $mock->shouldReceive('getAllowPasswordChange')->andReturn(true);
    
        $factory = new EditUserFilterFactory();
        $obj = $factory->createService($this->serviceManager);
    
        $this->assertInstanceOf('ZfcUser\Form\RegisterFilter', $obj);
        $this->assertInstanceOf('Zend\Validator\ValidatorInterface', $obj->getEmailValidator());
        $this->assertInstanceOf('Zend\Validator\ValidatorInterface', $obj->getUsernameValidator());
        
        $this->assertTrue($obj->has('password'));
        // Password verify is never required in Admin mode
        $this->assertFalse($obj->has('passwordVerify'));
        $this->assertTrue($obj->has('roleAssignments'));
    }
    
    public function testFilterDoesNotHavePasswordElementsWhenPasswordChangeIsDisabled()
    {
        $mock = $this->serviceManager->get('zfcuseradmin_module_options');
        $mock->shouldReceive('getAllowPasswordChange')->andReturn(false);
        
        $factory = new EditUserFilterFactory();
        $obj = $factory->createService($this->serviceManager);
        
        $this->assertFalse($obj->has('password'));
        $this->assertFalse($obj->has('passwordVerify'));
    }

    public function testFilterHasPasswordElementsWhenPasswordChangeIsEnabled()
    {
        $mock = $this->serviceManager->get('zfcuseradmin_module_options');
        $mock->shouldReceive('getAllowPasswordChange')->andReturn(true);
    
        $factory = new EditUserFilterFactory();
        $obj = $factory->createService($this->serviceManager);
    
        $this->assertTrue($obj->has('password'));
        $this->assertFalse($obj->get('password')->isRequired());
        $this->assertFalse($obj->has('passwordVerify'));
    }
}