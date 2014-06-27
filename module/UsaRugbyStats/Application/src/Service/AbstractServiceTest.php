<?php
namespace UsaRugbyStats\Application\Service;

use Mockery;
use UsaRugbyStats\Competition\Entity\Location;

class AbstractServiceTest extends \PHPUnit_Framework_TestCase
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
        $this->mockEventManager->shouldReceive('setIdentifiers');

        $this->service = new MyTestService();
        $this->service->setRepository($this->mockRepository);
        $this->service->setObjectManager($this->mockObjectManager);
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
        $service = Mockery::mock('UsaRugbyStats\Application\Service\AbstractService[save]');
        $service->shouldReceive('save')->once()->andReturnNull();

        // Inject the rest of the dependencies
        $service->setRepository($this->mockRepository);
        $service->setObjectManager($this->mockObjectManager);
        $service->setCreateForm($this->mockCreateForm);
        $service->setUpdateForm($this->mockUpdateForm);
        $service->setEventManager($this->mockEventManager);

        // Build the test data
        $data = ['id' => 42, 'name' => 'Test Location'];
        $entity = new Location();
        $entity->setId($data['id'])->setName($data['name']);

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->withArgs([$data]);
        $this->mockCreateForm->shouldReceive('getData')->once()->andReturn($entity);
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(true);

        // Ensure that proper events are triggered
        $this->mockEventManager->shouldReceive('trigger')->with('form.bind', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.bind.post', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.populate', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.populate.post', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.validate', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.validate.post', \Mockery::any(), \Mockery::any())->once();

        $result = $service->create($data);
        $this->assertInstanceOf(get_class($entity), $result);
        $this->assertEquals($entity, $result);
    }

    public function testUpdateMethod()
    {
        // Partial-mock the service class to isolate update() from save()
        $service = Mockery::mock('UsaRugbyStats\Application\Service\AbstractService[save]');
        $service->shouldReceive('save')->once()->andReturnNull();

        // Inject the rest of the dependencies
        $service->setRepository($this->mockRepository);
        $service->setObjectManager($this->mockObjectManager);
        $service->setCreateForm($this->mockCreateForm);
        $service->setUpdateForm($this->mockUpdateForm);
        $service->setEventManager($this->mockEventManager);

        // Build the test data
        $data = ['id' => 42, 'name' => 'Test Location'];
        $entity = new Location();
        $entity->setId($data['id'])->setName($data['name']);

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockUpdateForm->shouldReceive('bind')->once();
        $this->mockUpdateForm->shouldReceive('setData')->once()->withArgs([$data]);
        $this->mockUpdateForm->shouldReceive('getData')->once()->andReturn($entity);
        $this->mockUpdateForm->shouldReceive('isValid')->once()->andReturn(true);

        // Ensure that proper events are triggered
        $this->mockEventManager->shouldReceive('trigger')->with('form.bind', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.bind.post', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.populate', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.populate.post', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.validate', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('form.validate.post', \Mockery::any(), \Mockery::any())->once();

        $result = $service->update($entity, $data);
        $this->assertInstanceOf(get_class($entity), $result);
        $this->assertEquals($entity, $result);
    }

    public function testSaveProxiesToObjectManager()
    {
        $entity = new Location();

        $this->mockObjectManager->shouldReceive('persist')->with($entity)->once();
        $this->mockObjectManager->shouldReceive('flush')->once();

        // Ensure that proper events are triggered
        $this->mockEventManager->shouldReceive('trigger')->with('save', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('save.post', \Mockery::any(), \Mockery::any())->once();

        $this->service->save($entity);
    }

    public function testRemoveProxiesToObjectManager()
    {
        $entity = new Location();

        $this->mockObjectManager->shouldReceive('remove')->with($entity)->once();
        $this->mockObjectManager->shouldReceive('flush')->once();

        // Ensure that proper events are triggered
        $this->mockEventManager->shouldReceive('trigger')->with('remove', \Mockery::any(), \Mockery::any())->once();
        $this->mockEventManager->shouldReceive('trigger')->with('remove.post', \Mockery::any(), \Mockery::any())->once();

        $this->service->remove($entity);
    }
}

class MyTestService extends AbstractService
{

}
