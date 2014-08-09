<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;

/**
 * Processing Rule to filter position selectors down to only appropriate to the competition variant
 */
class FilterTeamRosterPositionSelectors extends AbstractRule
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
        $variant = $e->getParams()->competition->getVariant();

        foreach (['H', 'A'] as $side) {
            $fsSide = $e->getParams()->form->get('match')->get('teams')->get($side);

            $teamid = $fsSide->get('team')->getValue();
            if ( empty($teamid) ) {
                continue;
            }

            $fsSide->get('players')->getTargetElement()->setVariant($variant);
            foreach ( $fsSide->get('players') as $fsPlayer ) {
                $fsPlayer->setVariant($variant);
            }
        }
    }
}
