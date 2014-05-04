<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Union;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\TeamMembershipFieldset;

class TeamMembershipFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $fieldset = new TeamMembershipFieldset($mockObjectManager);

        $this->assertEquals('team-membership', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('team'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('team'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Team', $fieldset->get('team')->getOption('target_class'));
    }

    public function testGetTeamWillPullTeamIdFromFormFieldIfNotPassedViaArgument()
    {
        $entity = new \stdClass();

        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('find')->withArgs([42])->andReturn($entity);

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);
        $mockObjectManager->shouldReceive('getClassMetadata');

        $fieldset = new TeamMembershipFieldset($mockObjectManager);
        $fieldset->get('team')->setValue(42);

        $this->assertSame($entity, $fieldset->getTeam());
    }

    public function testGetTeamWillReturnNullIfNoValuePassedOrSelectedViaField()
    {
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $fieldset = new TeamMembershipFieldset($mockObjectManager);

        $this->assertNull($fieldset->getTeam());
    }

    public function testGetTeamWillReturnEntity()
    {
        $entity = new \stdClass();

        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('find')->withArgs([42])->andReturn($entity);
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getRepository')->andReturn($mockRepository);

        $fieldset = new TeamMembershipFieldset($mockObjectManager);

        $this->assertSame($entity, $fieldset->getTeam(42));
    }
}
