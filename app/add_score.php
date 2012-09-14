<div id="wrapper" class="container-fluid">
	<div class="row-fluid">
		<form name='addscore' id='addscore' method='POST' action='' class="form-inline span11">
		<div class="alert error alert-error" id="form-validation-error">
		  <button type="button" class="close" data-dismiss="alert">×</button>
		  <div class="error-message"></div>
		</div>
		<!--<label for="minute" id="minute_label" class="span1">Min.</label>
		<label for="type" id="type_label" class="span4">Type</label>
		<label for="player" id="player_label" class="span4">Player</label>-->


		<div class="row-fluid">
        <div id="minute-wrapper" class="span2">
          <div class="control-group">
            <label for="minute" id="minute_label" class="control-label">Min.</label>
            <div class="controls">
                <?php
					echo "<select id='minute' class='required'>\n";
					for ($k=1;$k<121;$k++) {
					    echo "<option value='$k'>$k</option>\n";
					}
					echo "</select>";
				?>
            </div>
          </div>
        </div>

        <div id="type-wrapper" class="span3">
          <div class="control-group">
            <label for="type" id="type_label" class="control-label">Type</label>
            <div class="controls">
               <select name='type' id='type' class='required'>
					<option value=''></option>
					<option value='1'>Try</option>
					<option value='2'>Conversion</option>
					<option value='3'>Penalty Kick</option>
					<option value='4'>Drop Goal</option>
					<option value='5'>Penalty Try</option>
				</select>
            </div>
          </div>
        </div>

        <div id="player-wrapper" class="span3">
          <div class="control-group">
            <label for="player" id="player_label" class="control-label">Player</label>
            <div class="controls">

				<select name='player' id='player' class ="">
				<?php
					echo "<option value='team$away_id'>--".teamName($away_id)."--</option>";
					foreach ($awayps as $awayp) {
					    echo "<option value='$awayp'>".playerName($awayp)."</option>";
					}
					echo "<option value='team$home_id'>--".teamName($home_id)."--</option>";
					foreach ($homeps as $homep) {
					    echo "<option value='$homep'>".playerName($homep)."</option>";
					}
				?>
				</select>
            </div>
          </div>
	</div>
	<div id="submit-wrapper" class="span2">
          <div class="control-group">
            <label for="type" id="type_label" class="control-label">&nbsp;</label>
            <div class="controls">
               <input type='submit' name='submit' class='button' id='add_score' value='Add Score'>
            </div>
          </div>
        </div>
		<input type='hidden' name='refresh' id='refresh' value='<?php $host = $_SERVER['HTTP_HOST']; echo "http://$host/game_score_events.php?id=$game_id"; ?>'>
		<input type='hidden' name='game_id' id='game_id' value='<?php echo "$game_id"; ?>'>

		</form>
	</div>
</div>
