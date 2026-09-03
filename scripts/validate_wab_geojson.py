#!/usr/bin/env python3
"""Validate Cloudlog WABSquares.geojson structure for Leaflet / RFC 7946."""

from __future__ import annotations

import json
import sys
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
DEFAULT_PATH = ROOT / "assets" / "json" / "WABSquares.geojson"

LON_MIN, LON_MAX = -12.0, 3.0
LAT_MIN, LAT_MAX = 48.5, 62.0
REQUIRED_SQUARES = frozenset({"NL57", "NL58", "HW83"})
CI_PREFIXES = frozenset({"WV", "WX", "WR", "WS", "WT", "WU"})


class GeoJsonValidationError(Exception):
    pass


def _is_position(value: object) -> bool:
    if not isinstance(value, list) or len(value) < 2:
        return False
    lon, lat = value[0], value[1]
    if not isinstance(lon, (int, float)) or not isinstance(lat, (int, float)):
        return False
    if not (LON_MIN <= lon <= LON_MAX and LAT_MIN <= lat <= LAT_MAX):
        return False
    return True


def _validate_line_string(coords: object, context: str) -> None:
    if not isinstance(coords, list) or len(coords) < 2:
        raise GeoJsonValidationError(f"{context}: LineString needs >=2 positions")
    for i, pos in enumerate(coords):
        if not _is_position(pos):
            raise GeoJsonValidationError(
                f"{context}: invalid position at index {i}: {pos!r} "
                "(expected [lon, lat]; extra nesting causes Leaflet Invalid LatLng)"
            )


def _validate_multi_line_string(coords: object, context: str) -> None:
    if not isinstance(coords, list) or not coords:
        raise GeoJsonValidationError(f"{context}: MultiLineString coordinates must be a non-empty array")
    first = coords[0]
    # Detect extra nesting: [[[lon,lat],...]] instead of [[lon,lat],...]
    if isinstance(first, list) and first and isinstance(first[0], list) and isinstance(first[0][0], list):
        raise GeoJsonValidationError(
            f"{context}: MultiLineString has extra coordinate nesting "
            f"(found 4+ levels); use [ring] where ring=[[lon,lat],...], not [[ring]]"
        )
    if isinstance(first, list) and first and _is_position(first):
        _validate_line_string(coords, context)
        return
    for i, line in enumerate(coords):
        _validate_line_string(line, f"{context} line {i}")


def _validate_point(coords: object, context: str) -> None:
    if not _is_position(coords):
        raise GeoJsonValidationError(f"{context}: invalid Point coordinates: {coords!r}")


def validate_feature_collection(data: dict) -> dict:
    if data.get("type") != "FeatureCollection":
        raise GeoJsonValidationError("Root type must be FeatureCollection")
    features = data.get("features")
    if not isinstance(features, list):
        raise GeoJsonValidationError("features must be an array")

    point_names: set[str] = set()
    boundary_names: set[str] = set()
    large_boxes = 0
    counts = {"Point": 0, "MultiLineString": 0, "other": 0}

    for idx, feat in enumerate(features):
        if feat.get("type") != "Feature":
            raise GeoJsonValidationError(f"Feature {idx}: type must be Feature")
        geom = feat.get("geometry")
        if not isinstance(geom, dict):
            raise GeoJsonValidationError(f"Feature {idx}: missing geometry")
        gtype = geom.get("type")
        coords = geom.get("coordinates")
        name = feat.get("properties", {}).get("name", f"feature {idx}")
        ctx = f"Feature {idx} ({name})"

        if gtype == "Point":
            counts["Point"] += 1
            _validate_point(coords, ctx)
            point_names.add(name)
        elif gtype == "MultiLineString":
            counts["MultiLineString"] += 1
            _validate_multi_line_string(coords, ctx)
            if name.startswith("Large Square"):
                large_boxes += 1
            if "Boundry Box" in name:
                boundary_names.add(name)
        else:
            counts["other"] += 1
            raise GeoJsonValidationError(f"{ctx}: unsupported geometry type {gtype}")

    missing = REQUIRED_SQUARES - point_names
    if missing:
        raise GeoJsonValidationError(f"Missing required squares: {sorted(missing)}")

    ci = [n for n in point_names if len(n) == 4 and n[:2] in CI_PREFIXES]
    if not ci:
        raise GeoJsonValidationError("No Channel Islands squares found")

    if large_boxes:
        raise GeoJsonValidationError(f"Found {large_boxes} large 100km boundary boxes")

    if counts["Point"] != counts["MultiLineString"]:
        raise GeoJsonValidationError(
            f"Expected equal Point/MultiLineString counts, got {counts['Point']}/{counts['MultiLineString']}"
        )

    return {
        "squares": counts["Point"],
        "features": len(features),
        "large_boxes": large_boxes,
        "ci_squares": len(ci),
        "irish_squares": sum(1 for n in point_names if len(n) == 3),
        "osgb_squares": sum(1 for n in point_names if len(n) == 4 and n[:2] not in CI_PREFIXES),
    }


def validate_file(path: Path = DEFAULT_PATH) -> dict:
    with path.open(encoding="utf-8") as fh:
        data = json.load(fh)
    return validate_feature_collection(data)


def main() -> None:
    path = Path(sys.argv[1]) if len(sys.argv) > 1 else DEFAULT_PATH
    try:
        stats = validate_file(path)
    except (GeoJsonValidationError, json.JSONDecodeError, OSError) as exc:
        print(f"VALIDATION FAILED: {exc}", file=sys.stderr)
        sys.exit(1)
    print(f"VALIDATION OK: {path}")
    for key, val in stats.items():
        print(f"  {key}: {val}")


if __name__ == "__main__":
    main()
