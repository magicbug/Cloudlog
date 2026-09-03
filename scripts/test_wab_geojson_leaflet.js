#!/usr/bin/env node
/**
 * Parse WABSquares.geojson the way Leaflet does and fail on Invalid LatLng.
 * Usage: node scripts/test_wab_geojson_leaflet.js [path]
 */
'use strict';

const fs = require('fs');
const path = require('path');

const geojsonPath = process.argv[2]
  || path.join(__dirname, '..', 'assets', 'json', 'WABSquares.geojson');

function latLngFromCoords(coords) {
  if (!Array.isArray(coords) || coords.length < 2) {
    throw new Error(`Invalid LatLng object: (${coords})`);
  }
  if (typeof coords[0] !== 'number' || typeof coords[1] !== 'number') {
    throw new Error(`Invalid LatLng object: (${coords})`);
  }
  return { lat: coords[1], lng: coords[0] };
}

function coordsToLatLngs(coords, depth) {
  if (!Array.isArray(coords) || coords.length === 0) {
    throw new Error('Empty coordinates');
  }
  if (typeof coords[0] === 'number') {
    if (depth !== 0) {
      throw new Error(`Expected position at depth ${depth}, got numbers`);
    }
    return [latLngFromCoords(coords)];
  }
  if (Array.isArray(coords[0]) && typeof coords[0][0] === 'number') {
    if (depth !== 1) {
      throw new Error(`Expected LineString at depth ${depth}, got positions`);
    }
    return coords.map((c) => latLngFromCoords(c));
  }
  if (depth === 2) {
    return coords.map((line) => {
      if (!Array.isArray(line) || !line.length || typeof line[0][0] !== 'number') {
        throw new Error(`Invalid MultiLineString line: ${JSON.stringify(line).slice(0, 120)}`);
      }
      return line.map((c) => latLngFromCoords(c));
    });
  }
  throw new Error(
    `Extra coordinate nesting at depth ${depth}: ${JSON.stringify(coords[0]).slice(0, 120)}`
  );
}

function geometryToLayers(geom) {
  switch (geom.type) {
    case 'Point':
      return [{ type: 'point', latlng: latLngFromCoords(geom.coordinates) }];
    case 'MultiLineString':
      return coordsToLatLngs(geom.coordinates, 2).map((line) => ({
        type: 'polyline',
        latlngs: line,
      }));
    default:
      throw new Error(`Unsupported geometry type: ${geom.type}`);
  }
}

function main() {
  const raw = fs.readFileSync(geojsonPath, 'utf8');
  const data = JSON.parse(raw);
  if (data.type !== 'FeatureCollection') {
    throw new Error('Expected FeatureCollection');
  }

  let points = 0;
  let lines = 0;
  data.features.forEach((feature, idx) => {
    try {
      const layers = geometryToLayers(feature.geometry);
      layers.forEach((layer) => {
        if (layer.type === 'point') points += 1;
        if (layer.type === 'polyline') lines += 1;
      });
    } catch (err) {
      const name = feature.properties && feature.properties.name;
      throw new Error(`Feature ${idx} (${name}): ${err.message}`);
    }
  });

  console.log(`Leaflet-style parse OK: ${geojsonPath}`);
  console.log(`  features: ${data.features.length}, points: ${points}, polylines: ${lines}`);
}

try {
  main();
} catch (err) {
  console.error(`LEAFLET PARSE FAILED: ${err.message}`);
  process.exit(1);
}
