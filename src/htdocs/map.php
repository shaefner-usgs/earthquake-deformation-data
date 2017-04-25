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

<div class="map">Interactive Map</div>

<ul class="no-style legend">
  <li>
    <svg>
      <circle class="creepmeter" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>Creepmeter</span>
  </li>
  <li>
    <svg>
      <circle class="dilatometer" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>Dilatometer</span>
  </li>
  <li>
    <svg>
      <circle class="dtm" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>DTM Tensor Strainmeter</span>
  </li>
  <li>
    <svg>
      <circle class="gtsm" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>GTSM Tensor Strainmeter</span>
  </li>
  <li>
    <svg>
      <circle class="long-baseline" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>Long Baseline Tiltmeter</span>
  </li>
  <li>
    <svg>
      <circle class="tiltmeter" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>Tiltmeter</span>
  </li>
  <li>
    <svg>
      <circle class="multiple" cx="8" cy="8" r="7"></circle>
    </svg>
    <span>Multiple Instruments</span>
  </li>
</ul>
