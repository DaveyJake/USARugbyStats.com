<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;
use UsaRugbyStats\Competition\Form\Fieldset\Competition\Match\MatchTeamPlayerFieldset;

/**
 * Processing Rule to force specific order on roster slot positions
 */
class ForceRosterSlotSelections extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->competition) ) {
            return false;
        }
        if ( ! isset($e->getParams()->data) ) {
            return false;
        }
        if ( ! isset($e->getParams()->form) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        $variant = $e->getParams()->competition->getVariant();

        foreach (['H','A'] as $side) {
            if ( !isset($e->getParams()->data['match']['teams'][$side]['players']) ) {
                continue;
            }
            $fieldset = $e->getParams()->form->get('match')->get('teams')->get($side)->get('players')->getTargetElement();
            $fieldset->setVariant($variant);

            $key = 1;
            foreach ( $e->getParams()->data['match']['teams'][$side]['players'] as &$record ) {
                $valueOptions = MatchTeamPlayerFieldset::$positions[$variant];
                $thisPosition = array_slice($valueOptions, $key-1, 1, true);
                if ( count($thisPosition) == 1 ) {
                    $thisPositionKey = array_keys($thisPosition);
                    $record['position'] = array_pop($thisPositionKey);
                }
                $key++;
            }
        }
    }
}
