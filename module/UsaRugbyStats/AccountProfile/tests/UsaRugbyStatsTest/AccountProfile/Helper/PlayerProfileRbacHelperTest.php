<?php
namespace UsaRugbyStatsTest\AccountProfile\Helper;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use UsaRugbyStats\AccountProfile\Helper\PlayerProfileRbacHelper;
use LdcUserProfile\Form\PrototypeForm;

class PlayerProfileRbacHelperTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->form = new PrototypeForm();
        
        $fsOne = new Fieldset('one');
        $fsOne->add(array(
            'type' => 'hidden',
            'name' => 'a'
        ));
        $fsOne->add(array(
            'type' => 'text',
            'name' => 'b'
        ));
        $this->form->add($fsOne);
        
        $fsTwo = new Fieldset('two');
        $fsTwo->add(array(
            'type' => 'hidden',
            'name' => 'z'
        ));
        $fsTwo->add(array(
            'type' => 'text',
            'name' => 'x'
        ));
        $this->form->add($fsTwo);
        
        $this->authService = \Mockery::mock('ZfcRbac\Service\AuthorizationService');
        
        $this->helper = new PlayerProfileRbacHelper($this->authService);
        
        $this->player = \Mockery::mock('UsaRugbyStats\Account\Entity\Rbac\AccountRbacInterface');
    }
    
    public function testEmptyRbacResultsInEmptyValidationGroup()
    {
        $this->player->shouldReceive('getId')->andReturn(9999);
        
        $this->authService->shouldReceive('isGranted')->andReturn(false);
        $this->assertEquals(['one' => [], 'two' => []], $this->helper->processForm($this->form, $this->player));
    }

    public function testSuccessfulRbacGrantAddsFieldToValidationGroup()
    {
        $this->player->shouldReceive('getId')->andReturn(9999);
        
        $this->authService->shouldReceive('isGranted')->andReturnUsing(function($permission, $context) {
            return ( $permission == 'account.profile.one.b' );
        });
        
        $this->assertEquals(['one' => ['b'], 'two' => []], $this->helper->processForm($this->form, $this->player));
    }
    
    public function testRejectsBadPlayerObject()
    {
        $this->player->shouldReceive('getId')->andReturn(9999);
        
        $this->setExpectedException('PHPUnit_Framework_Error');
        $this->helper->processForm($this->form, new \stdClass());
    }
    
    public function testRejectsEmptyPlayerObject()
    {
        $this->setExpectedException('RuntimeException');
        $this->player->shouldReceive('getId')->andReturn(null);
        $this->helper->processForm($this->form, $this->player);
    }
}