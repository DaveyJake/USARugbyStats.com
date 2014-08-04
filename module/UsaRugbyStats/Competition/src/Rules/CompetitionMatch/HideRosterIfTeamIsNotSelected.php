<?php
namespace UsaRugbyStats\Competition\Rules\CompetitionMatch;

use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;
use Zend\EventManager\EventInterface;

class HideRosterIfTeamIsNotSelected extends AbstractRule
{
    public function checkPrecondition(EventInterface $e)
    {
        if ( ! isset($e->getParams()->flags) ) {
            return false;
        }

        return true;
    }

    public function execute(EventInterface $e)
    {
        foreach (['H','A'] as $key) {
            $side = isset($e->getParams()->entity)
                  ? $e->getParams()->entity->getTeam($key)
                  : null;
            if (! $side instanceof MatchTeam) {
                $e->getParams()->flags->{"match.teams.$key.score%visible"}->off();
                $e->getParams()->flags->{"match.teams.$key.players%visible"}->off();
                continue;
            }
            if (! $side->getTeam() instanceof Team) {
                $e->getParams()->flags->{"match.teams.$key.score%visible"}->off();
                $e->getParams()->flags->{"match.teams.$key.players%visible"}->off();
                continue;
            }
            $e->getParams()->flags->{"match.teams.$key.score%visible"}->andWith(true);
            $e->getParams()->flags->{"match.teams.$key.players%visible"}->andWith(true);
        }
    }
}
