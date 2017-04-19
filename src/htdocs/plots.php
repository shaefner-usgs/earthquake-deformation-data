<?php

if (!isset($TEMPLATE)) {
  // template functions
  include_once 'functions.inc.php';

  // defines the $CONFIG hash of configuration variables
  include_once '../conf/config.inc.php';

  $TITLE = 'Data Plots';
  $HEAD = '
    <link rel="stylesheet" href="/lib/leaflet-0.7.7/leaflet.css" />
    <link rel="stylesheet" href="css/plots.css"/>
  ';
  $FOOT = '
    <script src="/lib/leaflet-0.7.7/leaflet.js"></script>
    <script src="js/plots.js"></script>
  ';

  include 'template.inc.php';
}

?>

<div class="map"></div>
