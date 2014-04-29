<?php
namespace UsaRugbyStatsTest\Competition\Service\Competition;

use Mockery;
use UsaRugbyStats\Competition\Service\Competition\MatchService;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchServiceTest extends \PHPUnit_Framework_TestCase
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

        $this->service = new MatchService();
        $this->service->setMatchRepository($this->mockRepository);
        $this->service->setMatchObjectManager($this->mockObjectManager);
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
        $service = Mockery::mock('UsaRugbyStats\Competition\Service\Competition\MatchService[save]');
        $service->shouldReceive('save')->once()->andReturnNull();

        // Inject the rest of the dependencies
        $service->setMatchRepository($this->mockRepository);
        $service->setMatchObjectManager($this->mockObjectManager);
        $service->setCreateForm($this->mockCreateForm);
        $service->setUpdateForm($this->mockUpdateForm);
        $service->setEventManager($this->mockEventManager);

        // Build the test data
        $entity = new Match();

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->once()->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $this->mockCreateForm->shouldReceive('setData')->once();
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(true);

        // Ensure the event manager is triggered
        $this->mockEventManager->shouldReceive('trigger')->twice();

        $result = $service->create($this->mockCreateForm, []);
        $this->assertInstanceOf(get_class($entity), $result);
    }

    public function testCreateMethodShortCircuitsOnFailedValidation()
    {
        // Build the test data
        $entity = new Match();

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->once()->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $this->mockCreateForm->shouldReceive('setData')->once();
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->assertFalse($this->service->create($this->mockCreateForm, []));
    }

    public function testCreateMethodPreprocessesFormInputDataForClassTableInheritance()
    {
        // Build the test data
        $entity = new Match();

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->once()->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $suite = $this;
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['homeTeam']['events']);
            $this->assertCount(1, $a['match']['homeTeam']['events']);
            $this->assertEquals('Foo\Bar\BarEntity', $a['match']['homeTeam']['events'][0]['__class__']);
            $this->assertInternalType('array', @$a['match']['awayTeam']['events']);
            $this->assertCount(1, $a['match']['awayTeam']['events']);
            $this->assertEquals('Foo\Bar\BazEntity', $a['match']['awayTeam']['events'][0]['__class__']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->setAvailableMatchTeamEventTypes([
            'testone' => [
                'name' => 'testone',
                'entity_class' => 'Foo\Bar\BarEntity',
            ],
            'testtwo' => [
                'name' => 'testtwo',
                'entity_class' => 'Foo\Bar\BazEntity',
            ],
        ]);

        $this->service->create($this->mockCreateForm, [
            'match' => [
                'homeTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'testone',
                        ],
                    ],
                ],
                'awayTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'testtwo',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testCreateMethodPreprocessesFormInputDataForClassTableInheritanceWithNonexistentEventsOnly()
    {
        // Build the test data
        $entity = new Match();

        // Specify the entity class type
        $this->mockRepository->shouldReceive('getClassName')->once()->andReturn(get_class($entity));

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->once();
        $suite = $this;
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['homeTeam']['events']);
            $this->assertCount(0, $a['match']['homeTeam']['events']);
            $this->assertInternalType('array', @$a['match']['awayTeam']['events']);
            $this->assertCount(0, $a['match']['awayTeam']['events']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->create($this->mockCreateForm, [
            'match' => [
                'homeTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'test',
                        ],
                    ],
                ],
                'awayTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'test',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testUpdateMethod()
    {
        // Partial-mock the service class to isolate create() from save()
        $service = Mockery::mock('UsaRugbyStats\Competition\Service\Competition\MatchService[save]');
        $service->shouldReceive('save')->once()->andReturnNull();

        // Inject the rest of the dependencies
        $service->setMatchRepository($this->mockRepository);
        $service->setMatchObjectManager($this->mockObjectManager);
        $service->setCreateForm($this->mockCreateForm);
        $service->setUpdateForm($this->mockUpdateForm);
        $service->setEventManager($this->mockEventManager);

        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('setData')->once();
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(true);
        $this->mockCreateForm->shouldReceive('getData')->once()->andReturn($entity);

        // Ensure the event manager is triggered
        $this->mockEventManager->shouldReceive('trigger')->twice();

        $result = $service->update($this->mockCreateForm, []);
        $this->assertSame($entity, $result);
    }

    public function testUpdateMethodShortCircuitsOnFailedValidation()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->never();
        $this->mockCreateForm->shouldReceive('setData')->once();
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);
        $this->mockCreateForm->shouldReceive('getData')->never();

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->assertFalse($this->service->update($this->mockCreateForm, []));
    }

    public function testUpdateMethodPreprocessesFormInputDataForClassTableInheritance()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $suite = $this;
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['homeTeam']['events']);
            $this->assertCount(1, $a['match']['homeTeam']['events']);
            $this->assertEquals('Foo\Bar\BarEntity', $a['match']['homeTeam']['events'][0]['__class__']);
            $this->assertInternalType('array', @$a['match']['awayTeam']['events']);
            $this->assertCount(1, $a['match']['awayTeam']['events']);
            $this->assertEquals('Foo\Bar\BazEntity', $a['match']['awayTeam']['events'][0]['__class__']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->setAvailableMatchTeamEventTypes([
            'testone' => [
                'name' => 'testone',
                'entity_class' => 'Foo\Bar\BarEntity',
            ],
            'testtwo' => [
                'name' => 'testtwo',
                'entity_class' => 'Foo\Bar\BazEntity',
            ],
        ]);

        $this->service->update($this->mockCreateForm, [
            'match' => [
                'homeTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'testone',
                        ],
                    ],
                ],
                'awayTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'testtwo',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testUpdateMethodPreprocessesFormInputDataForClassTableInheritanceWithNonexistentEventsOnly()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $suite = $this;
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['homeTeam']['events']);
            $this->assertCount(0, $a['match']['homeTeam']['events']);
            $this->assertInternalType('array', @$a['match']['awayTeam']['events']);
            $this->assertCount(0, $a['match']['awayTeam']['events']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->update($this->mockCreateForm, [
            'match' => [
                'homeTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'test',
                        ],
                    ],
                ],
                'awayTeam' => [
                    'events' => [
                        [
                            'id' => '',
                            'event' => 'test',
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testSaveProxiesToObjectManager()
    {
        $entity = new Match();

        $this->mockObjectManager->shouldReceive('persist')->with($entity)->once();
        $this->mockObjectManager->shouldReceive('flush')->once();

        $this->service->save($entity);
    }

    public function testRemoveProxiesToObjectManager()
    {
        $entity = new Match();

        $this->mockObjectManager->shouldReceive('remove')->with($entity)->once();
        $this->mockObjectManager->shouldReceive('flush')->once();

        $this->service->remove($entity);
    }
}
