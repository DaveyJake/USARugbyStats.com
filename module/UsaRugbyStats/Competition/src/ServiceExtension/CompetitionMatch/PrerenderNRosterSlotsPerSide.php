<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;

/**
 * Processing Rule to pre-render N roster slots per side,
 * N = Competition::$maxPlayersOnRoster (defaults to 23)
 */
class PrerenderNRosterSlotsPerSide extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->competition) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $homeTeam = $e->getParams()->entity->getTeam('H')->getTeam();
        $homeFieldset = $e->getParams()->form->get('match')->get('teams')->get('H');
        $this->injectRosterEntriesForSide($e->getParams()->competition, $homeTeam, $homeFieldset);

        $awayTeam = $e->getParams()->entity->getTeam('A')->getTeam();
        $awayFieldset = $e->getParams()->form->get('match')->get('teams')->get('A');
        $this->injectRosterEntriesForSide($e->getParams()->competition, $homeTeam, $awayFieldset);
    }

    protected function injectRosterEntriesForSide($competition, $team, $fieldset)
    {
        $players = $fieldset->get('players');

        // Pop off any existing entries and store them temporarily
        // (prevents manual change in number from ruining sort order on form render)
        $tempStorage = [];
        foreach ($players as $item) {
            $players->remove($item->getName());
            $item->setName($item->get('number')->getValue());
            $item->addPlayerSelect($team);
            $tempStorage[$item->get('number')->getValue()] = $item;
        }

        $maxRosteredPlayers = $competition instanceof Competition
            ? ( $competition->getMaxPlayersOnRoster() ?: 23 )
            : 23;

        // Pre-fill the first 23 roster slots with appropriate number and position
        for ($key = 1; $key <= min($maxRosteredPlayers,23); $key++) {

            if ( isset($tempStorage[$key]) ) {
                $players->add($tempStorage[$key]);
                unset($tempStorage[$key]);
                continue;
            }

            $item = clone $players->getTargetElement();
            $item->setName($key);
            $item->addPlayerSelect($team);
            $players->add($item);

            $valueOptions = $item->get('position')->getValueOptions();
            $thisPosition = array_slice($valueOptions, $key-1, 1, true);
            if ( count($thisPosition) == 1 ) {
                $thisPositionKey = array_keys($thisPosition);
                $item->get('position')->setValue(array_pop($thisPositionKey));
                $item->get('number')->setValue($key);
            }
        }

        // Re-add any extra records (>23) to the bottom
        if (count($tempStorage)) {
            foreach ($tempStorage as $item) {
                $players->add($item);
            }
        }
    }
}
