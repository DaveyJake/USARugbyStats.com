<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature;

class MatchSignatureTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetId()
    {
        $obj = new MatchSignature();
        $this->assertNull($obj->getId());
        $obj->setId(12345);
        $this->assertEquals(12345, $obj->getId());
    }

    public function testGetSetTimestamp()
    {
        $dt = new \DateTime();

        $obj = new MatchSignature();
        $obj->setTimestamp($dt);
        $this->assertSame($dt, $obj->getTimestamp());
    }

    public function testGetSetTimestampHasADefaultValue()
    {
        $obj = new MatchSignature();
        $this->assertInstanceOf('DateTime', $obj->getTimestamp());
    }

    public function testGetSetTimestampOnlyAcceptsDateTimeObjects()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new MatchSignature();
        $obj->setTimestamp('now');
    }

    public function testSetTimestampDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new MatchSignature();
        $obj->setTimestamp(NULL);
    }

    public function testGetSetMatch()
    {
        $obj = new MatchSignature();

        $match = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');

        // Test setting to an instance of Match class
        $obj->setMatch($match);
        $this->assertSame($match, $obj->getMatch());
    }

    public function testSetMatchAcceptsNull()
    {
        $obj = new MatchSignature();

        // Test setting to null (disassociate from match)
        $obj->setMatch(NULL);
        $this->assertNull($obj->getMatch());
    }

    public function testGetSetAccount()
    {
        $obj = new MatchSignature();

        $acct = Mockery::mock('UsaRugbyStats\Account\Entity\Account');

        $obj->setAccount($acct);

        $this->assertSame($acct, $obj->getAccount());
    }

    public function testSetAccountDoesNotAcceptNull()
    {
        $obj = new MatchSignature();

        // Test setting to null (disassociate from team)
        $obj->setAccount(NULL);
        $this->assertNull($obj->getAccount());
    }

    /**
     * @dataProvider providerGetSetType
     */
    public function testGetSetType($type, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new MatchSignature();
        $obj->setType($type);
        $this->assertEquals($type, $obj->getType());
    }

    /**
     * Data Provider for testGetSetType (lists valid MatchSignature types)
     *
     * @return array
     */
    public function providerGetSetType()
    {
        return [ ['HC',true], ['AC',true], ['REF',true], ['NR4',true], ['X',false], [NULL,false] ];
    }

}
