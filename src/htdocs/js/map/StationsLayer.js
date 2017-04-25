/* global L */
'use strict';


var Util = require('util/Util');

require('leaflet.label');

var _COLORS,
    _DEFAULTS,
    _OVERLAY_DEFAULTS;

_COLORS = {
  'Creepmeter': '#c00',
  'Dilatometer': '#f90',
  'DTM Tensor Strainmeter': '#90f',
  'GTSM Tensor Strainmeter': '#0ff',
  'Long Baseline Tiltmeter': '#c0c',
  'Multiple Instruments': '#666',
  'Tiltmeter': '#0c0'
};
_OVERLAY_DEFAULTS = {
  fillOpacity: 0.4,
  opacity: 0.7,
  radius: 8,
  weight: 1
};
_DEFAULTS = {
  data: {},
  overlayOptions: _OVERLAY_DEFAULTS
};

/**
 * Factory for Stations overlay
 *
 * @param options {Object}
 *     {
 *       data: {String} Geojson data
 *       markerOptions: {Object} L.Marker options
 *     }
 *
 * @return {L.FeatureGroup}
 */
var StationsLayer = function (options) {
  var _initialize,
      _this,

      _overlayOptions,

      _onEachFeature,
      _pointToLayer;


  _initialize = function (options) {
    options = Util.extend({}, _DEFAULTS, options);
    _overlayOptions = Util.extend({}, _OVERLAY_DEFAULTS, options.markerOptions);

    _this = L.geoJson(options.data, {
      onEachFeature: _onEachFeature,
      pointToLayer: _pointToLayer
    });
  };

  /**
   * Leaflet GeoJSON option: called on each created feature layer. Useful for
   * attaching events and popups to features.
   *
   * @param feature {Object}
   * @param layer (L.Layer)
   */
  _onEachFeature = function (feature, layer) {
    var data,
        popup,
        popupTemplate,
        props;

    props = feature.properties;

    data = {
      name: props.name,
      types: props.types
    };
    popupTemplate = '<div class="popup">' +
        '<h2>{name}</h2>' +
        '<p>{types}</p>' +
      '</div>';
    popup = L.Util.template(popupTemplate, data);

    layer.bindLabel(feature.properties.name, {
      pane: 'popupPane'
    }).bindPopup(popup, {
      autoPanPadding: L.point(50, 50),
      minWidth: 256,
    });
  };

  /**
   * Leaflet GeoJSON option: used for creating layers for GeoJSON points
   *
   * @param feature {Object}
   * @param latlng {L.LatLng}
   *
   * @return marker {L.CircleMarker}
   */
  _pointToLayer = function (feature, latlng) {
    var marker,
        type;

    type = feature.properties.types;
    if (/,/.test(type)) {
      type = 'Multiple Instruments';
    }
    _overlayOptions.color = _COLORS[type];
    _overlayOptions.fillColor = _COLORS[type];

    marker = L.circleMarker(latlng, _overlayOptions);

    return marker;
  };


  _initialize(options);
  options = null;
  return _this;
};


L.stationsLayer = StationsLayer;

module.exports = StationsLayer;
