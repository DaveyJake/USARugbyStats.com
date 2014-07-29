<?php
namespace UsaRugbyStatsTest\RemoteDataSync\Jobs;

abstract class AbstractJobTest extends \PHPUnit_Framework_TestCase
{
    public function testGetServiceLocatorProxiesProperly()
    {
        $this->assertSame($this->mockServiceLocator, $this->job->getServiceLocator());
    }

    public function testSetServiceLocator()
    {
        $mock = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $this->job->setServiceLocator($mock);
        $this->assertSame($mock, $this->job->getServiceLocator());
    }

    public function testGetSharedManagerProxiesProperly()
    {
        $this->assertSame($this->mockEventManager, $this->job->getSharedManager());
    }

    public function testSetSharedManager()
    {
        $mock = \Mockery::mock('Zend\EventManager\SharedEventManagerInterface');
        $this->job->setSharedManager($mock);
        $this->assertSame($mock, $this->job->getSharedManager());
    }

    public function testUnsetSharedManager()
    {
        $this->job->unsetSharedManager();
        $this->assertNull($this->job->getSharedManager());
        $this->assertNull($this->job->job->sharedEventManager);
    }
}
