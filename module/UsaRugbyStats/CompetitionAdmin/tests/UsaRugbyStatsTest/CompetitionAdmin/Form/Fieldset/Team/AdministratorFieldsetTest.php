<?php
namespace UsaRugbyStatsTest\CompetitionAdmin\Form\Fieldset\Team;

use Mockery;
use UsaRugbyStats\CompetitionAdmin\Form\Fieldset\Team\AdministratorFieldset;

class AdministratorFieldsetTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function testCreate()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockAdministratorFieldset = Mockery::mock('Zend\Form\FieldsetInterface');

        $fieldset = new AdministratorFieldset($mockObjectManager, $mockRepository);

        $this->assertEquals('team-administrator', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('account'));
        $this->assertInstanceOf('DoctrineModule\Form\Element\ObjectSelect', $fieldset->get('account'));
        $this->assertEquals('UsaRugbyStats\Account\Entity\Account', $fieldset->get('account')->getOption('target_class'));
    }

    public function testGetAccountWillPullAccountIdFromFormFieldIfNotPassedViaArgument()
    {
        $entity = new \stdClass();

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockObjectManager->shouldReceive('getClassMetadata');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('find')->withArgs([42])->andReturn($entity);

        $fieldset = new AdministratorFieldset($mockObjectManager, $mockRepository);
        $fieldset->get('account')->setValue(42);

        $this->assertSame($entity, $fieldset->getAccount());
    }

    public function testGetTeamWillReturnNullIfNoValuePassedOrSelectedViaField()
    {
        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');

        $fieldset = new AdministratorFieldset($mockObjectManager, $mockRepository);

        $this->assertNull($fieldset->getAccount());
    }

    public function testGetTeamWillReturnEntity()
    {
        $entity = new \stdClass();

        $mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $mockRepository->shouldReceive('find')->withArgs([42])->andReturn($entity);

        $fieldset = new AdministratorFieldset($mockObjectManager, $mockRepository);

        $this->assertSame($entity, $fieldset->getAccount(42));
    }
}
