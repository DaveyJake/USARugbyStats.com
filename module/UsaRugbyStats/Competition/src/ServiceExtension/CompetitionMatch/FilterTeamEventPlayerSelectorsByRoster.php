<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;

/**
 * Processing Rule to disable editing of a match when it's locked
 * (while still allowing changing the isLocked field)
 *
 */
class FilterTeamEventPlayerSelectorsByRoster extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->entity) ) {
            return false;
        }
        if ( ! isset($e->getParams()->competition) || ! $e->getParams()->competition instanceof Competition ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        foreach (['H', 'A'] as $side) {
            $fsSide = $e->getParams()->form->get('match')->get('teams')->get($side);

            $teamid = $fsSide->get('id')->getValue();
            if ( empty($teamid) ) {
                continue;
            }

            $targetElements = $fsSide->get('events')->getTargetElement();
            foreach ($targetElements as $fieldset) {
                $fieldset->filterPlayerSelectForTeam($teamid);
            }
            foreach ( $fsSide->get('events') as $fieldset ) {
                $fieldset->filterPlayerSelectForTeam($teamid);
            }
        }
    }
}
