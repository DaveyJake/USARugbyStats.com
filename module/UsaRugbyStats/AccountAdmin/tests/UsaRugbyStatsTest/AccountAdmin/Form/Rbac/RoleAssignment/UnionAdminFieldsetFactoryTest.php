<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldsetFactory;

class UnionAdminFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $mockMapper = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockMapper);

        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', $mockObjectManager);
    }

    public function testCreateService()
    {
        $factory = new UnionAdminFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\UnionAdmin', $object->getObject());
    }
}
