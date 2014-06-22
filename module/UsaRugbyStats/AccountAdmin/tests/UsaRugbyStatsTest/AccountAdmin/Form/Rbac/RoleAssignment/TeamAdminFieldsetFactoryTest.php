<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldsetFactory;

class TeamAdminFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
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
        $factory = new TeamAdminFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\TeamAdmin', $object->getObject());
    }
}
