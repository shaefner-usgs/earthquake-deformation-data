<?php

include_once '../conf/config.inc.php'; // app config

if (!isset($TEMPLATE)) {
  $TITLE = 'Map of Instrument Sites';
  $HEAD = '
    <link rel="stylesheet" href="/lib/leaflet-0.7.7/leaflet.css" />
    <link rel="stylesheet" href="css/map.css" />
  ';
  $FOOT = '
    <script>
      var MOUNT_PATH = "' . $CONFIG['MOUNT_PATH'] . '";
    </script>
    <script src="/lib/leaflet-0.7.7/leaflet.js"></script>
    <script src="js/map.js"></script>
  ';
  $NAVIGATION = true;

  include 'template.inc.php';
}

?>

<div class="map"></div>
