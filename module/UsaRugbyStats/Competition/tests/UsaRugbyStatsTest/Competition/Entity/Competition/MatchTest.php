<?php
namespace UsaRugbyStatsTest\Competition\Entity\Competition;

use Mockery;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Match;

class MatchTest extends \PHPUnit_Framework_TestCase
{

    public function testGetSetCompetition()
    {
        $obj = new Match();

        $comp = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition');

        // Test setting to an instance of Competition class
        $obj->setCompetition($comp);
        $this->assertSame($comp, $obj->getCompetition());

        // Test setting to null (disassociate from competition)
        $obj->setCompetition(NULL);
        $this->assertNull($obj->getCompetition());
    }

    public function testGetSetHomeTeam()
    {
        $obj = new Match();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $team0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setType')->withArgs(['H'])->once()->andReturnSelf();

        $obj->setHomeTeam($team0);

        $this->assertSame($team0, $obj->getHomeTeam());
    }

    public function testSetHomeTeamDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setHomeTeam(NULL);
    }

    public function testGetSetAwayTeam()
    {
        $obj = new Match();

        $team0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam');
        $team0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $team0->shouldReceive('setType')->withArgs(['A'])->once()->andReturnSelf();

        $obj->setAwayTeam($team0);

        $this->assertSame($team0, $obj->getAwayTeam());
    }

    public function testSetAwayTeamDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setAwayTeam(NULL);
    }

    public function testGetSetDate()
    {
        $obj = new Match();

        $date = new \DateTime();

        $obj->setDate($date);

        $this->assertSame($date, $obj->getDate());
    }

    public function testSetDateDoesNotAcceptNull()
    {
        $this->setExpectedException('PHPUnit_Framework_Error');

        $obj = new Match();
        $obj->setDate(NULL);
    }

    /**
     * @dataProvider providerGetSetStatus
     */
    public function testGetSetStatus($status, $valid)
    {
        if (! $valid) {
            $this->setExpectedException('InvalidArgumentException');
        }

        $obj = new Match();
        $obj->setStatus($status);
        $this->assertEquals($status, $obj->getStatus());
    }

    /**
     * Data Provider for testGetSetPosition (lists valid Match statuses)
     *
     * @return array
     */
    public function providerGetSetStatus()
    {
        return [
            [ 'NS', true ],
            [ 'S', true ],
            [ 'F', true ],
            [ 'HF', true ],
            [ 'AF', true ],
            [ 'C', true ],
            [ 'XX', false ],
            [ NULL, false ],
        ];
    }

    public function testStatusDefaultIsNotStarted()
    {
        $obj = new Match();
        $this->assertEquals('NS', $obj->getStatus());
    }

    public function testSetSignatures()
    {
        $obj = new Match();
        $collection = $obj->getSignatures();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');
        $sig1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        $newCollection = new ArrayCollection();
        $newCollection->add($sig0);
        $newCollection->add($sig1);

        // Do the add
        $obj->setSignatures($newCollection);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(2, $obj->getSignatures()->count());
    }

    public function testAddSignature()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->never();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');
        $sig1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        // Do teh add
        $obj->addSignature($sig1);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(2, $obj->getSignatures()->count());
    }

    public function testAddSignatureWillOverwriteExistingSignatureOfSameType()
    {
        $obj = new Match();
        $collection = $obj->getSignatures();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->withArgs([$obj])->twice()->andReturnSelf();

        // Add signature0 twice
        $coll = new ArrayCollection();
        $coll->add($sig0);
        $coll->add($sig0);
        $obj->addSignatures($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(1, $obj->getSignatures()->count());
    }

    public function testAddSignatures()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig0->shouldReceive('setMatch')->never();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');
        $sig1->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();
        $sig2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig2->shouldReceive('getType')->andReturn('REF');
        $sig2->shouldReceive('setMatch')->withArgs([$obj])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        // Do teh add
        $coll = new ArrayCollection();
        $coll->add($sig1);
        $coll->add($sig2);
        $obj->addSignatures($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        // Ensure we didn't replace the collection object, a no-no in Doctrineland
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(3, $obj->getSignatures()->count());
    }

    public function testHasSignature()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('getType')->andReturn('HC');
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('getType')->andReturn('AC');

        // Add roles to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);

        $this->assertTrue($obj->hasSignature($sig0));
        $this->assertFalse($obj->hasSignature($sig1));
    }

    public function testRemoveSignature()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('setMatch')->withArgs([NULL])->once()->andReturnSelf();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('setMatch')->never();

        // Add to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);
        $collection->add($sig1);

        // Do the remove
        $obj->removeSignature($sig0);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(1, $obj->getSignatures()->count());
        $this->assertFalse($obj->getSignatures()->contains($sig0));
        $this->assertTrue($obj->getSignatures()->contains($sig1));
    }

    public function testRemoveSignatures()
    {
        $obj = new Match();

        $sig0 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig0->shouldReceive('setMatch')->withArgs([NULL])->once()->andReturnSelf();
        $sig1 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig1->shouldReceive('setMatch')->never();
        $sig2 = Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match\MatchSignature');
        $sig2->shouldReceive('setMatch')->withArgs([NULL])->once()->andReturnSelf();

        // Add one to the existing collection
        $collection = $obj->getSignatures();
        $collection->add($sig0);
        $collection->add($sig1);
        $collection->add($sig2);

        // Do the remove
        $coll = new ArrayCollection();
        $coll->add($sig0);
        $coll->add($sig2);
        $obj->removeSignatures($coll);

        $this->assertInstanceOf('Doctrine\Common\Collections\Collection', $obj->getSignatures());
        $this->assertSame($collection, $obj->getSignatures());
        $this->assertEquals(1, $obj->getSignatures()->count());
        $this->assertFalse($obj->getSignatures()->contains($sig0));
        $this->assertTrue($obj->getSignatures()->contains($sig1));
        $this->assertFalse($obj->getSignatures()->contains($sig2));
    }

}
