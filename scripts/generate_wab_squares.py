#!/usr/bin/env python3
"""Regenerate Cloudlog WABSquares.geojson with land-only, CRS-correct 10 km squares.

Uses:
- osbng for GB Ordnance Survey National Grid (EPSG:27700)
- Irish Grid (EPSG:29902) and Channel Islands UTM zone 30N (EPSG:32630)
  following the grid logic in kwirk/pota-gb-map
- OSM land-polygons (WGS84) for land intersection (includes Scottish islands)

Output schema matches the existing Cloudlog frontend: each square is a Point
label plus a MultiLineString boundary box; large 100 km boundary boxes are omitted.
"""

from __future__ import annotations

import json
import sys
from pathlib import Path

import geopandas as gpd
from osbng.grids import bng_grid_10km
from pyproj import Transformer
from shapely.geometry import Point, Polygon, mapping, shape
from shapely.ops import unary_union

ROOT = Path(__file__).resolve().parents[1]
OUTPUT = ROOT / "assets" / "json" / "WABSquares.geojson"
LAND_SHP = Path("/tmp/osm/land/land-polygons-split-4326/land_polygons.shp")

# WGS84 bbox covering GB, Ireland, and Channel Islands (with small margin).
LAND_BBOX = (-11.5, 49.0, 2.5, 61.5)

OS_GRID_PREFIXES = [
    ["SV", "SW", "SX", "SY", "SZ", "TV", "TW"],
    ["SQ", "SR", "SS", "ST", "SU", "TQ", "TR"],
    ["SL", "SM", "SN", "SO", "SP", "TL", "TM"],
    ["SF", "SG", "SH", "SJ", "SK", "TF", "TG"],
    ["SA", "SB", "SC", "SD", "SE", "TA", "TB"],
    ["NV", "NW", "NX", "NY", "NZ", "OV", "OW"],
    ["NQ", "NR", "NS", "NT", "NU", "OQ", "OR"],
    ["NL", "NM", "NN", "NO", "NP", "OL", "OM"],
    ["NF", "NG", "NH", "NJ", "NK", "OF", "OG"],
    ["NA", "NB", "NC", "ND", "NE", "OA", "OB"],
    ["HV", "HW", "HX", "HY", "HZ", "JV", "JW"],
    ["HQ", "HR", "HS", "HT", "HU", "JQ", "JR"],
    ["HL", "HM", "HN", "HO", "HP", "JL", "JM"],
]

TO_WGS84 = {
    27700: Transformer.from_crs(27700, 4326, always_xy=True),
    29902: Transformer.from_crs(29902, 4326, always_xy=True),
    32630: Transformer.from_crs(32630, 4326, always_xy=True),
}


def osgb_prefix(easting: float, northing: float) -> str | None:
    row = int(northing // 100_000)
    col = int(easting // 100_000)
    if 0 <= row < len(OS_GRID_PREFIXES) and 0 <= col < len(OS_GRID_PREFIXES[row]):
        return OS_GRID_PREFIXES[row][col]
    return None


def irish_prefix(easting: float, northing: float) -> str | None:
    alphabet = "ABCDEFGHJKLMNOPQRSTUVWXYZ"
    idx = 20 - int(northing // 100_000) * 5 + int(easting // 100_000)
    if 0 <= idx < len(alphabet):
        letter = alphabet[idx]
        if letter in {"C", "D", "G", "H", "J"}:
            return letter
    return None


def mgrs_prefix(easting: float, northing: float) -> str | None:
    e_alphabet = "STUVWXYZ"
    n_alphabet = "ABCDEFGHJKLMNPQRSTUV"
    e_idx = int(easting // 100_000) - 1
    n_idx = (int(northing // 100_000) + 5) % 20
    if 0 <= e_idx < len(e_alphabet) and 0 <= n_idx < len(n_alphabet):
        return e_alphabet[e_idx] + n_alphabet[n_idx]
    return None


def iter_grid_squares(
    crs: int,
    prefix_func,
    extent: tuple[float, float, float, float],
    step: int = 10_000,
) -> list[tuple[str, Polygon]]:
    """Generate 10 km grid squares within a projected extent."""
    squares: dict[str, Polygon] = {}
    min_e, min_n, max_e, max_n = extent
    e = (min_e // step) * step
    while e <= max_e:
        n = (min_n // step) * step
        while n <= max_n:
            prefix = prefix_func(e, n)
            if prefix:
                east_digit = int((e % 100_000) // step)
                north_digit = int((n % 100_000) // step)
                name = f"{prefix}{east_digit}{north_digit}"
                poly = Polygon([(e, n), (e + step, n), (e + step, n + step), (e, n + step)])
                squares[name] = poly
            n += step
        e += step
    return list(squares.items())


def project_extent(extent_wgs84: tuple[float, float, float, float], crs: int) -> tuple[float, float, float, float]:
    min_lon, min_lat, max_lon, max_lat = extent_wgs84
    tf = Transformer.from_crs(4326, crs, always_xy=True)
    corners = [
        tf.transform(min_lon, min_lat),
        tf.transform(max_lon, min_lat),
        tf.transform(min_lon, max_lat),
        tf.transform(max_lon, max_lat),
    ]
    xs = [c[0] for c in corners]
    ys = [c[1] for c in corners]
    return min(xs), min(ys), max(xs), max(ys)


def to_wgs84_polygon(poly: Polygon, crs: int) -> Polygon:
    tf = TO_WGS84[crs]
    coords = [tf.transform(x, y) for x, y in poly.exterior.coords]
    return Polygon(coords)


def load_land() -> gpd.GeoDataFrame:
    if not LAND_SHP.exists():
        print(f"Land shapefile not found: {LAND_SHP}", file=sys.stderr)
        sys.exit(1)
    print("Loading OSM land polygons for GB/IE/CI bbox...")
    land = gpd.read_file(LAND_SHP, bbox=LAND_BBOX)
    land = land[land.geometry.notnull() & ~land.geometry.is_empty]
    print(f"  {len(land)} land polygons loaded")
    return land


def square_intersects_land(square_wgs84: Polygon, land: gpd.GeoDataFrame) -> bool:
    return land.intersects(square_wgs84).any()


def boundary_and_point(name: str, square_wgs84: Polygon) -> list[dict]:
    ring = list(square_wgs84.exterior.coords)
    centroid = square_wgs84.centroid
    return [
        {
            "type": "Feature",
            "geometry": {
                "type": "MultiLineString",
                "coordinates": [[list(ring)]],
            },
            "properties": {
                "name": f"Small Square {name} Boundry Box",
                "tessellate": True,
            },
        },
        {
            "type": "Feature",
            "geometry": mapping(centroid),
            "properties": {"name": name},
        },
    ]


def collect_osgb_squares() -> list[tuple[str, Polygon, int]]:
    squares = []
    for feat in bng_grid_10km:
        name = feat["properties"]["bng_ref"]
        poly = shape(feat["geometry"])
        squares.append((name, poly, 27700))
    return squares


def main() -> None:
    land = load_land()

    # GB from official osbng 10 km grid.
    candidates: list[tuple[str, Polygon, int]] = collect_osgb_squares()

    # Irish Grid — whole island (Cloudlog includes ROI + NI Irish squares).
    ireland_extent_wgs84 = (-11.0, 51.4, -5.0, 55.5)
    irish_extent = project_extent(ireland_extent_wgs84, 29902)
    for name, poly in iter_grid_squares(29902, irish_prefix, irish_extent):
        candidates.append((name, poly, 29902))

    # Channel Islands (UTM 30N, WV/WX etc.).
    ci_extent_wgs84 = (-3.2, 48.8, -1.7, 49.98)
    ci_extent = project_extent(ci_extent_wgs84, 32630)
    for name, poly in iter_grid_squares(32630, mgrs_prefix, ci_extent):
        candidates.append((name, poly, 32630))

    print(f"Candidate squares before land filter: {len(candidates)}")

    features: list[dict] = []
    kept: list[str] = []
    gb_count = irish_count = ci_count = 0
    ci_prefixes = {"WV", "WX", "WR", "WS", "WT", "WU"}

    for name, poly, crs in candidates:
        square_wgs84 = to_wgs84_polygon(poly, crs)
        if square_wgs84.is_empty or not square_wgs84.is_valid:
            square_wgs84 = square_wgs84.buffer(0)
        if not square_intersects_land(square_wgs84, land):
            continue

        if crs == 29902:
            irish_count += 1
        elif crs == 32630 or name[:2] in ci_prefixes:
            ci_count += 1
        else:
            gb_count += 1

        kept.append(name)
        features.extend(boundary_and_point(name, square_wgs84))

    # Detect accidental duplicate names or coordinates (CRS overlap bugs).
    names = [f["properties"]["name"] for f in features if f["geometry"]["type"] == "Point"]
    if len(names) != len(set(names)):
        dup_names = {n for n in names if names.count(n) > 1}
        raise SystemExit(f"Duplicate square names generated: {sorted(dup_names)[:10]}")

    from collections import defaultdict

    by_coord: dict[tuple, list[str]] = defaultdict(list)
    for f in features:
        if f["geometry"]["type"] == "Point":
            key = tuple(round(c, 5) for c in f["geometry"]["coordinates"])
            by_coord[key].append(f["properties"]["name"])
    dups = {k: v for k, v in by_coord.items() if len(v) > 1}
    if dups:
        raise SystemExit(f"Duplicate coordinates (CRS overlap): {list(dups.items())[:5]}")

    required = {"NL57", "NL58", "HW83"}
    missing = required - set(names)
    if missing:
        raise SystemExit(f"Required island squares missing after land filter: {missing}")

    geojson = {"type": "FeatureCollection", "features": features}
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    with OUTPUT.open("w", encoding="utf-8") as fh:
        json.dump(geojson, fh, indent=4)
        fh.write("\n")

    size_bytes = OUTPUT.stat().st_size
    print("\n=== Generation complete ===")
    print(f"Output: {OUTPUT}")
    print(f"Land squares kept: {len(names)} ({gb_count} OSGB + {irish_count} Irish + {ci_count} CI)")
    print(f"GeoJSON features: {len(features)} (2 per square, no large boundary boxes)")
    print(f"File size: {size_bytes:,} bytes ({size_bytes / 1024 / 1024:.2f} MiB)")
    print(f"Required islands present: {', '.join(sorted(required))}")


if __name__ == "__main__":
    main()
