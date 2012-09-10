<?php
include_once './include_mini.php';

$game_id ?: $request->get('id');

echo "<table class='table'>";

$game_score_events = $db->getGameScoreEvents($game_id);
if ($iframe) {
  echo get_header_string('game_events');
}
foreach ($game_score_events as $game_score_event) {
    echo "<tr><td>{$game_score_event['minute']}'</td>\r";
    echo "<td>".eType($game_score_event['type'])."</td>\r";
    echo "<td>".teamName($game_score_event['team_id'], !$iframe)."</td>\r";
    echo "<td>".playerName($game_score_event['player_id'])."</td>\r";

    if (editCheck() && !$iframe) {
        echo "<td><form style='margin: 0; padding: 0' name='dForm{$game_score_event['id']}' id='dForm{$game_score_event['id']}'>";
        echo "<input name='dScore{$game_score_event['id']}' class='dScore btn btn-danger' id='dScore{$game_score_event['id']}' type='button' value='Delete Score' />";
        echo "<input type='hidden' class='dId' name='event_id' id='event_id' value='{$game_score_event['id']}' />";
        echo "<input type='hidden' name='refresh' id='refresh' value='game_score_events.php?id=$game_id' />";

        echo "</form></td>\r";
    }

    echo "</tr>\r";
}

echo "</table>\r";
