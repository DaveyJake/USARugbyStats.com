<?php
require_once __DIR__.'/../vendor/autoload.php';
include_once './prepare_request.php';
include_once './db.php';
include_once './display_funcs.php';
include_once './other_funcs.php';
if (!empty($iframe)) {
  echo '<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/css/bootstrap-combined.min.css" rel="stylesheet" type="text/css">';
  echo '<link href="/assets/css/app.css" rel="stylesheet" type="text/css">';
}
