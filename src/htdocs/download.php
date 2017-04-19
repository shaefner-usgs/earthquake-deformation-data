<?php

if (!isset($TEMPLATE)) {
  $TITLE = 'Download Data';
  $HEAD = '';
  $FOOT = '';

  include 'template.inc.php';

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
      'rows' => '',
      'summary' => '
        <p>The data listed below are measures of surface fault slip (scaled in mm)
        as measured by creepmeters. Creepmeters consist of two piers separated by
        about 30 meters and connected by an invar wire. The main fault trace lies
        between the two piers. There is an invar wire oriented roughly a 30 degree
        angle from the local trace of the fault. A displacement transducer (LVDT)
        measures the change in length of the wire (or the change in distance
        between the piers).</p>
        <p>For many of the creepmeters, the measurements are made once every 10
        minutes and sent via satellite telemetry to Menlo Park for analysis. In
        addition, these measurements are periodically measured manually for
        comparison. For other creepmeters, the measurements are made manually.</p>
        <p>Below is a list of creepmeters, from North to South, located on the San
        Andreas, Hayward, and Calaveras Faults. For each site, the available data
        are listed. These data have been hand-edited to remove periods of bad data
        and, where possible, compared with manual measurements. Since these
        represent human generated and cleaned data, these files are only updated
        periodically.</p>
        <p>More information can be found in the USGS Open File Report:
        <a href="http://pubs.er.usgs.gov/publication/ofr89650">Catalog of
        creepmeter measurements in California from 1966 through 1988</a></p>
        <p>For information on 5 sites along the Hayward Fault (cpp, ctm, coz,
        chp, and cfw) see: <a href="http://cires.colorado.edu/~bilham/creepmeter.file/creepmeters.htm">Surface
        Creep on California faults</a></p>
      '
    ],
    'Dilatometer' => [
      'fields' => [
        'HDR', 'strain', 'p_t_removed', 'pressure'
      ],
      'rows' => '',
      'summary' => '
        <p>The data listed below are measures of changes in volumetric strain
        (units of 0.001 part per million) as a function of time for instruments
        in California. Outliers due to telemetry problems, instrument visits,
        and etc have been removed. Offset due to valve resets have been removed.
        Data are provided in two modes; 1) Cleaned strain data and 2) Clean
        strain data adjusted for the Earth Tide and atmospheric pressure changes.
        Pressure data (not scaled to units of pressure) are also provided.</p>
      '
    ],
    'Tensor Strainmeter' => [
      'fields' => [
        'subtype', 'HDR', 'pressure', 'gage_clean', 'gage_adjusted',
        'tensor_clean', 'tensor_adjusted'
      ],
      'notes' => '
        <ol class="notes">
          <li>Ceased working Sept. 1998</li>
          <li>Ceased working; second gage in Dec 2005</li>
          <li>Ceased working Apr. 2002</li>
          <li>Ceased working Dec. 2002</li>
        </ol>
      ',
      'rows' => '',
      'summary' => '
        <p>The data listed below are measures of changes in tensor strain (units
        of 0.001 part per million) as a function of time for instruments in
        California. Outliers due to telemetry problems, instrument visits, and
        etc have been removed. Offset due to valve resets, for the Carnegie, DTM
        instruments, have been removed. We are providing data for two different
        instruments, one is a three component strainmeter made by <a
        href="http://earthquake.usgs.gov/monitoring/deformation/data/instruments.php">Carnegie
        Dept. of Terrestial Magnetism</a> (DTM) and the other is a three or 4
        component strainmeter made by <a href="http://www.gtsmtechnologies.com/index_files/nehrp.htm">GTSM
        Technologies</a>. Since these instruments have at least 3 extensometers,
        these data can be combined into tensor strain based upon analysis using
        the Earth Tides. We provide both the extensometer and the tensor strain
        changes. Data are provided in two modes; 1) Cleaned strain data and 2)
        Clean strain data adjusted for the Earth Tide and atmospheric pressure
        changes. Pressure data (not scaled to units of pressure) are also provided.
        Gauge data for the DTM instruments are not scaled to physical units.</p>
      '
    ]
  ];

  $db = new Db();
  $rsStations = $db->queryStations;

  $prev_type = '';
  $prev_region = '';

  while ($row = $rsStations->fetch(PDO::FETCH_ASSOC)) {
    $region = $row['region'];
    $type = $row['type'];
    $url = [];

    // Create URL links to data
    foreach ($tables[$type]['fields'] as $field) {
      if ($type === 'Creepmeter') {
        if ($row['base_url'] == 'http://escweb.wr.usgs.gov/share/langbein/Archive/HaywardCreep') {
          $url = sprintf('%s/%s_merge.jl.gz', $row['base_url'], strtolower(substr($row['code'], 0, 3)));
        }
        else {
          if ($field === 'HDR') {
            $url[$field] = sprintf('%s/HDR/%s.hdr1', $row['base_url'], strtolower($row['code']));
          }
          else if ($field === 'MIC') {
            $url[$field] = sprintf('%s/%s/%s.m.gz', $row['base_url'], $field, strtolower($row['code']));
          }
          else {
            $url[$field] = sprintf('%s/%s/%s.%s.gz', $row['base_url'], $field, strtolower($row['code']), strtolower($field));
          }
        }
      } else if ($type === 'Dilatometer') {
        $cols = [
          'strain' => 'cl',
          'p_t_removed' => 'pr_td_cl'
        ];

        if ($field == 'HDR') {
          $url[$field] = sprintf('%s/%s/ReadMe.gz', $row['base_url'], strtoupper($row['code']));
        }
        else if ($field == 'pressure') {
          $url[$field] = sprintf('%s/%s/press.jl.gz', $row['base_url'], strtoupper($row['code']));
        }
        else {
          $url[$field] = sprintf('%s/%s/%s_%s_Archive.jl.gz',
            $row['base_url'],
            strtoupper($row['code']),
            strtolower($row['code']),
            $cols[$valid_field]
          );
        }
      } else if ($type === 'Tensor Strainmeter') {
        if ($row['subtype'] == 'DTM') {

          foreach ($validFields[$current_type] as $valid_field) {

            // map column names to file names
            $cols = array(
              'HDR' => 'readme',
              'pressure' => 'press',
              'gage_clean' => 'gauge',
              'gage_adjusted' => 'gauge_adj',
              'tensor_clean' => 'tensor',
              'tensor_adjusted' => 'tensor_adj'
            );
            if ($valid_field != 'subtype') {
              $url[$current_type][$valid_field] = sprintf('%s/%s_%s.tar.gz', $row['base_url'], strtolower($row['code']), $cols[$valid_field]);
            }

          }

        } else if ($row['subtype'] == 'GTSM') {

          foreach ($validFields[$current_type] as $valid_field) {

            // map column names to file names
            $cols = array(
              'HDR' => 'README',
              'gage_clean' => 'gage',
              'tensor_clean' => 'ten',
            );

            if ($valid_field == 'HDR') {
              $url[$current_type][$valid_field] = sprintf('%s/README.pdf', $row['base_url']);
            }
            else if ($valid_field == 'gage_clean' || $valid_field == 'tensor_clean') {
              $url[$current_type][$valid_field] = sprintf('%s/%s_%s.tar.gz', $row['base_url'], strtolower($row['code']), $cols[$valid_field]);
            }

          }
        }
      }
    }

    // Build table
    if ($prev_type != $current_type) {
      if ($prev_type) printf ('</table>%s', $descriptions[$prev_type]['notes']);
      printf ('<h2>%s</h2>%s<table class="tabular">', $row['type'], $descriptions[$current_type]['summary']);
      printf ('<tr><th>%s</th><th>%s</th><th>%s</th><th>%s</th><th>%s</th>', $fields['name'], $fields['code'], $fields['lat'], $fields['lon'], $fields['initial_obs']);
      foreach($validFields[$current_type] as $field) {
        print "<th>$fields[$field]</th>";
      }
      print '</tr>';
    }
    if ($prev_region != $current_region) {
      $num_cols = 5 + count($validFields[$current_type]);
      printf ('<tr><td colspan="%s">%s</td></tr>', $num_cols, $current_region);
    }
    printf ('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td>', $row['name'], $row['code'], $row['lat'], $row['lon'], $row['initial_obs']);
    foreach($validFields[$current_type] as $field) {
      if ($row[$field] == 'Y') {
        printf ('<td><a href="%s">%s</a></td>', $url[$current_type][$field], $row[$field]);
      }
      else {
        printf ('<td>%s</td>', $row[$field]);
      }
    }
    print '</tr>';
    $prev_type = $current_type;
    $prev_region = $current_region;
  }
  printf ('</table>%s', $descriptions[$prev_type]['notes']);
}

?>