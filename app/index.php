<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
use Source\DataSource;
use Source\QueueHelper;
use Source\APSource;
use AllPlayers\Client as APClient;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();
// register the session extension
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

/**
 * Dumb helper to just run pending queue tasks.
 *
 * @see QueueRunCommand
 */
$app->get('/processqueue', function () use ($app) {
    $qh = new QueueHelper();
    if ($qh->Queue()->count() > 0) {
        $qh->RunQueue();
    } else {
        // Nothing to do.
    }

    return $app->redirect('/');
});

$app->get('/group_above', function (Request $request) use ($app) {
    Resque::enqueue('get_group_above', 'GetGroupAbove');
    return new Response('Group above enqueued.', 200);
});

/**
 * Post callback for updating and synching groups and players from allplayers.
 */
$app->post('/sync', function (Request $request) use ($app) {
    $data = $request->request->get('event_data');
    include_once './db.php';
    $db = new Source\DataSource();
    $user = $db->getUser($data['uuid']);
    $team_info = array(
        'hidden' => 0,
        'user_create' => ($user) ? $user['login'] : 'usarugbyallplayers@gmail.com',
        'uuid' => $data['group']['uuid'],
        'name' => $data['group']['name'],
        'short' => $data['group']['name'],
        'logo_url' => $data['group']['logo'],
        'description' => $data['group']['description'],
        'type' => strtolower($data['group']['group_type']),
        'group_above_uuid' => $data['group']['group_above']
    );

    switch ($data['webhook_type']) {
        case 'user_creates_group':
            $db->addupdateTeam($team_info);
            return new Response('Group ' . $team_info['name'] . ' deleted.', 201);
            break;

        case 'user_updates_group':
            $team = $db->getTeam($team_info['uuid']);
            $team_info['id'] = $team['id'];
            unset($team_info['group_above_uuid']);
            $db->addupdateTeam($team_info);
            return new Response('Group ' . $team_info['name'] . ' updated.', 201);
            break;

        case 'user_deletes_group':
            $db->removeTeam($team_info['uuid']);
            return new Response('Group ' . $team_info['name'] . ' deleted.', 201);
            break;

        case 'user_adds_role':
            $now = date('Y-m-d H:i:s');
            $player_info = array(
                'user_create' => ($user) ? $user['login'] : 'usarugbyallplayers@gmail.com',
                'last_update' => $now,
                'uuid' => $data['member']['uuid'],
                'team_uuid' => $team_info['uuid'],
                'firstname' => mysql_real_escape_string($data['member']['first_name']),
                'lastname' => mysql_real_escape_string($data['member']['last_name']),
                'picture_url' => '/sites/default/files/imagecache/profile_mini/sites/all/themes/allplayers960/images/default_profile.png',
                'roles' => serialize(array($data['member']['role_name']))
            );

            $player = $db->getPlayer($player_info['uuid'], $team_info['uuid']);
            if ($player) {
                $player_info['id'] = $player['id'];
                $roles = unserialize($player['roles']);
                $roles[] = $data['member']['role_name'];
                $player_info['roles'] = serialize($roles);
            }

            $db->addUpdatePlayer($player_info);
            return new Response('Role added to ' . $player_info['uuid'] . ' in ' . $team_info['name'], 200);
            break;
        case 'user_removes_role':
            $now = date('Y-m-d H:i:s');
            $player_info = array(
                'user_create' => ($user) ? $user['login'] : 'usarugbyallplayers@gmail.com',
                'last_update' => $now,
                'uuid' => $data['member']['uuid'],
                'team_uuid' => $team_info['uuid'],
                'firstname' => mysql_real_escape_string($data['member']['first_name']),
                'lastname' => mysql_real_escape_string($data['member']['last_name']),
                'picture_url' => '/sites/default/files/imagecache/profile_mini/sites/all/themes/allplayers960/images/default_profile.png',
                'roles' => $data['member']['role_name']
            );

            $player = $db->getPlayer($player_info['uuid'], $team_info['uuid']);
            if ($player) {
                $player_info['id'] = $player['id'];
                $roles = unserialize($player['roles']);
                $key = array_search($player_info['roles'], $roles);
                if ($key !== FALSE) {
                    unset($roles[$key]);
                }
                if (sizeof($roles) == 0) {
                    $db->removePlayer($player['id']);
                    return new Response('Player ' . $player_info['uuid'] . ' removed from ' . $team_info['name'], 200);
                    break;
                }
                $player_info['roles'] = serialize($roles);
            }

            $db->addUpdatePlayer($player_info);
            return new Response('Role removed from ' . $player_info['uuid'] . ' in ' . $team_info['name'], 200);
            break;
    }
});

/**
 *  Return html representation of standings based on comp or group.
 */
$app->get('/standings', function (Request $request) use ($app) {
    include_once './db.php';
    if ($app['request']->get('iframe')) {
        echo "<script src='https://www.allplayers.com/iframe.js?usar_stats' type='text/javascript'></script>";
        echo '<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css" rel="stylesheet" type="text/css">';
    }
    $comp_id = $app['request']->get('comp_id');
    if (empty($comp_id)) {
        // Team UUID is required in order to get the standings of that group (league or division).
        $group_uuid = $app['request']->get('group_uuid');
        $comp_id = $db->getCompetitionId($group_uuid);
        if (empty($comp_id)) {
            return '';
        }
    }
    $doc = get_standings($comp_id, $db, $app['session']->get('domain'));

    $doc->saveXML();
    $xslDoc = new DOMDocument();
    $xslDoc->load("views/sportsml2html.xsl");
    $proc = new XSLTProcessor();
    $proc->importStylesheet($xslDoc);
    return $proc->transformToXML($doc);
});

/**
 *  Return html representation of standings based on comp or group.
 */
$app->get('/standings.xml', function () use ($app) {
    include_once './db.php';
    header('Content-type: application/xml');
    $comp_id = $app['request']->get('comp_id');
    if (empty($comp_id)) {
        // Team UUID is required in order to get the standings of that group (league or division).
        $group_uuid = $app['request']->get('group_uuid');
        $comp_id = $db->getCompetitionId($group_uuid);
    }
    $doc = get_standings($comp_id, $db, $app['session']->get('domain'));

    $doc->formatOutput = true;
    return $doc->saveXML();
});

$app->get('/player', function () use ($app) {
    $iframe = $app['request']->get('iframe');
    $player_id = $app['request']->get('player_id');
    $comp_id = $app['request']->get('comp_id');
    include_once './include.php';
    $render = get_player_stat_data($player_id, $comp_id, $iframe);
    return $app['twig']->render('player.twig', $render);
});

$app->run();

function get_player_stat_data($player_id, $comp_id = NULL, $iframe = FALSE)
{
    $db = new DataSource();
    $render = array();
    $player_data = $db->getPlayer($player_id);
    $player_team = $db->getTeam($player_data['team_uuid']);
    $player_data['picture_url'] = getFullImageUrl($player_data['picture_url']);
    $player_data['full_name'] = $player_data['firstname'] . ' ' . $player_data['lastname'];
    $player_team['team_name'] = teamName($player_team['id'], empty($iframe));
    $game_events = array();
    if (empty($comp_id)) {
        // Get all game events for player.
        $game_events = $db->getPlayerGameEvents($player_id);
    }
    if (empty($game_events)) {
        // Try just getting Player's team games where he's on the roster.
        $game_events = $db->getGamesWithPlayerOnRoster($player_id);
        foreach ($game_events as $key => $prep_game_event) {
            $game_events[$key]['value'] = 0;
            $game_events[$key]['type'] = 0;
        }
    }

    if (!empty($game_events)) {
        $game_data_keys = array(
            'Date',
            'Comp',
            'PTS',
            'Tries',
            'Cons',
            'Pens',
            'DGs',
            'YC',
            'RC',
        );

        $stat_data = array();
        foreach ($game_events as $game_event) {
            $game_id = $game_event['game_id'];
            if (empty($stat_data[$game_id])) {
                $game_data = array();
                foreach ($game_data_keys as $game_data_key) {
                    $game_data[strtolower($game_data_key)] = 0;
                }
            } else {
                $game_data = $stat_data[$game_id];
            }

            if ($game_event['home_id'] = $player_team['id']) {
                $competing_team = $game_event['away_id'];
            } else {
                $competing_team = $game_event['home_id'];
            }

            $kickoff = new DateTime($game_event['kickoff']);
            $kickoff_year = new DateTime($game_event['kickoff']);
            $kickoff_year->add(new DateInterval('P365D'));
            $game_data['date'] = $kickoff->format('Y') . ' - ' . $kickoff_year->format('Y');
            $game_data['comp'] = teamName($competing_team, empty($iframe));
            $game_data['pts'] = empty($game_data['pts']) ? $game_event['value'] : $game_data['pts'] + $game_event['value'];

            switch ($game_event['type']) {
                // Try - Tries. @todo - do penalty tries count as tries?
                case 1:
                    $game_data['tries'] = empty($game_data['tries']) ? 1 : $game_data['tries'] + 1;
                    break;

                // Conversion - Cons.
                case 2:
                    $game_data['cons'] = empty($game_data['cons']) ? 1 : $game_data['cons'] + 1;
                    break;

                // Penalty Kick - Pens.
                case 3:
                    $game_data['pens'] = empty($game_data['pens']) ? 1 : $game_data['pens'] + 1;
                    break;

                // Drop Goals - DGs.
                case 4:
                    $game_data['dgs'] = empty($game_data['dgs']) ? 1 : $game_data['dgs'] + 1;
                    break;

                // Yellow Card - YC.
                case 21:
                    $game_data['yc'] = empty($game_data['yc']) ? 1 : $game_data['yc'] + 1;
                    break;

                // Red Card - RC.
                case 22:
                    $game_data['rc'] = empty($game_data['rc']) ? 1 : $game_data['rc'] + 1;
                    break;
            }
            $stat_data[$game_id] = $game_data;
        }
    }

    $render = array(
        'player_data' => $player_data,
        'player_team' => $player_team,
        'stat_data' => empty($stat_data) ? array() : $stat_data,
    );
    return $render;
}

function get_standings($comp_id, $db, $domain)
{
    $doc = new DomDocument('1.0');
    $comp_data = $db->getCompetition($comp_id);
    $comp_type = $comp_data['type'] == 1 ? '15s' : '7s';
    $root = $doc->appendChild($doc->createElement('sports-content'));
    $root->setAttribute('xmlns', "http://iptc.org/std/SportsML/2008-04-01/");
    $root->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");

    $teams = $db->getCompetitionTeams($comp_id);
    $team_records = array();
    $points = array();
    $games_played = array();
    $divisions = array();
    foreach ($teams as $uuid => $team) {
        $record = $db->getTeamRecordInCompetition($team['id'], $comp_id);
        $team_records[$uuid] = $record;
        $points[$uuid] = $record['points'];
        $games_played[$uuid] = $record['total_games'];
        $point_diff[$uuid] = $record['favor'] - $record['against'];
        $divisions[$uuid] = ($team['division_id'] != 0) ? $team['division_id'] : NULL;
    }

    if (empty($divisions) || in_array(NULL, $divisions)) {
        $standing = $root->appendChild($doc->createElement('standing'));
        // Sort by ranking.
        array_multisort($points, SORT_DESC, $point_diff, SORT_DESC, $games_played, SORT_ASC, $teams);
    } else {
        // Sort by divisions then by ranking.
        array_multisort($divisions, SORT_DESC, $points, SORT_DESC, $point_diff, SORT_DESC, $games_played, SORT_ASC, $teams);
        $divisions = array();
    }

    $rank = 1;
    foreach ($teams as $uuid => $team) {
        $record = $team_records[$uuid];
        if (isset($team['division_id']) && $team['division_id'] != 0 && !array_key_exists($team['division_id'], $divisions)) {
            $divisions[$team['division_id']] = $db->getDivision($team['division_id']);
            $standing = $root->appendChild($doc->createElement('standing'));
            $standing->setAttribute('content-label', $divisions[$team['division_id']]['name']);
        }
        $team_node = $standing->appendChild($doc->createElement('team'));

        $team_metadata = $team_node->appendChild($doc->createElement('team-metadata'));
        $name = $team_metadata->appendChild($doc->createElement('name'));
        $name->setAttribute('full', $team['name']);
        if (strpos($partial_image_url, "https://") !== false) {
            $team_logo = str_replace($config['auth_domain'], $config['cdn'], $partial_image_url);
        } else {
            $team_logo = $config['cdn'] . $partial_image_url;
        }
        $name->setAttribute('logo', $team_logo);

        $team_stats = $team_node->appendChild($doc->createElement('team-stats'));
        $team_stats->setAttribute('events-played', $record['total_games']);
        $team_stats->setAttribute('standing-points', $record['points']);
        $ranking = $team_stats->appendChild($doc->createElement('rank'));
        $ranking->setAttribute('value', (string)$rank);
        $rank++;

        $totals = $team_stats->appendChild($doc->createElement('outcome-totals'));
        $totals->setAttribute('wins', $record['total_wins']);
        $totals->setAttribute('losses', $record['total_losses']);
        $totals->setAttribute('ties', $record['total_ties']);
        $totals->setAttribute('winning-percentage', $record['percent']);
        $totals->setAttribute('points-scored-for', $record['favor']);
        $totals->setAttribute('points-scored-against', $record['against']);
        $totals->setAttribute('points-differential', $record['favor'] - $record['against']);
        if ($comp_type == '15s') {
            $totals->setAttribute('try-bonus', $record['try_bonus_total']);
            $totals->setAttribute('loss-bonus', $record['loss_bonus_total']);
        }
        $totals->setAttribute('forfeits', $record['forfeits']);
    }

    return $doc;
}
