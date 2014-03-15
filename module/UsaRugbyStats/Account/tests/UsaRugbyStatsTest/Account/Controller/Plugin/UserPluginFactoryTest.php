<?php
namespace UsaRugbyStatsTest\Account\Controller\Plugin;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;

use UsaRugbyStats\Account\Controller\Plugin\UserPluginFactory;
use Zend\Mvc\Controller\PluginManager;
use ZfcUser\Authentication\Adapter\AdapterChain;


class UserPluginFactoryTest extends ServiceManagerTestCase
{
    public function testCreate()
    {
        $authServiceMock = Mockery::mock('Zend\Authentication\AuthenticationService');
        $authAdapterMock = Mockery::mock('ZfcUser\Authentication\Adapter\AdapterChain');
        
        $mockParentPlugin = Mockery::mock('ZfcUser\Controller\Plugin\ZfcUserAuthentication');
        $mockParentPlugin->shouldReceive('getAuthService')->once()->andReturn($authServiceMock);
        $mockParentPlugin->shouldReceive('getAuthAdapter')->once()->andReturn($authAdapterMock);
        
        $sm = $this->getServiceManager();
        $sm->setAllowOverride(true);
        $sm->setService('Config', ['zfcuser' => ['user_entity_class' => 'stdClass']]);
        
        $cpManager = new PluginManager();
        $cpManager->setServiceLocator($sm);
        $cpManager->setService('zfcUserAuthentication', $mockParentPlugin);
                
        $factory = new UserPluginFactory();
        $obj = $factory->createService($cpManager);
        
        $this->assertInstanceOf('UsaRugbyStats\Account\Controller\Plugin\UserPlugin', $obj);
        $this->assertInstanceOf('ZfcUser\Controller\Plugin\ZfcUserAuthentication', $obj);
        $this->assertEquals('stdClass', $obj->getEntityClass());
    }
}