<?php
namespace UsaRugbyStatsTest\AccountAdmin\Entity;

use Mockery;
use UsaRugbyStats\AccountAdmin\Entity\AccountHydrator;
use UsaRugbyStats\Account\Entity\Account;

class AccountHydratorTest extends \PHPUnit_Framework_TestCase
{
    protected $objectManager;

    public function setUp()
    {
        $metadata = Mockery::mock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $metadata->shouldReceive('getAssociationNames')->andReturn(array());
        $metadata->shouldReceive('hasAssociation')->andReturn(false);
        $metadata->shouldReceive('getIdentifierFieldNames')->andReturn(array('id'));
        $metadata->shouldReceive('getFieldNames')->andReturn(array('id','displayName'));
        $metadata->shouldReceive('getName')->andReturn('UsaRugbyStats\Account\Entity\Account');
        $metadata->shouldReceive('getTypeOfField')->withArgs(['id'])->andReturn('int');
        $metadata->shouldReceive('getTypeOfField')->withArgs(['displayName'])->andReturn('string');

        $this->objectManager = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $this->objectManager->shouldReceive('getClassMetadata')->andReturn($metadata);
        $this->objectManager->shouldReceive('find')->andReturn(null);
    }

    public function testHydrate()
    {
        $object = new Account();
        $data = array(
            'id' => '123',
            'display_name' => 'Foobar Bazbat',
        );

        $hydrator = new AccountHydrator($this->objectManager);
        $entity = $hydrator->hydrate($data, $object);

        $this->assertEquals(get_class($object), get_class($entity));
        $this->assertEquals($data['id'], $entity->getId());
    }

    public function testExtract()
    {
        $object = new Account();
        $object->setId(123);
        $object->setDisplayName('Foobar Bazbat');

        $hydrator = new AccountHydrator($this->objectManager);
        $data = $hydrator->extract($object);

        $this->assertInternalType('array', $data);
        $this->assertEquals($object->getId(), $data['userId']);
        $this->assertEquals($object->getDisplayName(), $data['display_name']);
    }
}
