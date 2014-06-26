<?php
namespace UsaRugbyStats\Competition\Service;

use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\EventManagerAwareInterface;
use UsaRugbyStats\Application\Entity\AccountInterface;
use UsaRugbyStats\Competition\Traits\CompetitionMatchServiceTrait;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent;

class PlayerStatisticsService implements EventManagerAwareInterface
{
    use EventManagerAwareTrait;
    use CompetitionMatchServiceTrait;

    public function getStatisticsFor(AccountInterface $account)
    {
        $params = compact('account');
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
        $matches = $this->getCompetitionMatchService()->getMatchRepository()->findAllForPlayer($account);
        foreach ($matches as $match) {
            $match instanceof Match;

            $matchYear = $match->getDate()->format('Y') . '-' . ($match->getDate()->format('Y')+1);

            // Fetch all the game events involving this player
            $gameEvents = $match->getEvents()->filter(function (MatchTeamEvent $event) use ($account) {
                return $event->hasPlayer($account);
            });
            if ( count($gameEvents) == 0 ) {
                continue;
            }

            foreach ($gameEvents as $event) {
                $event instanceof MatchTeamEvent;

                $result = $this->processEvent($event);
                if ( is_null($result) ) {
                    continue;
                }

                // Increment counters for career stats
                $params['result']['career'][$result['type']]++;
                $params['result']['career']['PTS'] += $result['value'];

                // Increment counters for season stats
                if ( !isset($params['result']['season'][$matchYear]) ) {
                    $params['result']['season'][$matchYear] = $entryTemplate;
                }
                $params['result']['season'][$matchYear][$result['type']]++;
                $params['result']['season'][$matchYear]['PTS'] += $result['value'];

                // Increment counters for team stats
                $teamid = $event->getTeam()->getTeam()->getId();
                if ( !isset($params['result']['team'][$teamid]) ) {
                    $params['result']['team'][$teamid] = ['career' => $entryTemplate, 'season' => []];
                }
                if ( !isset($params['result']['team'][$teamid]['season'][$matchYear]) ) {
                    $params['result']['team'][$teamid]['season'][$matchYear] = $entryTemplate;
                }
                $params['result']['team'][$teamid]['career'][$result['type']]++;
                $params['result']['team'][$teamid]['career']['PTS'] += $result['value'];
                $params['result']['team'][$teamid]['season'][$matchYear][$result['type']]++;
                $params['result']['team'][$teamid]['season'][$matchYear]['PTS'] += $result['value'];

                // Increment counters for opponent stats
                $opponent = $match->getTeam($event->getTeam()->getType() == 'H' ? 'A' : 'H');
                $opponentId = $opponent->getTeam()->getId();
                if ( !isset($params['result']['opponent'][$opponentId]) ) {
                    $params['result']['opponent'][$opponentId] = ['career' => $entryTemplate, 'season' => []];
                }
                if ( !isset($params['result']['opponent'][$opponentId]['season'][$matchYear]) ) {
                    $params['result']['opponent'][$opponentId]['season'][$matchYear] = $entryTemplate;
                }
                $params['result']['opponent'][$opponentId]['career'][$result['type']]++;
                $params['result']['opponent'][$opponentId]['career']['PTS'] += $result['value'];
                $params['result']['opponent'][$opponentId]['season'][$matchYear][$result['type']]++;
                $params['result']['opponent'][$opponentId]['season'][$matchYear]['PTS'] += $result['value'];
            }
        }

        $results = $this->getEventManager()->trigger(__FUNCTION__ . '.post', $this, $params);

        return $params['result'];
    }

    protected function processEvent(MatchTeamEvent $e)
    {
        switch ( $e->getDiscriminator() ) {
            case 'score':
            {
                $e instanceof ScoreEvent;

                return [ 'type' => $e->getType(), 'value' => $e->getPoints() ];
            }
            case 'card':
            {
                $e instanceof CardEvent;

                return [ 'type' => $e->getType() . 'C', 'value' => 0 ];
            }
        }
    }

}
