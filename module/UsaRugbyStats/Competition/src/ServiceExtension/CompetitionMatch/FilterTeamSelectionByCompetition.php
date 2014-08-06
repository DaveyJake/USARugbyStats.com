<?php
namespace UsaRugbyStats\Competition\ServiceExtension\CompetitionMatch;

use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition;

/**
 * Processing Rule to disable editing of a match when it's locked
 * (while still allowing changing the isLocked field)
 *
 */
class FilterTeamSelectionByCompetition extends AbstractRule
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
        $id = $e->getParams()->competition->getId();

        foreach (['H', 'A'] as $side) {
            $team = $e->getParams()->form->get('match')->get('teams')->get($side)->get('team');
            $team->setFindMethod([
                'name'   => 'findAllTeamsInCompetition',
                'params' => [ 'competition' => $id ],
            ]);
        }
    }
}
