<?php
namespace UsaRugbyStatsTest\Account\View\Helper;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;

use UsaRugbyStats\Account\View\Helper\UserHelper;
use UsaRugbyStats\Account\Entity\Account;

class UserHelperTest extends ServiceManagerTestCase
{
    public function testReturnsNullObjectWhenUserIsNotAuthenticated()
    {
        $mockAuthService = Mockery::mock('Zend\Authentication\AuthenticationService');
        $mockAuthService->shouldReceive('hasIdentity')->andReturn(false);

        $obj = new UserHelper();
        $obj->setAuthService($mockAuthService);
        $obj->setEntityClass('UsaRugbyStats\Account\Entity\Account');
        
        $this->assertFalse($obj->isAuthenticated());
        $this->assertInstanceOf('ProxyManager\Proxy\NullObjectInterface', $obj());
        $this->assertNotInstanceOf($obj->getEntityClass(), $obj());
        $this->assertNull($obj()->getId());
    }
    
    public function testReturnsUserObjectWhenUserIsAuthenticated()
    {
        $user = new Account();
        
        $mockAuthService = Mockery::mock('Zend\Authentication\AuthenticationService');
        $mockAuthService->shouldReceive('hasIdentity')->andReturn(true);
        $mockAuthService->shouldReceive('getIdentity')->andReturn($user);
    
        $obj = new UserHelper();
        $obj->setAuthService($mockAuthService);
        $obj->setEntityClass('ZfcUser\Entity\UserInterface');
    
        $this->assertTrue($obj->isAuthenticated());
        $this->assertInstanceOf('UsaRugbyStats\Application\Entity\AccountInterface', $obj());
        $this->assertInstanceOf($obj->getEntityClass(), $obj());
        $this->assertNull($obj()->getId());
    }
}