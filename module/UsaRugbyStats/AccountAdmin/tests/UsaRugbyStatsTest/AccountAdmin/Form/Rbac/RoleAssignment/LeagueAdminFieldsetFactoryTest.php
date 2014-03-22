<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\LeagueAdminFieldsetFactory;

class LeagueAdminFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    
    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
    }

    public function testCreateService()
    {
        $factory = new LeagueAdminFieldsetFactory();
        $object = $factory->createService($this->serviceManager);
        
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\LeagueAdminFieldset', $object);
        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\LeagueAdmin', $object->getObject());
    }
}