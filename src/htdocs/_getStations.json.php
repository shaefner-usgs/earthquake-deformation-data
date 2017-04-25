<?php

include_once '../conf/config.inc.php'; // app config
include_once '../lib/_functions.inc.php'; // app functions
include_once '../lib/classes/Db.class.php'; // db connector, queries

$now = date(DATE_RFC2822);

$db = new Db;

// Db query result: all stations
$rsStations = $db->queryStations();

// Initialize array template for json feed
$output = [
  'count' => $rsStations->rowCount(),
  'generated' => $now,
  'features' => [],
  'network' => $network,
  'type' => 'FeatureCollection'
];

// Group stations with multiple instrument types together in a single station
while ($row = $rsStations->fetch(PDO::FETCH_ASSOC)) {
  $name = $row['site_name'];

  // either initialize or add to existing $types array for each site_name
  if ($name === $prevName) {
    $types[] = $row['instrument_type'];
  } else {
    $types = [$row['instrument_type']];
  }

  $stations[$name] = [
    'code' => $row['site_abr'],
    'id' => $row['id'],
    'lat' => $row['lat'],
    'lon' => $row['lon'],
    'types' => $types
  ];

  $prevName = $name;
}

// Store results from db into features array
foreach ($stations as $name => $station) {
  $feature = [
    'id' => intval($station['id']),
    'geometry' => [
      'coordinates' => [
        floatval($station['lon']),
        floatval($station['lat'])
      ],
      'type' => 'Point'
    ],
    'properties' => [
      'code' => strtoupper($station['code']),
      'name' => ucwords(strtolower($name)),
      'types' => implode(', ', $station['types'])
    ],
    'type' => 'Feature'
  ];

  array_push($output['features'], $feature);
}

// Send json stream to browser
showJson($output);
