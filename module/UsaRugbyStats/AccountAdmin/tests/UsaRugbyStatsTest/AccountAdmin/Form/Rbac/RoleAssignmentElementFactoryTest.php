<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentElementFactory;

class RoleAssignmentElementFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $mockFieldset = Mockery::mock('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset');
        $mockFieldset->shouldReceive('getObject')->andReturn(new \stdClass());

        $sl = Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $sl->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\MemberFieldset'])->andReturn($mockFieldset);
        $sl->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\TeamAdminFieldset'])->andReturn($mockFieldset);
        $sl->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset'])->andReturn($mockFieldset);
        $sl->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\UnionAdminFieldset'])->andReturn($mockFieldset);
        $sl->shouldReceive('get')->withArgs(['UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\SuperAdminFieldset'])->andReturn($mockFieldset);

        $factory = new RoleAssignmentElementFactory();
        $obj = $factory->createService($sl);

        $this->assertInstanceOf('LdcZendFormCTI\Form\Element\NonuniformCollection', $obj);
        $this->assertContainsOnlyInstancesOf('UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignmentFieldset', $obj->getTargetElement());
    }

}
