<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldsetFactory;

class UnionAdminFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    
    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
    }

    public function testCreateService()
    {
        $factory = new UnionAdminFieldsetFactory();
        $object = $factory->createService($this->serviceManager);
        
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset', $object);
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin', $object->getObject());
    }
}