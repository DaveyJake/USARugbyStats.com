<?php
namespace UsaRugbyStatsTest\Competition\Form\Fieldset\Union;

use Mockery;
use UsaRugbyStats\Competition\Form\Fieldset\Union\TeamFieldset;

class TeamFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockTeamFieldset = Mockery::mock('Zend\Form\FieldsetInterface');

        $fieldset = new TeamFieldset($mockObjectManager, $mockRepository);

        $this->assertEquals('team', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('id'));
        $this->assertEquals('UsaRugbyStats\Competition\Entity\Team', $fieldset->get('id')->getOption('target_class'));
    }

    public function testGetTeamWillPullTeamIdFromFormFieldIfNotPassedViaArgument()
    {
        $entity = new \stdClass();

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getClassMetadata');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('find')->withArgs([42])->andReturn($entity);

        $fieldset = new TeamFieldset($mockObjectManager, $mockRepository);
        $fieldset->get('id')->setValue(42);

        $this->assertSame($entity, $fieldset->getTeam());
    }

    public function testGetTeamWillReturnNullIfNoValuePassedOrSelectedViaField()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');

        $fieldset = new TeamFieldset($mockObjectManager, $mockRepository);

        $this->assertNull($fieldset->getTeam());
    }

    public function testGetTeamWillReturnEntity()
    {
        $entity = new \stdClass();

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('find')->withArgs([42])->andReturn($entity);

        $fieldset = new TeamFieldset($mockObjectManager, $mockRepository);

        $this->assertSame($entity, $fieldset->getTeam(42));
    }
}
