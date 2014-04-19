<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;

class MatchTeamPlayerTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSetTeam()
    {
        $obj = new MatchTeamPlayer();

        $team = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');

        // Test setting to an instance of MatchTeam class
        $obj->setTeam($team);
        $this->assertSame($team, $obj->getTeam());
    }

    public function testSetTeamAcceptsNull()
    {
        $obj = new MatchTeamPlayer();

        // Test setting to null (disassociate from MatchTeam)
        $obj->setTeam(NULL);
        $this->assertNull($obj->getTeam());
    }

    /**
     * @dataProvider providerGetSetNumber
     */
    public function testGetSetNumber($number, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new MatchTeamPlayer();
        $obj->setNumber($number);
        $this->assertEquals($number, $obj->getNumber());
    }

    /**
     * Data Provider for testGetSetPosition (lists valid MatchTeamPlayer positions)
     *
     * @return array
     */
    public function providerGetSetNumber()
    {
        return [
            [ 0, true ],
            [ 5, true ],
            [ 25, true ],
            [ 99, true ],
            [ 100, false ],
            [ -1, false ],
            [ 'A', false ],
            [ '0A', false ],
        ];
    }

    /**
     * @dataProvider providerGetSetPosition
     */
    public function testGetSetPosition($position, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new MatchTeamPlayer();
        $obj->setPosition($position);
        $this->assertEquals($position, $obj->getPosition());
    }

    /**
     * Data Provider for testGetSetPosition (lists valid MatchTeamPlayer positions)
     *
     * @return array
     */
    public function providerGetSetPosition()
    {
        return [
            [ 'LHP', true ],
            [ 'H', true ],
            [ 'THP', true ],
            [ 'L1', true ],
            [ 'L2', true ],
            [ 'OSF', true ],
            [ 'BSF', true ],
            [ 'N8', true ],
            [ 'SH', true ],
            [ 'FH', true ],
            [ 'IC', true ],
            [ 'OC', true ],
            [ 'W1', true ],
            [ 'W2', true ],
            [ 'FB', true ],
            [ 'R1', true ],
            [ 'R2', true ],
            [ 'R3', true ],
            [ 'R4', true ],
            [ 'R5', true ],
            [ 'R6', true ],
            [ 'R7', true ],
            [ 'R8', true ],
            [ 'XX', false ],
            [ NULL, false ],
        ];
    }

    /**
     * @dataProvider providerOfTruthyValues
     */
    public function testGetSetIsFrontRow($tf, $valid)
    {
        $obj = new MatchTeamPlayer();
        $obj->setIsFrontRow($tf);
        $this->assertEquals($valid, $obj->getIsFrontRow());
        $this->assertEquals($valid, $obj->isFrontRow());
    }

    /**
     * Data Provider for truthy values
     *
     * @return array
     */
    public function providerOfTruthyValues()
    {
        return [
            [ true, true ],
            [ false, false ],
            [ 1, true ],
            [ 0, false ],
            [ NULL, false ],
        ];
    }
}
