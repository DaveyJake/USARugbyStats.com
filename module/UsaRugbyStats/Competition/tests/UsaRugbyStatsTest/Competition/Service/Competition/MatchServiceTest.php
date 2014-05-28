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

        $this->mockEventManager = new \Zend\EventManager\EventManager();

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
        $this->mockCreateForm->shouldReceive('getData')->once()->andReturn($entity);

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
            $this->assertInternalType('array', @$a['match']['teams']['H']['events']);
            $this->assertCount(1, $a['match']['teams']['H']['events']);
            $this->assertEquals('Foo\Bar\BarEntity', $a['match']['teams']['H']['events'][0]['__class__']);
            $this->assertInternalType('array', @$a['match']['teams']['A']['events']);
            $this->assertCount(1, $a['match']['teams']['A']['events']);
            $this->assertEquals('Foo\Bar\BazEntity', $a['match']['teams']['A']['events'][0]['__class__']);

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
                'teams' => [
                    'H' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'testone',
                            ],
                        ],
                    ],
                    'A' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'testtwo',
                            ],
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
            $this->assertInternalType('array', @$a['match']['teams']['H']['events']);
            $this->assertCount(0, $a['match']['teams']['H']['events']);
            $this->assertInternalType('array', @$a['match']['teams']['A']['events']);
            $this->assertCount(0, $a['match']['teams']['A']['events']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->create($this->mockCreateForm, [
            'match' => [
                'teams' => [
                    'H' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'test',
                            ],
                        ],
                    ],
                    'A' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'test',
                            ],
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
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once();
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(true);
        $this->mockCreateForm->shouldReceive('getData')->once()->andReturn($entity);

        $result = $service->update($this->mockCreateForm, [], $entity);
        $this->assertSame($entity, $result);
    }

    public function testUpdateMethodShortCircuitsOnFailedValidation()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once();
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);
        $this->mockCreateForm->shouldReceive('getData')->never();

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->assertFalse($this->service->update($this->mockCreateForm, [], $entity));
    }

    public function testUpdateMethodPreprocessesFormInputDataForClassTableInheritance()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $suite = $this;
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['teams']['H']['events']);
            $this->assertCount(1, $a['match']['teams']['H']['events']);
            $this->assertEquals('Foo\Bar\BarEntity', $a['match']['teams']['H']['events'][0]['__class__']);
            $this->assertInternalType('array', @$a['match']['teams']['A']['events']);
            $this->assertCount(1, $a['match']['teams']['A']['events']);
            $this->assertEquals('Foo\Bar\BazEntity', $a['match']['teams']['A']['events'][0]['__class__']);

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
                'teams' => [
                    'H' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'testone',
                            ],
                        ],
                    ],
                    'A' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'testtwo',
                            ],
                        ],
                    ],
                ],
            ],
        ], $entity);
    }

    public function testUpdateMethodPreprocessesFormInputDataForClassTableInheritanceWithNonexistentEventsOnly()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $suite = $this;
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['teams']['H']['events']);
            $this->assertCount(0, $a['match']['teams']['H']['events']);
            $this->assertInternalType('array', @$a['match']['teams']['A']['events']);
            $this->assertCount(0, $a['match']['teams']['A']['events']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->update($this->mockCreateForm, [
            'match' => [
                'teams' => [
                    'H' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'test',
                            ],
                        ],
                    ],
                    'A' => [
                        'events' => [
                            [
                                'id' => '',
                                'event' => 'test',
                            ],
                        ],
                    ],
                ],
            ],
        ], $entity);
    }

    public function testUpdateMethodRemovesAnyPreexistingSignaturesWhenMatchIsModified()
    {
        // Build the test data
        $entity = new Match();

        // Mock the form actions
        $suite = $this;
        $this->mockCreateForm->shouldReceive('bind')->withArgs([$entity])->once();
        $this->mockCreateForm->shouldReceive('setData')->once()->with(Mockery::on(function ($a) use ($suite) {
            // Ensure that Form::setData gets the preprocessed array
            $this->assertInternalType('array', @$a['match']['signatures']);
            $this->assertCount(1, $a['match']['signatures']);
            $sigKeys = array_keys($a['match']['signatures']);
            $sig = $a['match']['signatures'][array_pop($sigKeys)];
            $this->assertNull($sig['id']);
            $this->assertEquals('AC', $sig['type']);

            return true;
        }));
        $this->mockCreateForm->shouldReceive('isValid')->once()->andReturn(false);

        // Ensure the event manager is not triggered
        $this->mockEventManager->shouldReceive('trigger')->never();

        $this->service->update($this->mockCreateForm, [
            'match' => [
                'signatures' => [
                    [
                        'id' => 123,
                        'type' => 'HC',
                        'account' => 1,
                    ],
                    [
                        'id' => NULL,
                        'type' => 'AC',
                        'account' => 5,
                    ],
                ],
            ],
        ], $entity);
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
