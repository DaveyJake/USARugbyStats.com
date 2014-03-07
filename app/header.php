<?php
include_once './include.php';
?>

<div class="navbar navbar">
  <div class="inner">
    <div class="container">
      <a class="brand" href="/"><img src="http://usarugby.org/images/usarugby-2x-logo-beta.png"/></a>
        <ul class="nav pull-right">
        <?php
//If the user has a team specific login, provide link to their roster page.
//@TODO determine which link the user should be shown
if (true) {
    echo "<li><a href='/'>Games</a></li>";
} else {
	echo "<li><a href='/'>Competitions</a></li>";
	 } ?>
          <li class = "dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Support <b class = "caret"></b></a>
          <ul class = "dropdown-menu">
          	<li><a id="startButton" href="#">Page Tutorial</a></li>
          	<li><a href="https://usarugby.zendesk.com/forums/22264177-Competition-Management-System" target="_blank">Support Center</a></li>
          	<li><a href="https://usarugby.zendesk.com/anonymous_requests/new" target="_blank">Contact</a></li>
          </ul>
      </li>
          <li><a href="<?php echo $this->url('zfcuser/logout'); ?>">Logout</a></li>

<?php
//only display Admin Options to admins
if ($this->user()->hasRole('super_admin')) {
    ?>
      <li class = "dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin Options <b class = "caret"></b></a>
          <ul class = "dropdown-menu">
              <!-- <li><a href='db_update.php'>Update Player Database</a></li> -->
              <!-- <li><a href='db_update_team.php'>Add Club to Club Database</a></li> -->
              <li><a href='users.php'>User Management</a></li>
              <li><a href='groups_active.php'>Group Management</a></li>
              <li class="divider"></li>
              <li class="dropdown-submenu">
                <a tabindex="-1" href="processqueue">Sync (<?php $qh = new Source\QueueHelper(); echo $qh->Queue()->count();?>)</a>
                <ul class="dropdown-menu">
                  <li><a href='db_update_users_process.php'>Users</a></li>
                  <li><a href='groups_sync.php'>Groups</a></li>
                  <li><a href='group_members_sync.php'>Players in Groups</a></li>
                </ul>
              </li>
          </ul>
      </li>
    <?php
}
?>

      </ul>
      </div>
  </div>
</div>

<?php
if (isset($_SESSION['alert_message'])) {
	$alert = $_SESSION['alert_message'];
    unset($_SESSION['alert_message']);
    // @TODO: support alert types {info, success, error}.
    echo '<div class="alert alert-info">' . $alert . '</div>';
}
?>

