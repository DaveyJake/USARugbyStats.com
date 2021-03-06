<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamPlayerFieldset;

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
        if ( $e->getParams()->entity->hasTeam('H') ) {
            $homeTeam = $e->getParams()->entity->getTeam('H')->getTeam();
            $homeFieldset = $e->getParams()->form->get('match')->get('teams')->get('H');
            $this->injectRosterEntriesForSide($e->getParams()->competition, $homeTeam, $homeFieldset);
        }

        if ( $e->getParams()->entity->hasTeam('A') ) {
            $awayTeam = $e->getParams()->entity->getTeam('A')->getTeam();
            $awayFieldset = $e->getParams()->form->get('match')->get('teams')->get('A');
            $this->injectRosterEntriesForSide($e->getParams()->competition, $awayTeam, $awayFieldset);
        }
    }

    protected function injectRosterEntriesForSide($competition, $team, $fieldset)
    {
        $players = $fieldset->get('players');

        // Pop off any existing entries and store them temporarily
        // (prevents manual change in number from ruining sort order on form render)
        $tempStorage = [];
        $usedNumbers = [];
        foreach ($players as $item) {
            $players->remove($item->getName());
            if ( ! empty($item->get('player')->getValue()) ) {
                $tempStorage[$item->get('position')->getValue()] = $item;
                array_push($usedNumbers, $item->get('number')->getValue());
            }
        }

        $optimalLevel = $competition->getVariant() == Competition::VARIANT_FIFTEENS ? 23 : 15;

        $maxRosteredPlayers = $competition instanceof Competition
            ? ( $competition->getMaxPlayersOnRoster() ?: $optimalLevel )
            : $optimalLevel;

        $positions = MatchTeamPlayerFieldset::$positions[$competition->getVariant()];

        // Pre-fill the first N roster slots with appropriate number and position
        $number = 0;
        foreach ($positions as $positionKey => $positionName) {
            $number++;
            if ( isset($tempStorage[$positionKey]) ) {
                $tempStorage[$positionKey]->setName($positionKey);
                $players->add($tempStorage[$positionKey]);
                unset($tempStorage[$positionKey]);
                continue;
            }

            $item = clone $players->getTargetElement();
            $item->setName($positionKey);
            $item->get('position')->setValue($positionKey);
            $item->get('number')->setValue(in_array($number, $usedNumbers, true) ? 0 : $number);
            $players->add($item);
        }

        // Re-add any extra records (>23) to the bottom
        if (count($tempStorage)) {
            foreach ($tempStorage as $item) {
                $players->add($item);
            }
        }
    }
}
