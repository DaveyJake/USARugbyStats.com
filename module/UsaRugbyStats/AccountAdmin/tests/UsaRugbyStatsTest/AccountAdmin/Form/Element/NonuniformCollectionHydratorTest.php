<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Element;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Element\NonuniformCollectionHydrator;

class NonuniformCollectionHydratorTest extends \PHPUnit_Framework_TestCase
{
    protected $objectManager;

    public function setUp()
    {
        $metadata = Mockery::mock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $metadata->shouldReceive('getAssociationNames')->andReturn(array());
        $metadata->shouldReceive('hasAssociation')->andReturn(false);
        $metadata->shouldReceive('getIdentifierFieldNames')->andReturn(array('id'));
        $metadata->shouldReceive('getFieldNames')->andReturn(array('id'));
        $metadata->shouldReceive('getName')->andReturn('stdClass');
        $metadata->shouldReceive('getTypeOfField')->andReturn('int');

        $this->objectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $this->objectManager->shouldReceive('getClassMetadata')->andReturn($metadata);
        $this->objectManager->shouldReceive('find')->andReturn(null);
    }

    public function testHydrateWillPopulateEntityOfSpecifiedClass()
    {
        $data = array(
            '__class__' => 'stdClass',
            'id' => '123'
        );

        $obj = new NonuniformCollectionHydrator($this->objectManager);
        $entity = $obj->hydrate($data, NULL);
        $this->assertEquals('stdClass', get_class($entity));
    }

    public function testHydrateUseTheProvidedEntityIfNoClassIsSpecifiedInData()
    {
        $data = array('id' => '123');

        $obj = new NonuniformCollectionHydrator($this->objectManager);
        $entity = $obj->hydrate($data, new \stdClass());
        $this->assertEquals('stdClass', get_class($entity));
    }

    public function testExtract()
    {
        $entity = new \stdClass();

        $obj = new NonuniformCollectionHydrator($this->objectManager);
        $data = $obj->extract($entity);
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('__class__', $data);
        $this->assertEquals(get_class($entity), $data['__class__']);
    }
}
