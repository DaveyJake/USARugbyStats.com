<?php
namespace UsaRugbyStatsTest\Competition\InputFilter\Competition;

use Mockery;
use UsaRugbyStats\Competition\InputFilter\Competition\Match\MatchTeamCollectionFilter;

class MatchTeamCollectionFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testOverriddenIsValidDoesNotBreakDefaultOperation()
    {
        $data = [
            'H' => ['id' => 1, 'team' => 99, 'players' => []],
            'A' => ['id' => 2, 'team' => 42, 'players' => []],
        ];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new MatchTeamCollectionFilter($mif);
        $obj->setData($data);
        $this->assertTrue($obj->isValid());
        $this->assertCount(0, $obj->getMessages());
    }

    public function testIsValidDoesNotAllowDuplicateTeams()
    {
        $data = [
            'H' => ['id' => 1, 'team' => 42, 'players' => []],
            'A' => ['id' => 2, 'team' => 42, 'players' => []],
        ];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new MatchTeamCollectionFilter($mif);
        $obj->setData($data);
        $this->assertFalse($obj->isValid());

        $messages = $obj->getMessages();
        $this->assertArrayHasKey('A', $messages);
        $this->assertArrayHasKey('team', $messages['A']);
        $this->assertArrayHasKey(0, $messages['A']['team']);
        $this->assertCount(1, $messages['A']['team']);
    }

    public function testIsValidDoesNotAllowDuplicatePlayersAcrossTeams()
    {
        $data = [
            'H' => ['id' => 1, 'team' => 42, 'players' => [['player' => 99]]],
            'A' => ['id' => 2, 'team' => 13, 'players' => [['player' => 99]]],
        ];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new MatchTeamCollectionFilter($mif);
        $obj->setData($data);
        $this->assertFalse($obj->isValid());

        $messages = $obj->getMessages();
        $this->assertArrayHasKey('A', $messages);
        $this->assertArrayHasKey('players', $messages['A']);
        $this->assertArrayHasKey(0, $messages['A']['players']);
        $this->assertArrayHasKey('player', $messages['A']['players'][0]);
        $this->assertCount(1, $messages['A']['players'][0]['player']);
    }

    public function testIsValidDoesNotAllowDuplicatePlayersOnSameTeam()
    {
        $data = [
            'H' => ['id' => 1, 'team' => 42, 'players' => [['player' => 99], ['player' => 99]]],
            'A' => ['id' => 2, 'team' => 13, 'players' => []],
        ];

        $mif = Mockery::mock('Zend\InputFilter\InputFilter');
        $mif->shouldReceive('setData');
        $mif->shouldReceive('isValid')->andReturn(true);
        $mif->shouldReceive('getValidInput');
        $mif->shouldReceive('getValues')->andReturnValues($data);
        $mif->shouldReceive('getRawValues');

        $obj = new MatchTeamCollectionFilter($mif);
        $obj->setData($data);
        $this->assertFalse($obj->isValid());

        $messages = $obj->getMessages();
        $this->assertArrayHasKey('H', $messages);
        $this->assertArrayHasKey('players', $messages['H']);
        $this->assertArrayHasKey(1, $messages['H']['players']);
        $this->assertArrayHasKey('player', $messages['H']['players'][1]);
        $this->assertCount(1, $messages['H']['players'][1]['player']);
    }
}
