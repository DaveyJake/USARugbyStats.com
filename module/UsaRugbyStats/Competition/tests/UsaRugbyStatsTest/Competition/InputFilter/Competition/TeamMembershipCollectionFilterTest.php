<?php
namespace UsaRugbyStatsTest\Competition\InputFilter\Competition;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\Competition\TeamMembershipCollectionFilter;

class TeamMembershipCollectionFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testOverriddenIsValidDoesNotBreakDefaultOperation()
    {
        $data = [
            ['id' => 1, 'team' => 99],
            ['id' => 2, 'team' => 42],
        ];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new TeamMembershipCollectionFilter($mif);
        $obj->setData($data);
        $this->assertTrue($obj->isValid());
        $this->assertCount(0, $obj->getMessages());
    }

    public function testOverriddenIsValidWorksProperlyWhenInputDataIsEmpty()
    {
        $data = [];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new TeamMembershipCollectionFilter($mif);
        $obj->setData($data);
        $this->assertTrue($obj->isValid());
        $this->assertCount(0, $obj->getMessages());
    }

    public function testIsValidDoesNotAllowDuplicateTeams()
    {
        $data = [
            ['id' => 1, 'team' => 99],
            ['id' => 2, 'team' => 42],
            ['id' => NULL, 'team' => 99],
        ];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new TeamMembershipCollectionFilter($mif);
        $obj->setData($data);
        $this->assertFalse($obj->isValid());

        $messages = $obj->getMessages();
        $this->assertArrayHasKey(2, $messages);
        $this->assertArrayHasKey('team', $messages[2]);
        $this->assertArrayHasKey(0, $messages[2]['team']);
        $this->assertCount(1, $messages[2]['team']);
    }
}
