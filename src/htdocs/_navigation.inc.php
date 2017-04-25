<?php

include_once '../conf/config.inc.php'; // app config

$section = $CONFIG['MOUNT_PATH'];
$url = $_SERVER['REQUEST_URI'];

$matchesIndex = false;
if (preg_match("@^{$section}/(index.php)?$@", $url)) {
  $matchesIndex = true;
}

$NAVIGATION =
  navGroup('Crustal Deformation Data',
    navItem("$section/", 'Overview', $matchesIndex) .
    navItem("$section/instruments.php", 'Monitoring Instruments') .
    navItem("$section/map.php", 'Map of Instrument Sites') .
    navItem("$section/plots.php", 'Data Plots') .
    navItem("$section/download.php", 'Download Data') .
    navItem("$section/acquisition.php", 'Data Acquisition and Processing')
  );

print $NAVIGATION;
