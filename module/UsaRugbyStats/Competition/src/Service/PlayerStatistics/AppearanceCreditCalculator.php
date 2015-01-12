<?php
namespace UsaRugbyStats\Competition\Service\PlayerStatistics;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Team;

class AppearanceCreditCalculator implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $events->attach('getStatisticsFor.match.pre', [$this, 'processMatch']);
        $events->attach('getStatisticsFor.match.event.post', [$this, 'processMatchEvent']);
    }

    /**
     * Player gets a 'started' credit for each match they started, excluding
     * cases where they are placed on the reserve roster
     *
     * @param EventInterface $e
     */
    public function processMatch(EventInterface $e)
    {
        $match = $e->getParams()['match'];
        if (! $match instanceof Match) {
            return;
        }
        $account = $e->getParams()['account'];
        if (! $account instanceof AccountInterface) {
            return;
        }

        $e->getParams()['match_credits'] = ['played' => 0, 'started' => 0, 'sub' => 0];

        $position = $match->getRosterPositionForPlayer($account);
        if (! $position instanceof MatchTeamPlayer) {
            return;
        }
        if ( $position->isReserve() ) {
            return;
        }

        @$e->getParams()['result']['career']['credits']['played'] += 1;
        @$e->getParams()['result']['career']['credits']['started'] += 1;
        @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['cumulative']['credits']['played'] += 1;
        @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['cumulative']['credits']['started'] += 1;
        
        if ( ( $team = $this->getTeam($position) ) instanceof Team ) {
            @$e->getParams()['result']['team'][$team->getId()]['career']['credits']['played'] += 1;
            @$e->getParams()['result']['team'][$team->getId()]['career']['credits']['started'] += 1;
            @$e->getParams()['result']['team'][$team->getId()]['season'][$e->getParams()['matchYear']]['credits']['played'] += 1;
            @$e->getParams()['result']['team'][$team->getId()]['season'][$e->getParams()['matchYear']]['credits']['started'] += 1;
            
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['team'][$team->getId()]['credits']['played'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['team'][$team->getId()]['credits']['started'] += 1;
        }
        if ( ( $opponent = $this->getOpponent($position) ) instanceof Team ) {
            @$e->getParams()['result']['opponent'][$opponent->getId()]['career']['credits']['played'] += 1;
            @$e->getParams()['result']['opponent'][$opponent->getId()]['career']['credits']['started'] += 1;
            @$e->getParams()['result']['opponent'][$opponent->getId()]['season'][$e->getParams()['matchYear']]['credits']['played'] += 1;
            @$e->getParams()['result']['opponent'][$opponent->getId()]['season'][$e->getParams()['matchYear']]['credits']['started'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['opponent'][$opponent->getId()]['credits']['played'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['opponent'][$opponent->getId()]['credits']['started'] += 1;
        }

        $e->getParams()['match_credits']['played'] = 1;
        $e->getParams()['match_credits']['started'] = 1;
    }

    /**
     * Player gets a 'subbed-on' credit for being subbed into the match
     *
     * @param EventInterface $e
     */
    public function processMatchEvent(EventInterface $e)
    {
        $match = $e->getParams()['match'];
        if (! $match instanceof Match) {
            return;
        }
        $event = $e->getParams()['event'];
        if (! $event instanceof SubEvent) {
            return;
        }
        $account = $e->getParams()['account'];
        if (! $account instanceof AccountInterface) {
            return;
        }
        if ( $event->getPlayerOn()->getPlayer()->getId() != $account->getId() ) {
            return;
        }
        $position = $match->getRosterPositionForPlayer($account);
        if (! $position instanceof MatchTeamPlayer) {
            return;
        }

        // Give the player a sub credit as they were subbed into the game
        @$e->getParams()['result']['career']['credits']['sub'] += 1;
        @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['cumulative']['credits']['sub'] += 1;
        if ( ( $team = $this->getTeam($position) ) instanceof Team ) {
            @$e->getParams()['result']['team'][$team->getId()]['career']['credits']['sub'] += 1;
            @$e->getParams()['result']['team'][$team->getId()]['season'][$e->getParams()['matchYear']]['credits']['sub'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['team'][$team->getId()]['credits']['sub'] += 1;
        }
        if ( ( $opponent = $this->getOpponent($position) ) instanceof Team ) {
            @$e->getParams()['result']['opponent'][$opponent->getId()]['career']['credits']['sub'] += 1;
            @$e->getParams()['result']['opponent'][$opponent->getId()]['season'][$e->getParams()['matchYear']]['credits']['sub'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['opponent'][$opponent->getId()]['credits']['sub'] += 1;
        }

        // If they don't already have a played credit...
        if ( isset($e->getParams()['match_credits']) && $e->getParams()['match_credits']['played'] > 0 ) {
            return;
        }

        // give them a credit for having played in the game, since they were subbed in
        @$e->getParams()['result']['career']['credits']['played'] += 1;
        @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['cumulative']['credits']['played'] += 1;
        if ( ( $team = $this->getTeam($position) ) instanceof Team ) {
            @$e->getParams()['result']['team'][$team->getId()]['career']['credits']['played'] += 1;
            @$e->getParams()['result']['team'][$team->getId()]['season'][$e->getParams()['matchYear']]['credits']['played'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['team'][$team->getId()]['credits']['played'] += 1;
        }
        if ( ( $opponent = $this->getOpponent($position) ) instanceof Team ) {
            @$e->getParams()['result']['opponent'][$opponent->getId()]['career']['credits']['played'] += 1;
            @$e->getParams()['result']['opponent'][$opponent->getId()]['season'][$e->getParams()['matchYear']]['credits']['played'] += 1;
            @$e->getParams()['result']['season'][$e->getParams()['matchYear']]['opponent'][$opponent->getId()]['credits']['played'] += 1;
        }
    }

    protected function getTeam($position)
    {
        $matchTeam = $position->getTeam();
        if (! $matchTeam instanceof MatchTeam) {
            return;
        }
        $team = $matchTeam->getTeam();
        if (! $team instanceof Team) {
            return;
        }

        return $team;
    }

    protected function getOpponent($position)
    {
        $matchTeam = $position->getTeam();
        if (! $matchTeam instanceof MatchTeam) {
            return;
        }
        $match = $position->getTeam()->getMatch();
        if (! $match instanceof Match) {
            return;
        }
        $team = $match->getTeam($matchTeam->isHomeTeam() ? 'A' : 'H');
        if (! $team instanceof MatchTeam) {
            return;
        }

        return $team->getTeam();
    }
}
