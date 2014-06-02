<?php
use Zend\Mvc\Application;
use UsaRugbyStats\Competition\Entity\Competition\Match;
use Doctrine\Common\Collections\ArrayCollection;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamPlayer;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeam;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\ScoreEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\CardEvent;
use UsaRugbyStats\Competition\Entity\Competition\Match\MatchTeamEvent\SubEvent;
use UsaRugbyStats\Competition\Entity\Competition\Division;

ini_set('display_errors', true);

chdir(__DIR__ . '/../../');
include_once './vendor/autoload.php';

//=====================================================
// Bootstrap the ZF application
//=====================================================

$config = include 'config/application.config.php';

// We don't want an audit trail of what happens here
if ( ( $offendingModule = array_search('SoliantEntityAudit', $config['modules'])) !== false ) {
    unset($config['modules'][$offendingModule]);
}

$application = Application::init($config);
$objectManager = $application->getServiceManager()->get('zfcuser_doctrine_em');

//=====================================================
// Run Alice to fill up the database with goodies
//=====================================================

$loader = new \Nelmio\Alice\Loader\Yaml('en_US', [
    new CustomAliceProvider()
], mt_rand());
$loader->setLogger(function($message) {
	echo $message . "\n";
});

$objects = $loader->load(__DIR__.'/fixtures.yml');

$persister = new \Nelmio\Alice\ORM\Doctrine($objectManager);
$persister->persist($objects);


class CustomAliceProvider
{
    public function addRandomTeamsToDivision(Division $division)
    {
        global $loader;

        $competition = $division->getCompetition();

        // Select 10-20 teams for this division that aren't already in another division of the same competition
        $references = $loader->getReferences();
        $referenceKeys = array_keys($references);
        $teams = array_filter($referenceKeys, function($k) use ($competition, $references) {
            return preg_match('{^team[0-9]+$}is', $k) && ! $competition->hasTeam($references[$k]);
        });
        if (count($teams) < 10) {
            return new ArrayCollection();
        }
        $selectedTeams = array_rand($teams, mt_rand(10,min(20,count($teams))));
        if ( !is_array($selectedTeams) ) {
            return new ArrayCollection();
        }

        foreach ( $selectedTeams as $reference ) {
            if ( ! isset($teams[$reference]) || ! preg_match('{^team[0-9]+$}is', $teams[$reference]) ) {
                continue;
            }
            $division->addTeam($references[$teams[$reference]]);
        }
        return new ArrayCollection();
    }

    //@TODO this goes WAY beyond just random teams, should probably break it down
    public function addRandomTeamsToMatch(Match $match)
    {
        $comp = $match->getCompetition();
        $teamMemberships = $comp->getTeamMemberships();
        if (count($teamMemberships) > 2 )
        {
            $selectedKeys = array_rand($teamMemberships->getKeys(), 2);

            $homeTeam = $match->getHomeTeam();
            $homeTeam->setTeam($comp->getTeamMemberships()->get($selectedKeys[0])->getTeam());

            $awayTeam = $match->getAwayTeam();
            $awayTeam->setTeam($comp->getTeamMemberships()->get($selectedKeys[1])->getTeam());

            $this->generateRandomRosterForMatch($match);
            $this->generateRandomEventsForMatch($match);
        }
        return new ArrayCollection();
    }

    public function generateRandomRosterForMatch(Match $match)
    {
        global $loader;

        $positions = [
            'LHP' => 'Loose-Head Prop (P)',
            'H' => 'Hooker (H)',
            'THP' => 'Tight-Head Prop (P)',
            'L1' => 'Lock 1 (L)',
            'L2' => 'Lock 2 (L)',
            'OSF' => 'Open Side Flanker (F)',
            'BSF' => 'Blind Side Flanker (F)',
            'N8' => 'Number 8 (N8)',
            'SH' => 'Scrum Half (SH)',
            'FH' => 'Fly Half (FH)',
            'IC' => 'Inside Center (C)',
            'OC' => 'Outside Center (C)',
            'W1' => 'Wing 1 (W)',
            'W2' => 'Wing 2 (W)',
            'FB' => 'Fullback (FB)',
            'R1' => 'Reserve 1 (R)',
            'R2' => 'Reserve 2 (R)',
            'R3' => 'Reserve 3 (R)',
            'R4' => 'Reserve 4 (R)',
            'R5' => 'Reserve 5 (R)',
            'R6' => 'Reserve 6 (R)',
            'R7' => 'Reserve 7 (R)',
            'R8' => 'Reserve 8 (R)',
        ];
        $positionKeys = array_keys($positions);

        $references = $loader->getReferences();
        $referenceKeys = array_keys($references);
        $accounts = array_filter($referenceKeys, function($k) {
            return preg_match('{^account[0-9]+$}is', $k);
        });
        $players = array_rand($accounts, 46);

        $homePlayers = array_slice($players, 0, 23);
        $awayPlayers = array_slice($players, 23, 23);

        foreach ( $positionKeys as $index => $positionKey ) {
            $posHome = new MatchTeamPlayer();
            $posHome->setPlayer($references[$referenceKeys[$homePlayers[$index]]]);
            $posHome->setPosition($positionKey);
            $posHome->setNumber($index+1);
            $match->getHomeTeam()->addPlayer($posHome);

            $posAway = new MatchTeamPlayer();
            $posAway->setPlayer($references[$referenceKeys[$awayPlayers[$index]]]);
            $posAway->setPosition($positionKey);
            $posAway->setNumber($index+1);
            $match->getAwayTeam()->addPlayer($posAway);
        }
    }

    public function generateRandomEventsForMatch(Match $match)
    {
        $coll = new ArrayCollection();
        for ( $i = 0; $i < mt_rand(15,50); $i++ ) {
            $obj = $this->generateRandomEventForMatch($match);
            if ( is_null($obj) ) {
                continue;
            }
            $coll->add($obj);
        }

        $match->addEvents($coll);
    }

    public function generateRandomEventForMatch(Match $match)
    {
        global $loader;

        $team = $match->getTeam(mt_rand(0,1) == 1 ? 'H' : 'A');

        $rand = mt_rand(0,100);
        if ( $rand < 50 ) {
            $obj = $this->addRandomScoreEventToTeam($team);
        } elseif ( $rand < 85 ) {
            $obj = $this->addRandomCardEventToTeam($team);
        } else {
            $obj = $this->addRandomSubEventToTeam($team);
        }

        return $obj;
    }

    public function addRandomScoreEventToTeam(MatchTeam $team)
    {
        $types = ['CV','DG','PK','PT','TR'];

        $obj = new ScoreEvent();
        $obj->setTeam($team);
        $obj->setMinute(mt_rand(1,80));
        $obj->setType($types[array_rand($types, 1)]);

        $players = $team->getPlayers();
        if (count($players) == 0 ) {
            return;
        }
        $obj->setPlayer($players[array_rand($players->getKeys(), 1)]);

        return $obj;
    }

    public function addRandomCardEventToTeam(MatchTeam $team)
    {
        $obj = new CardEvent();
        $obj->setTeam($team);
        $obj->setMinute(mt_rand(1,80));
        $obj->setType(mt_rand(0,1) == 1 ? 'R' : 'Y');

        $players = $team->getPlayers();
        if (count($players) == 0 ) {
            return;
        }
        $obj->setPlayer($players[array_rand($players->getKeys(), 1)]);

        return $obj;
    }

    public function addRandomSubEventToTeam(MatchTeam $team)
    {
        $types = ['BL','IJ', 'FRC', 'TC'];

        $obj = new SubEvent();
        $obj->setTeam($team);
        $obj->setMinute(mt_rand(1,80));
        $obj->setType($types[array_rand($types, 1)]);

        $players = $team->getPlayers();
        if (count($players) == 0 ) {
            return;
        }
        $selected = array_rand($players->getKeys(), 2);
        $obj->setPlayerOn($players[$selected[0]]);
        $obj->setPlayerOff($players[$selected[1]]);

        return $obj;
    }
}