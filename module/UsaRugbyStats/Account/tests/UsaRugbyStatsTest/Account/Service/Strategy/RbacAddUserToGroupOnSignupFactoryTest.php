<?php
namespace UsaRugbyStatsTest\Service\Strategy;

use Mockery;
use UsaRugbyStatsTest\Account\ServiceManagerTestCase;
use UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignupFactory;


class RbacAddUserToGroupOnSignupFactoryTest extends ServiceManagerTestCase
{
    public function testCreate()
    {
        $sm = $this->getServiceManager();
        $sm->setAllowOverride(true);

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $sm->setService('zfcuser_doctrine_em', $mockObjectManager);
        
        $factory = new RbacAddUserToGroupOnSignupFactory();
        $obj = $factory->createService($sm);
        
        $this->assertInstanceOf('UsaRugbyStats\Account\Service\Strategy\RbacAddUserToGroupOnSignup', $obj);
    }
}