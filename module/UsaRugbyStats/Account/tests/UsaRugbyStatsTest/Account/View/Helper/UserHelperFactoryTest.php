<?php
namespace UsaRugbyStatsTest\Account\View\Helper;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;

use Zend\View\HelperPluginManager;
use UsaRugbyStats\Account\View\Helper\UserHelperFactory;


class UserHelperFactoryTest extends ServiceManagerTestCase
{
    public function testCreate()
    {
        $authServiceMock = Mockery::mock('Zend\Authentication\AuthenticationService');
        
        $mockParentPlugin = Mockery::mock('ZfcUser\View\Helper\ZfcUserIdentity');
        $mockParentPlugin->shouldReceive('getAuthService')->once()->andReturn($authServiceMock);
        
        $sm = $this->getServiceManager();
        $sm->setAllowOverride(true);
        $sm->setService('Config', ['zfcuser' => ['user_entity_class' => 'stdClass']]);
        
        $vhManager = new HelperPluginManager();
        $vhManager->setServiceLocator($sm);
        $vhManager->setService('zfcUserIdentity', $mockParentPlugin);
                
        $factory = new UserHelperFactory();
        $obj = $factory->createService($vhManager);
        
        $this->assertInstanceOf('UsaRugbyStats\Account\View\Helper\UserHelper', $obj);
        $this->assertInstanceOf('ZfcUser\View\Helper\ZfcUserIdentity', $obj);
        $this->assertEquals('stdClass', $obj->getEntityClass());
    }
}