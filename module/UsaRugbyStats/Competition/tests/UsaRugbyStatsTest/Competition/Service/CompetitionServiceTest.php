<?php
namespace UsaRugbyStatsTest\Competition\Service;

use Mockery;
use UsaRugbyStats\Competition\Service\CompetitionService;
use UsaRugbyStats\Competition\Entity\Competition;

class CompetitionServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $service;
    protected $mockRepository;
    protected $mockObjectManager;
    protected $mockCreateForm;
    protected $mockUpdateForm;
    protected $mockEventManager;

    public function setUp()
    {
        $this->mockRepository = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository,Doctrine\Common\Collections\Selectable');
        $this->mockObjectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $this->mockCreateForm = Mockery::mock('Zend\Form\FormInterface');
        $this->mockUpdateForm = Mockery::mock('Zend\Form\FormInterface');
        $this->mockEventManager = Mockery::mock('Zend\EventManager\EventManagerInterface');
        $this->mockEventManager->shouldReceive('setIdentifiers')->zeroOrMoreTimes();

        $this->service = new CompetitionService();
        $this->service->setCompetitionRepository($this->mockRepository);
        $this->service->setCompetitionObjectManager($this->mockObjectManager);
        $this->service->setCreateForm($this->mockCreateForm);
        $this->service->setUpdateForm($this->mockUpdateForm);
        $this->service->setEventManager($this->mockEventManager);
    }

    public function testFindByID()
    {
        $entity = new \stdClass();

        $this->mockRepository->shouldReceive('find')->withArgs([123])->once()->andReturn($entity);

        $this->assertSame($entity, $this->service->findByID(123));
    }

    public function testFindByIDRejectsInvalidIdentifiers()
    {
        $this->setExpectedException('InvalidArgumentException');
        $this->service->findByID('abc');
        $this->fail('Invalid identifier did not trigger exception');
    }

    public function testFetchAllReturnsAPaginatorOfResults()
    {
        $this->assertInstanceOf('Zend\Paginator\Paginator', $this->service->fetchAll());
    }

    public function testCreateMethod()
    {
        // Partial-mock the service class to isolate create() from save()
        $service = Mockery::mock('UsaRugbyStats\Competition\Service\CompetitionService[save]');
        $service->shouldReceive('save')->once()->andReturnNull();

        // Inject the rest of the dependencies
        $service->setCompetitionRepository($this->mockRepository);
        $service->setCompetitionObjectManager($this->mockObjectManager);
        $service->setCreateForm($this->mockCreateForm);
        $service->setUpdateForm($this->mockUpdateForm);
        $service->setEventManager($this->mockEventManager);

        // Build the test data
        $data = ['id' => 42, 'name' => 'Test Competition'];
        $entity = new Competition();
        $entity->setId($data['id'])->setName($data['name']);

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->once()->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->withArgs([['competition' => $data]]);
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(true);

        // Ensure the event manager is triggered
        $this->mockEventManager->shouldReceive('trigger')->twice();

        $result = $service->create($this->mockCreateForm, ['competition' => $data]);
        $this->assertInstanceOf(get_class($entity), $result);
    }

    public function testCreateMethodShortCircuitsOnFailedValidation()
    {
        // Build the test data
        $data = ['id' => 42, 'name' => 'Test Competition'];
        $entity = new Competition();
        $entity->setId($data['id'])->setName($data['name']);

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->once()->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->withArgs([['competition' => $data]]);
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->assertFalse($this->service->create($this->mockCreateForm, ['competition' => $data]));
    }

    public function testUpdateMethod()
    {
        // Partial-mock the service class to isolate create() from save()
        $service = Mockery::mock('UsaRugbyStats\Competition\Service\CompetitionService[save]');
        $service->shouldReceive('save')->once()->andReturnNull();

        // Inject the rest of the dependencies
        $service->setCompetitionRepository($this->mockRepository);
        $service->setCompetitionObjectManager($this->mockObjectManager);
        $service->setCreateForm($this->mockCreateForm);
        $service->setUpdateForm($this->mockUpdateForm);
        $service->setEventManager($this->mockEventManager);

        // Build the test data
        $data = ['id' => 42, 'name' => 'Test Competition', 'divisions' => []];
        $entity = new Competition();
        $entity->setId($data['id'])->setName($data['name']);

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->withArgs([['competition' => $data]]);
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(true);
        $this->mockCreateForm->shouldReceive('getData')->once()->andReturn($entity);

        // Ensure the event manager is triggered
        $this->mockEventManager->shouldReceive('trigger')->twice();

        $result = $service->update($this->mockCreateForm, ['competition' => $data], $entity);
        $this->assertSame($entity, $result);
    }

    public function testUpdateMethodShortCircuitsOnFailedValidation()
    {
        // Build the test data
        $data = ['id' => 42, 'name' => 'Test Competition', 'divisions' => []];
        $entity = new Competition();
        $entity->setId($data['id'])->setName($data['name']);

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->withArgs([['competition' => $data]]);
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);
        $this->mockCreateForm->shouldReceive('getData')->never();

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->assertFalse($this->service->update($this->mockCreateForm, ['competition' => $data], $entity));
    }

    public function testSaveProxiesToObjectManager()
    {
        $entity = new Competition();

        $this->mockObjectManager->shouldReceive('persist')->with($entity)->once();
        $this->mockObjectManager->shouldReceive('flush')->once();

        $this->service->save($entity);
    }

    public function testRemoveProxiesToObjectManager()
    {
        $entity = new Competition();

        $this->mockObjectManager->shouldReceive('remove')->with($entity)->once();
        $this->mockObjectManager->shouldReceive('flush')->once();

        $this->mockEventManager->shouldReceive('trigger')->twice();

        $this->service->remove($entity);
    }
}
