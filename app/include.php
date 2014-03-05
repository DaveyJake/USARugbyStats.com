<?php
require_once __DIR__.'/../vendor/autoload.php';
include_once './prepare_request.php';
include_once './db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta http-equiv="Content-Language" content="en" />
  <meta name="google" content="notranslate">

  <!-- Styles -->
  <link href="/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="/assets/css/bootstrap-responsive.css" media="screen" rel="stylesheet" type="text/css">
  <link href="/assets/css/vendor/datepicker.css" rel="stylesheet" type="text/css">
  <link href="/assets/css/vendor/chosen.css" rel="stylesheet" type="text/css">
  <link href="/assets/css/app.css" rel="stylesheet" type="text/css">
  <link href="/assets/css/style.css" rel="stylesheet" type="text/css"> 
  <link href="/assets/css/print.css" media="print" rel="stylesheet" type="text/css">   

  <!-- Fonts -->
  <script type="text/javascript" src="//use.typekit.net/dqm3mdr.js"></script>
  <script type="text/javascript">try{Typekit.load();}catch(e){}</script>

<?php
include_once './display_funcs.php';
include_once './other_funcs.php';
?>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
  <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="/assets/js/vendor/bootstrap-datepicker.js" type="text/javascript"></script>
  <script src="/assets/js/vendor/chosen.jquery.min.js" type="text/javascript"></script>
  <script src="/assets/js/vendor/jquery.dataTables.js" type="text/javascript"></script>
  <script src="/assets/js/vendor/jquery.timeentry.pack.js" type='text/javascript'></script>
  <script src="/assets/js/vendor/jquery.dataTables.yadcf.js" type='text/javascript'></script>
  <script src="/assets/js/vendor/intro.js" type="text/javascript"></script>
  <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>
  <script src="/assets/js/vendor/jquery.geocomplete.js"></script>

<?php if (!empty($iframe)) { ?>
  <script src='https://www.allplayers.com/iframe.js?usar_stats' type='text/javascript'></script>
<?php } ?>
  <script src='jquery_funcs.js'></script>
  <script>
    if (window.name=="ConnectWithOAuth") {window.close();}
  </script>


</head>
<body class="<?php if (empty($iframe)) {echo "app"; }?>">

<?php
if (empty($iframe)) {
  include_once './header.php';
}


