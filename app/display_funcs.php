<?php

use Source\DataSource;

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */

function teamName($id, $link = TRUE)
{
    $query = "SELECT id, short FROM `teams` WHERE id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        if (!empty($link)) {
            $output = "<a href='team.php?id={$row['id']}'>{$row['short']}</a>";
        } else {
            $output = $row['short'];
        }
    }

    return (isset($output) && $output) ? $output : '';
}

function getFullImageUrl($partial_image_url)
{
    include './config.php';
    if (strpos($partial_image_url, "https://") !== false) {
        $team_logo = str_replace($config['auth_domain'], $config['cdn'], $partial_image_url);
    } else {
        $team_logo = $config['cdn'] . $partial_image_url;
    }
    return $team_logo;
}

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */
function teamNameF($id)
{
    $query = "SELECT id, name FROM `teams` WHERE id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {

        $output = "<a href='team.php?id={$row['id']}'>{$row['name']}</a>";
    }

    return $output;
}

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */
function teamNameNL($id)
{
    $query = "SELECT id, name FROM `teams` WHERE id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {

        $output = "{$row['name']}";
    }

    return $output;
}

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */
function compName($id, $link = TRUE)
{
    $query = "SELECT id, name FROM `comps` WHERE id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        if ($link) {
            $output = "<a href='comp.php?id={$row['id']}'>{$row['name']}</a>";
        } else {
            $output = $row['name'];
        }
    }

    return (isset($output) && $output) ? $output : '';
}

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */

function compNameNL($id)
{
    $query = "SELECT id, name FROM `comps` WHERE id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        $output = $row['name'];
    }
    return $output;
}


/**
 *
 *
 * @param unknown $id
 * @return unknown
 */
function playerName($id, $iframe = FALSE, $game_id = NULL)
{
    if (empty($twig)) {
        $loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
        $twig = new Twig_Environment($loader, array());
    }

    if (empty($id)) {
        return '';
    }
    if (empty($db)) {
        $db = new DataSource();
    }
    $player = $db->getPlayer($id);
    if (empty($player)) {
        return '';
    }
    $player['picture_url'] = getFullImageUrl($player['picture_url']);
    $player['full_name'] = $player['firstname'] . ' ' . $player['lastname'];
    $player['entity'] = 'player';
    $player['id'] = $id;
    $player['settings'] = array('iframe' => $iframe);
    $output = $twig->render('player_name.twig', $player);
    return $output;
}

/**
 * Get Player number on game.
 *
 * @param int $game_id
 * @param int $team_id
 * @param int $player_id
 *
 * @return int $output
 */
function playerNumber($game_id, $team_id, $player_id)
{
    if (empty($player_id) || $player_id == 'NIL') {
        return '';
    }
    if (empty($db)) {
        $db = new DataSource();
    }
    $roster = $db->getRoster($game_id, $team_id);
    $player_ids = $roster['player_ids'];
    $numbers = $roster['numbers'];
    $numvals = explode('-', $numbers);
    $cplayers = explode('-', substr($player_ids, 1, (strlen($player_ids) - 2)));
    foreach ($cplayers as $row => $id) {
        if ($id == $player_id) {
            return $numvals[$row + 1];
        }
    }
    return '';
}

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */
function rplayerName($id)
{
    $query = "SELECT id,firstname,lastname FROM `players` WHERE id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {

        $output = "{$row['lastname']}, {$row['firstname']}";
    }

    return $output;
}

/**
 *
 *
 * @param unknown $id
 * @return unknown
 */
function eType($id)
{
    $query = "SELECT name FROM `event_types` WHERE event_id = $id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {

        $output = "{$row['name']}";
    }

    return isset($output) ? $output : '';
}

/**
 *
 *
 * @param unknown $switch
 * @return unknown
 */
function sign($switch)
{
    if ($switch)
        return "Signed";
    else
        return "Not Signed";
}

/**
 *
 *
 * @param unknown $access
 * @return unknown
 */
function accessName($access)
{
    if ($access == 1) {
        $output = 'Administrator';
    } elseif ($access == 2) {
        $output = 'Referee';
    } elseif ($access == 3) {
        $output = 'Team Specific';
    } else {
        $output = 'View Only';
    }

    return $output;
}

function get_header_string($op)
{
    switch ($op) {
        case 'game_events':
            $headers = array(
                'minute',
                'type',
                'team',
                'player'
            );
    }
    $header_string = '<tr class="header-game-events">';
    foreach ($headers as $header) {
        $header_string .= '<th>' . ucfirst($header) . '</th>';
    }
    $header_string .= '</tr>';
    return $header_string;
}

/**
 *
 * @return array of available status names
 */
function game_status_list()
{
    $query = "SELECT * FROM `game_status` ORDER BY status_name ASC";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        $output[$row['id']] = "{$row['status_name']}";
    }

    return isset($output) ? $output : array();
}

/**
 *
 * @param type $game_id
 *
 * @return status(name) of game
 */
function game_status($game_id)
{
    $query = "SELECT s.status_name FROM game_status s, games g WHERE g.id = $game_id AND g.status = s.id";
    $result = mysql_query($query);
    while ($row = mysql_fetch_assoc($result)) {
        $output = "{$row['status_name']}";
    }

    return isset($output) ? $output : '';
}
