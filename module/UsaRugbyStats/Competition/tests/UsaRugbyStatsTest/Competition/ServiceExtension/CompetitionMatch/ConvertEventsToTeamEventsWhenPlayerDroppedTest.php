<?php
namespace UsaRugbyStatsTest\Competition\ServiceExtension\CompetitionMatch;

use UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch\ConvertEventsToTeamEventsWhenPlayerDropped;
use Zend\EventManager\Event;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;

class ConvertEventsToTeamEventsWhenPlayerDroppedTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->extension = new ConvertEventsToTeamEventsWhenPlayerDropped();
    }

    public function testPreconditionWillProceedIfEntityProvided()
    {
        $match = \Mockery::mock('UsaRugbyStats\Competition\Entity\Competition\Match');

        $params = new \stdClass();
        $params->entity = $match;
        $event = new Event(null, null, $params);

        $this->assertTrue($this->extension->checkPrecondition($event));
    }

    public function testPreconditionRejectsEventWithoutEntity()
    {
        $params = new \stdClass();
        $event = new Event(null, null, $params);

        $this->assertFalse($this->extension->checkPrecondition($event));
    }

    public function testPreconditionRejectsEventWithEntityThatIsNotAMatch()
    {
        $params = new \stdClass();
        $params->entity = new \stdClass();
        $event = new Event(null, null, $params);

        $this->assertFalse($this->extension->checkPrecondition($event));
    }

    public function testHappyCase()
    {
        $match = new Match();

        $homeTeam = new MatchTeam();
        $homeTeam->setTeam((new Team())->setId(123)->setName('Home'));
        $match->setHomeTeam($homeTeam);

        $awayTeam = new MatchTeam();
        $awayTeam->setTeam((new Team())->setId(456)->setName('Away'));
        $match->setAwayTeam($awayTeam);

        $playerOne = new MatchTeamPlayer();
        $playerOne->setId(9999);

        $playerTwo = new MatchTeamPlayer();
        $playerTwo->setId(8888);

        $playerThree = new MatchTeamPlayer();
        $playerThree->setId(7777);

        // We add Player 3 to the roster so their events are retained
        // Player 1 and Player 2 events should be modified since they are not on the roster
        $homeTeam->addPlayer($playerThree);

        $scoreEvent = new ScoreEvent();
        $scoreEvent->setMinute(5);
        $scoreEvent->setPlayer($playerOne);
        $scoreEvent->setTeam($homeTeam);
        $scoreEvent->setType(ScoreEvent::TYPE_TRY);
        $match->addEvent($scoreEvent);

        $scoreEventTwo = new ScoreEvent();
        $scoreEventTwo->setMinute(5);
        $scoreEventTwo->setPlayer($playerThree);
        $scoreEventTwo->setTeam($homeTeam);
        $scoreEventTwo->setType(ScoreEvent::TYPE_TRY);
        $match->addEvent($scoreEventTwo);

        $cardEvent = new CardEvent();
        $cardEvent->setMinute(7);
        $cardEvent->setPlayer($playerOne);
        $cardEvent->setTeam($awayTeam);
        $cardEvent->setType(CardEvent::TYPE_RED);
        $match->addEvent($cardEvent);

        $subEvent = new SubEvent();
        $subEvent->setMinute(14);
        $subEvent->setPlayerOn($playerOne);
        $subEvent->setPlayerOff($playerTwo);
        $subEvent->setTeam($homeTeam);
        $subEvent->setType(SubEvent::TYPE_BLOOD);
        $match->addEvent($subEvent);

        $params = new \stdClass();
        $params->entity = $match;
        $event = new Event(null, null, $params);

        $this->extension->execute($event);

        // Score event #1 should be present, but converted to Team
        $this->assertTrue($match->hasEvent($scoreEvent));
        $this->assertNull($scoreEvent->getPlayer());

        // Score event #2 should be present and associated with player
        $this->assertTrue($match->hasEvent($scoreEventTwo));
        $this->assertSame($playerThree, $scoreEventTwo->getPlayer());

        // Card event should be present, but converted to Team
        $this->assertTrue($match->hasEvent($cardEvent));
        $this->assertNull($cardEvent->getPlayer());

        // Sub event should be removed
        $this->assertFalse($match->hasEvent($subEvent));
    }
}
