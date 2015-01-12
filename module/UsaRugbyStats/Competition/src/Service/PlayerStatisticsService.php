<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use UsaRugbyStats\Competition\Service\PlayerStatistics\AppearanceCreditCalculator;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Competition\Entity\Competition;

class PlayerStatisticsService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;
    use CompetitionMatchServiceTrait;

    public function attachDefaultListeners()
    {
        $this->getEventManager()->attachAggregate(new AppearanceCreditCalculator());
    }

    public function getStatisticsFor(AccountInterface $account)
    {
        $params = new \ArrayObject(['account' => $account]);
        $results = $this->getEventManager()->trigger(__FUNCTION__ . '.pre', $this, $params);
        if ( $results->stopped() ) {
            return $results->last();
        }

        $entryTemplate = [ 'PTS' => 0, 'TR' => 0, 'CV' => 0, 'PT' => 0, 'PK' => 0, 'DG' => 0, 'YC' => 0, 'RC' => 0  ];

        $params['result'] = [
            'career'   => $entryTemplate,
            'season'   => [],
            'team'     => [],
            'opponent' => [],
        ];

        // Fetch a list of all the matches this player has participated in
        $matches = $this->getCompetitionMatchService()->getRepository()->findAllForPlayer($account);
        foreach ($matches as $match) {
            if (! $match instanceof Match) {
                continue;
            }
            // Exclude friendly matches from statistics calculations
            if ( $match->getCompetition() instanceof Competition && $match->getCompetition()->isFriendly() ) {
                continue;
            }
            $playerPosition = $match->getRosterPositionForPlayer($account);
            if (! $playerPosition instanceof MatchTeamPlayer) {
                continue;
            }

            $matchYear = $match->getDate()->format('Y') . '-' . ($match->getDate()->format('Y')+1);

            //--------- Result Array Structure Preconfiguration ---------//
            if ( !isset($params['result']['season'][$matchYear]) ) {
                $params['result']['season'][$matchYear] = ['cumulative' => $entryTemplate, 'team' => [], 'opponent' => []];
            }
            $teamid = $playerPosition->getTeam()->getTeam()->getId();
            if ( !isset($params['result']['team'][$teamid]) ) {
                $params['result']['team'][$teamid] = ['career' => $entryTemplate, 'season' => []];
            }
            if ( !isset($params['result']['team'][$teamid]['season'][$matchYear]) ) {
                $params['result']['team'][$teamid]['season'][$matchYear] = $entryTemplate;
            }
            if ( !isset($params['result']['season'][$matchYear]['team'][$teamid]) ) {
                $params['result']['season'][$matchYear]['team'][$teamid] = $entryTemplate;
            }
            $opponent = $match->getTeam($playerPosition->getTeam()->getType() == 'H' ? 'A' : 'H');
            $opponentId = $opponent->getTeam()->getId();
            if ( !isset($params['result']['opponent'][$opponentId]['career']) ) {
                $params['result']['opponent'][$opponentId]['career'] = $entryTemplate;
            }
            if ( !isset($params['result']['opponent'][$opponentId]['season'][$matchYear]) ) {
                $params['result']['opponent'][$opponentId]['season'][$matchYear] = $entryTemplate;
            }
            if ( !isset($params['result']['season'][$matchYear]['opponent'][$opponentId]) ) {
                $params['result']['season'][$matchYear]['opponent'][$opponentId] = $entryTemplate;
            }
            //--------- Result Array Structure Preconfiguration ---------//

            $params['match'] = $match;
            $params['matchYear'] = $matchYear;
            $this->getEventManager()->trigger(__FUNCTION__ . '.match.pre', $this, $params);

            // Fetch all the game events involving this player
            $gameEvents = $match->getEvents()->filter(function (MatchTeamEvent $event) use ($account) {
                return $event->hasPlayer($account);
            });
            if ( count($gameEvents) == 0 ) {
                continue;
            }

            foreach ($gameEvents as $event) {
                $event instanceof MatchTeamEvent;

                $params['event'] = $event;
                $this->getEventManager()->trigger(__FUNCTION__ . '.match.event.pre', $this, $params);

                // Calculate score and card statistics
                $result = $this->processEvent($event);

                // Increment counters for career stats
                if (isset($params['result']['career'][$result['type']])) {
                    $params['result']['career'][$result['type']]++;
                }
                $params['result']['career']['PTS'] += $result['value'];

                // Increment counters for season stats
                if (isset($params['result']['season'][$matchYear]['cumulative'][$result['type']])) {
                    $params['result']['season'][$matchYear]['cumulative'][$result['type']]++;
                }
                $params['result']['season'][$matchYear]['cumulative']['PTS'] += $result['value'];

                // Increment counters for team stats
                if (isset($params['result']['team'][$teamid]['career'][$result['type']])) {
                    $params['result']['team'][$teamid]['career'][$result['type']]++;
                }
                $params['result']['team'][$teamid]['career']['PTS'] += $result['value'];
                if (isset($params['result']['team'][$teamid]['season'][$matchYear][$result['type']])) {
                    $params['result']['team'][$teamid]['season'][$matchYear][$result['type']]++;
                }
                $params['result']['team'][$teamid]['season'][$matchYear]['PTS'] += $result['value'];
                if (isset($params['result']['season'][$matchYear]['team'][$teamid][$result['type']])) {
                    $params['result']['season'][$matchYear]['team'][$teamid][$result['type']]++;
                }
                $params['result']['season'][$matchYear]['team'][$teamid]['PTS'] += $result['value'];

                // Increment counters for opponent stats
                if (isset($params['result']['opponent'][$opponentId]['career'][$result['type']])) {
                    $params['result']['opponent'][$opponentId]['career'][$result['type']]++;
                }
                $params['result']['opponent'][$opponentId]['career']['PTS'] += $result['value'];
                if (isset($params['result']['opponent'][$opponentId]['season'][$matchYear][$result['type']])) {
                    $params['result']['opponent'][$opponentId]['season'][$matchYear][$result['type']]++;
                }
                $params['result']['opponent'][$opponentId]['season'][$matchYear]['PTS'] += $result['value'];
                if (isset($params['result']['season'][$matchYear]['opponent'][$opponentId][$result['type']])) {
                    $params['result']['season'][$matchYear]['opponent'][$opponentId][$result['type']]++;
                }
                $params['result']['season'][$matchYear]['opponent'][$opponentId]['PTS'] += $result['value'];

                $this->getEventManager()->trigger(__FUNCTION__ . '.match.event.post', $this, $params);
            }

            $this->getEventManager()->trigger(__FUNCTION__ . '.match.post', $this, $params);
        }

        $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $params);

        return $params['result'];
    }

    protected function processEvent(MatchTeamEvent $e)
    {
        switch ( $e->getDiscriminator() ) {
            case 'score':
            {
                return [ 'type' => $e->getType(), 'value' => $e->getPoints() ];
            }
            case 'card':
            {
                return [ 'type' => $e->getType() . 'C', 'value' => 0 ];
            }
            case 'sub':
            {
                return [ 'type' => $e->getType(), 'value' => 0 ];
            }
        }
    }

}
