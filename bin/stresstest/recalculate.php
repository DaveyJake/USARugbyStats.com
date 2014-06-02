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
use Doctrine\Common\Persistence\ObjectManager;

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
// Recalculate scores
//=====================================================

$repository = $application->getServiceManager()->get('usarugbystats_competition_competition_match_service')->getMatchRepository();
$allMatches = $repository->findAll();
foreach ( $allMatches as $match ) {
    $match->recalculateScore();
    $objectManager->persist($match);
}
$objectManager->flush();
