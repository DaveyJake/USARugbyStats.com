<?php
namespace UsaRugbyStatsTest\AccountAdmin\Form\Rbac\RoleAssignment;

use Mockery;
use UsaRugbyStats\AccountAdmin\Form\Rbac\RoleAssignment\CompetitionAdminFieldset;

class CompetitionAdminFieldsetTest extends \PHPUnit_Framework_TestCase
{

    public function testCreate()
    {
        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');

        $fieldset = new CompetitionAdminFieldset($om, $or);

        $this->assertEquals('competition-admin', $fieldset->getName());
        $this->assertTrue($fieldset->has('id'));
        $this->assertTrue($fieldset->has('type'));

        $inputFilter = $fieldset->getInputFilterSpecification();
        $this->assertArrayHasKey('id', $inputFilter);
        $this->assertArrayHasKey('type', $inputFilter);
    }

    public function testGetCompetition()
    {
        $competition = new \stdClass();

        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('find')->once()->andReturn($competition);

        $fieldset = new CompetitionAdminFieldset($om, $or);
        $this->assertSame($competition, $fieldset->getCompetition(999));
    }

    public function testGetCompetitionWithEmptyCompetitionId()
    {
        $competition = new \stdClass();

        $om = Mockery::mock('Doctrine\Common\Persistence\ObjectManager');
        $or = Mockery::mock('Doctrine\Common\Persistence\ObjectRepository');
        $or->shouldReceive('find')->never();

        $fieldset = new CompetitionAdminFieldset($om, $or);
        $this->assertNull($fieldset->getCompetition(''));
    }
}
