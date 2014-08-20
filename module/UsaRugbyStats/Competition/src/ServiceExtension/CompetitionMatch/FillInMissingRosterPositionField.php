<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;

/**
 * Processing Rule to force specific order on roster slot positions
 */
class FillInMissingRosterPositionField extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->competition) ) {
            return false;
        }
        if ( ! isset($e->getParams()->data) ) {
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
            foreach ( $e->getParams()->data['match']['teams'][$side]['players'] as $key => &$record ) {
                $record['position'] = $key;
            }
        }
    }
}
