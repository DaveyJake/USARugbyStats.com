<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldsetFactory;

class SuperAdminFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    
    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
    }

    public function testCreateService()
    {
        $factory = new SuperAdminFieldsetFactory();
        $object = $factory->createService($this->serviceManager);
        
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset', $object);
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\SuperAdmin', $object->getObject());
    }
}