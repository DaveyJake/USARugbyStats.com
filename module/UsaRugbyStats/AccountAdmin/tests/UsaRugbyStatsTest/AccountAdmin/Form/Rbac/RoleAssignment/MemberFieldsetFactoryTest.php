<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldsetFactory;

class MemberFieldsetFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = new \Zend\ServiceManager\ServiceManager();
        $this->serviceManager->setService('zfcuser_doctrine_em', Mockery::mock('Doctrine\Common\Persistence\ObjectManager'));
        $this->serviceManager->setService(
            'usarugbystats_competition_team_member_fieldset',
            Mockery::mock('UsaRugbyStats\Competition\Form\Fieldset\Team\MemberFieldset')
        );
    }

    public function testCreateService()
    {
        $factory = new MemberFieldsetFactory();
        $object = $factory->createService($this->serviceManager);

        $this->assertInstanceOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset', $object);
        $this->assertInstanceOf('Zend\Stdlib\Hydrator\HydratorInterface', $object->getHydrator());
        $this->assertInstanceOf('UsaRugbyStats\Account\Entity\Rbac\RoleAssignment\Member', $object->getObject());
    }
}
