<?php
include_once './include_mini.php';


if (empty($game)) {
  $game_id = empty($game_id) ? $request->get('id') : $game_id;
  $game = $db->getGame($game_id);
}

echo compName($game['comp_id'])."<br/>";
echo "Game Number: ".$game['comp_game_id']."<br/>";
echo teamName($game['away_id'])." @ ".teamName($game['home_id'])."<br/>";
echo date('F j, Y', strtotime($game['kickoff']))."<br/>";
echo "Kickoff: ".date('g:i', strtotime($game['kickoff']))."<br/>";
echo "Field: ".$game['field_num']."<br/>";


if (editCheck()) {
    echo "<input type='button' class='edit' id='eShow' name='eShow' value='Edit Game Info' />";
    echo "<input type='hidden' id='game_id' value='$game_id' />";
}
