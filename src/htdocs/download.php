<?php

include_once '../conf/config.inc.php'; // app config
include_once '../lib/_functions.inc.php'; // app functions
include_once '../lib/classes/Db.class.php'; // db connector, queries

if (!isset($TEMPLATE)) {
  $TITLE = 'Download Data';
  $HEAD = '<link rel="stylesheet" href="css/download.css" />';
  $FOOT = '';
  $NAVIGATION = true;

  include 'template.inc.php';
}

// Human-friendly fieldnames
$lookup = [
  '10MIN' => '10-minute sampling',
  'code' => 'Code',
  'DAY' => '1-day sampling',
  'gage_adjusted' => 'Gage data adjusted',
  'gage_clean' => 'Gage data clean',
  'HDR' => 'Notes',
  'initial_obs' => 'Initial obs.',
  'lat' => 'Latitude',
  'lon' => 'Longitude',
  'MIC' => 'Manual data',
  'name' => 'Name',
  'pressure' => 'Pressure data',
  'p_t_removed' => 'Dilitation adjusted',
  'strain' => 'Dilatation clean',
  'subtype' => 'Instrument type',
  'tensor_adjusted' => 'Tensor data adjusted',
  'tensor_clean' => 'Tensor data clean'
];

$tables = [
  'Creepmeter' => [
    'fields' => [
      'HDR', 'MIC', '10MIN', 'DAY'
    ],
    'summary' => '
      <p>The data listed below are measures of surface fault slip (scaled in mm)
      as measured by creepmeters. Creepmeters consist of two piers separated by
      about 30 meters and connected by an invar wire. The main fault trace lies
      between the two piers. The invar wire oriented roughly a 30 degree angle
      from the local trace of the fault. A displacement transducer (LVDT) measures
      the change in length of the wire (or the change in distance between the
      piers).</p>
      <p>For many of the creepmeters, the measurements are made once every 10
      minutes and are sent via satellite telemetry to Menlo Park for analysis.
      In addition, periodic measurements are made manually for comparison with
      the electronically recorded data. For other creepmeters without telemetry,
      the measurements are made manually.</p>
      <p>Below is a list of creepmeters, from North to South, located on the San
      Andreas, Hayward, and Calaveras Faults. For each site, the available data
      are listed. For sites with telemetry, the data are uploaded to this webpage
      daily. However, for the most recent observations, that is, within the past
      2 weeks, they have not always been reviewed and, if needed, edited.</p>
      <p>Note that at some sites, there exists both 10 minute and 1-day sampled
      data. The 10-minute and 1-day sampled data often do not cover the same
      interval of time. The 1-day data files will include older data.</p>
      <p>The format of these data are year, day-of-year, and slip in mm. If there
      is a fourth column, then ignore that column.</p>
      <h3>How to Cite Creepmeter Data</h3>
      <p>Please reference <a href="https://pubs.usgs.gov/publication/ofr20241011">Open-File Report 2024-1011</a>,
      “Summary of Creepmeter Data from 1980 to 2020—Measurements Spanning the
      Hayward, Calaveras, and San Andreas Faults in Northern and Central
      California”</p>'
  ],
  'Dilatometer' => [
    'fields' => [
      'HDR', 'strain', 'p_t_removed', 'pressure'
    ],
    'notes' => '
      <ul>
        <li>DL ceased working Sept 2016</li>
        <li>VC ceased working Sept 2016</li>
        <li>FR ceased working Sept 2015</li>
        <li>JC ceased working Jan 2015</li>
        <li>RR ceased working Dec. 2014</li>
        <li>CN ceased working Jan 2019</li>
        <li>BD ceased working Aug 2014</li>
      </ul>
    ',
    'summary' => '
      <p>The data listed below are measures of changes in volumetric strain
      (units of 0.001 part per million) as a function of time for instruments
      in California. Outliers due to telemetry problems, instrument visits, and
      etc have been removed. Offsets due to valve resets have been removed. Data
      are provided in two modes; 1) Cleaned strain data and 2) Clean strain data
      adjusted for the Earth Tide and atmospheric pressure changes. Pressure data
      (not scaled to units of pressure) are also provided.</p>
      <p>The format of these data are year, day-of-year, and slip in mm. If there
      is a fourth column, then ignore that column.</p>
      <p>Data are complete through the end of 2019. More data exist, but have not
      been archived.</p>
      <p>The ReadMe file contains the lists of offsets and data removed for analysis.
      In addition, there is a table listing the estimates of the amplitude and phase
      of the O1 and M2 tides calculated as a function of time for the strainmeter.</p>
    '
  ],
  'Tensor Strainmeter' => [
    'fields' => [
      'subtype', 'HDR', 'pressure', 'gage_clean', 'gage_adjusted',
      'tensor_clean', 'tensor_adjusted'
    ],
    'notes' => '
      <ol>
        <li>Ceased working in Sep 1998</li>
        <li>Ceased working; second gage in Dec 2005</li>
        <li>Ceased working in Apr 2002</li>
        <li>Ceased working in Dec 2002</li>
      </ol>
    ',
    'summary' => '
      <p>The data listed below are measures of changes in tensor strain (units
      of 0.001 part per million) as a function of time for instruments in
      California. We are providing data for two different instruments, one is
      a three component strainmeter made by
      <a href="/monitoring/deformation/data/instruments.php">Carnegie Dept. of
      Terrestial Magnetism</a> (DTM) and the other is a three or 4 component
      strainmeter made by
      <a href="https://www.gtsmtechnologies.com/index_files/nehrp.htm">GTSM
      Technologies</a>. Since these instruments have at least 3 extensometers,
      these data can be combined into tensor strain based upon analysis using
      the Earth Tides. We provide both the extensometer and the tensor strain
      changes. Data are provided in two modes; 1) Cleaned strain data and 2)
      Clean strain data adjusted for the Earth Tide and atmospheric pressure
      changes.  Outliers due to telemetry problems, instrument visits, and etc
      have been removed. Offsets due to valve resets, for the Carnegie, DTM
      instruments, have been removed. Pressure data (not scaled to units of
      pressure) are also provided. Gauge data for the DTM instruments are not
      scaled to physical units.</p>
      <p>For the GTSM instruments, the data are complete. These GTSM instruments
      are identified with a 3 letter code. None of these GTSM instruments
      currently work after more than 20 years of continuous operation.</p>
      <p>For the DTM instruments, the data are complete through the end of
      2019. More data exist, but have not been archived.</p>
      <p>The ReadMe file contains the lists of offsets and data removed for
      analysis. In addition, there is a table listing the estimates of the
      amplitude and phase of the O1 and M2 tides calculated as a function of
      time for the strainmeter.</p>
      <p>The format of these data are year, day-of-year, and slip in mm. If
      there is a fourth column, then ignore that column.</p>
    '
  ]
];

$db = new Db();
$rsStations = $db->queryLbStations();

$prevType = '';
$prevRegion = '';
$table = '';

while ($row = $rsStations->fetch(PDO::FETCH_ASSOC)) {
  $baseUrl = $row['base_url'];
  $baseUrl = str_replace('http://', 'https://', $baseUrl);
  $region = $row['region'];
  $type = $row['type'];
  $url = [];

  // Create URL links to data
  foreach ($tables[$type]['fields'] as $field) {
    if ($type === 'Creepmeter') {
      if ($field === 'HDR') {
        $url[$field] = sprintf('%s/HDR/%s.hdr1',
          $baseUrl,
          strtolower($row['code'])
        );
      }
      else if ($field === 'MIC') {
        $url[$field] = sprintf('%s/%s/%s.m.gz',
          $baseUrl,
          $field,
          strtolower($row['code'])
        );
      }
      else {
        $url[$field] = sprintf('%s/%s/%s.%s.gz',
          $baseUrl,
          $field,
          strtolower($row['code']),
          strtolower($field)
        );
      }
    } else if ($type === 'Dilatometer') {
      // map column names to file names
      $cols = [
        'strain' => 'cl',
        'p_t_removed' => 'pr_td_cl'
      ];

      if ($field == 'HDR') {
        $url[$field] = sprintf('%s/%s/ReadMe.gz',
          $baseUrl,
          strtoupper($row['code'])
        );
      }
      else if ($field == 'pressure') {
        $url[$field] = sprintf('%s/%s/press.jl.gz',
          $baseUrl,
          strtoupper($row['code'])
        );
      }
      else {
        $url[$field] = sprintf('%s/%s/%s_%s_Archive.jl.gz',
          $baseUrl,
          strtoupper($row['code']),
          strtolower($row['code']),
          $cols[$field]
        );
      }
    } else if ($type === 'Tensor Strainmeter') {
      if ($row['subtype'] === 'DTM') {
        // map column names to file names
        $cols = array(
          'HDR' => 'readme',
          'pressure' => 'press',
          'gage_clean' => 'gauge',
          'gage_adjusted' => 'gauge_adj',
          'tensor_clean' => 'tensor',
          'tensor_adjusted' => 'tensor_adj'
        );

        if ($field !== 'subtype') {
          $url[$field] = sprintf('%s/%s_%s.tar.gz',
            $baseUrl,
            strtolower($row['code']),
            $cols[$field]
          );
        }
      } else if ($row['subtype'] === 'GTSM') {
        // map column names to file names
        $cols = array(
          'HDR' => 'README',
          'gage_clean' => 'gage',
          'tensor_clean' => 'ten',
        );

        if ($field === 'HDR') {
          $url[$field] = sprintf('%s/README.pdf', $baseUrl);
        }
        else if ($field === 'gage_clean' || $field === 'tensor_clean') {
          $url[$field] = sprintf('%s/%s_%s.tar.gz',
            $baseUrl,
            strtolower($row['code']),
            $cols[$field]
          );
        }
      }
    }
  }

  // Build table
  if ($prevType !== $type) {
    if ($prevType) {
      $table .= sprintf ('</table>%s', $tables[$prevType]['notes']);
    }
    $table .= sprintf ('<h2>%s</h2>%s<table>',
      $type,
      $tables[$type]['summary']
    );
    // Common fields for all tables
    $table .= sprintf ('<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th>',
      $lookup['name'],
      $lookup['code'],
      $lookup['lat'],
      $lookup['lon'],
      $lookup['initial_obs']
    );
    // Custom fields for specific tables
    foreach($tables[$type]['fields'] as $field) {
      $table .= "<th>$lookup[$field]</th>";
    }
    $table .= '</tr>';
  }
  if ($prevRegion !== $region) {
    $numCols = 5 + count($tables[$type]['fields']); // common + custom fields
    $table .= sprintf ('<tr><td colspan="%s"><h3>%s</h3></td></tr>',
      $numCols,
      $region
    );
  }
  $table .= sprintf ('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>',
    $row['name'],
    $row['code'],
    $row['lat'],
    $row['lon'],
    $row['initial_obs']
  );
  foreach($tables[$type]['fields'] as $field) {
    if ($row[$field] === 'Y') {
      $table .= sprintf ('<td class="data"><a href="%s">
        <i class="material-icons">&#xE2C4;</i></a></td>', $url[$field]);
    }
    else {
      $table .= '<td class="no-data" title="No data">
        <i class="material-icons">&#xE14B;</i></td>';
    }
  }
  $table .= '</tr>';

  $prevType = $type;
  $prevRegion = $region;
}

// Close final table tag / add notes
$table .= sprintf ('</table>%s', $tables[$prevType]['notes']);

print $table;
