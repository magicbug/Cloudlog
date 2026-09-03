#!/usr/bin/env python3
"""Regenerate Cloudlog WABSquares.geojson for official WAB award territory.

WAB award scope (wab.intermip.net/Definitions.php):
  - Great Britain (England, Scotland, Wales): Ordnance Survey National Grid (EPSG:27700)
  - Northern Ireland only: Irish Grid (EPSG:29902) — not Republic of Ireland (WAI/IRT)
  - Channel Islands: UTM zone 30N (EPSG:32630)

Land intersection uses OSM land-polygons. Award territory uses Natural Earth admin
boundaries (GB = UK minus NI; NI via gu_a3=NIR; CI = Jersey + Guernsey; France and
ROI excluded). This prevents OSGB squares over Ireland/NI, Irish Grid squares over
ROI, and France-only coastal OSGB cells from the broad BNG index extent.
"""

from __future__ import annotations

import json
import sys
import urllib.request
import zipfile
from collections import defaultdict
from dataclasses import dataclass
from pathlib import Path

import geopandas as gpd
from osbng.grids import bng_grid_10km
from pyproj import Transformer
from shapely.geometry import Point, Polygon, mapping, shape
from shapely.ops import unary_union

ROOT = Path(__file__).resolve().parents[1]
OUTPUT = ROOT / "assets" / "json" / "WABSquares.geojson"
LAND_SHP = Path("/tmp/osm/land/land-polygons-split-4326/land_polygons.shp")
ADMIN1_SHP = Path("/tmp/admin/admin1/ne_10m_admin_1_states_provinces.shp")
COUNTRIES_SHP = Path("/tmp/admin/countries/ne_10m_admin_0_countries.shp")

LAND_BBOX = (-11.5, 49.0, 2.5, 61.5)
MIN_CROSS_GRID_OVERLAP = 1e-6  # deg²; catches visible polygon overlap, ignores numerical noise
REQUIRED_SQUARES = {"NL57", "NL58", "HW83"}
CI_PREFIXES = frozenset({"WV", "WX", "WR", "WS", "WT", "WU"})

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


@dataclass
class Territories:
    gb: object  # UK admin1 minus NI (mainland GB reference)
    ni: object
    ci: object
    france: object
    roi: object
    wab: object  # GB + NI + CI reference union
    foreign: object  # NI + France + ROI + CI — subtract from OSGB squares


@dataclass
class SquareRecord:
    name: str
    crs: int
    grid: str  # osgb | irish | ci
    polygon_wgs84: Polygon


def ensure_admin_boundaries() -> None:
    if ADMIN1_SHP.exists() and COUNTRIES_SHP.exists():
        return
    ADMIN1_SHP.parent.mkdir(parents=True, exist_ok=True)
    COUNTRIES_SHP.parent.mkdir(parents=True, exist_ok=True)
    sources = [
        ("https://naciscdn.org/naturalearth/10m/cultural/ne_10m_admin_1_states_provinces.zip", ADMIN1_SHP.parent),
        ("https://naciscdn.org/naturalearth/10m/cultural/ne_10m_admin_0_countries.zip", COUNTRIES_SHP.parent),
    ]
    for url, dest in sources:
        zip_path = dest / "download.zip"
        print(f"Downloading {url} ...")
        urllib.request.urlretrieve(url, zip_path)
        with zipfile.ZipFile(zip_path) as zf:
            zf.extractall(dest)


def load_territories() -> Territories:
    ensure_admin_boundaries()
    admin1 = gpd.read_file(ADMIN1_SHP).to_crs(4326)
    countries = gpd.read_file(COUNTRIES_SHP).to_crs(4326)

    gb = unary_union(admin1[(admin1.admin == "United Kingdom") & (admin1.gu_a3 != "NIR")].geometry)
    ni = unary_union(admin1[(admin1.admin == "United Kingdom") & (admin1.gu_a3 == "NIR")].geometry)
    ci = unary_union(countries[countries.ADMIN.isin(["Jersey", "Guernsey"])].geometry)
    france = countries[countries.ADMIN == "France"].geometry.iloc[0]
    roi = countries[countries.ADMIN == "Ireland"].geometry.iloc[0]
    foreign = unary_union([ni, france, roi, ci])
    wab = unary_union([gb, ni, ci])
    return Territories(gb=gb, ni=ni, ci=ci, france=france, roi=roi, wab=wab, foreign=foreign)


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
    prefix_func,
    extent: tuple[float, float, float, float],
    step: int = 10_000,
) -> list[tuple[str, Polygon]]:
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
                squares[name] = Polygon([(e, n), (e + step, n), (e + step, n + step), (e, n + step)])
            n += step
        e += step
    return list(squares.items())


def project_extent(extent_wgs84: tuple[float, float, float, float], crs: int) -> tuple[float, float, float, float]:
    min_lon, min_lat, max_lon, max_lat = extent_wgs84
    tf = Transformer.from_crs(4326, crs, always_xy=True)
    corners = [tf.transform(min_lon, min_lat), tf.transform(max_lon, min_lat),
               tf.transform(min_lon, max_lat), tf.transform(max_lon, max_lat)]
    xs = [c[0] for c in corners]
    ys = [c[1] for c in corners]
    return min(xs), min(ys), max(xs), max(ys)


def to_wgs84_polygon(poly: Polygon, crs: int) -> Polygon:
    tf = TO_WGS84[crs]
    coords = [tf.transform(x, y) for x, y in poly.exterior.coords]
    poly_wgs = Polygon(coords)
    if not poly_wgs.is_valid:
        poly_wgs = poly_wgs.buffer(0)
    return poly_wgs


def load_land() -> gpd.GeoDataFrame:
    if not LAND_SHP.exists():
        print(f"Land shapefile not found: {LAND_SHP}", file=sys.stderr)
        sys.exit(1)
    print("Loading OSM land polygons ...")
    land = gpd.read_file(LAND_SHP, bbox=LAND_BBOX).to_crs(4326)
    land = land[land.geometry.notnull() & ~land.geometry.is_empty]
    print(f"  {len(land)} land polygons")
    return land


def land_intersection(square: Polygon, land: gpd.GeoDataFrame) -> Polygon | None:
    hits = land[land.intersects(square)]
    if hits.empty:
        return None
    merged = unary_union(hits.geometry)
    clipped = square.intersection(merged)
    if clipped.is_empty:
        return None
    return clipped


def gb_qualifying_land(land_part: Polygon, territories: Territories) -> Polygon | None:
    """Land within a square that belongs to WAB GB (not NI, France, ROI, or CI).

    Uses OSM land minus admin foreign territories so Scottish islands are kept
    even when Natural Earth admin polygons omit them. Rejects French coast cells
    whose land falls outside WAB admin but inside France (e.g. TW10 near Calais).
    """
    if land_part.intersection(territories.ni).area > 0:
        return None
    remaining = land_part.difference(territories.foreign)
    if remaining.is_empty:
        return None
    in_wab = not remaining.intersection(territories.wab).is_empty
    in_france = not land_part.intersection(territories.france).is_empty
    if not in_wab and in_france:
        return None
    return remaining


def qualifies_osgb(land_part: Polygon, territories: Territories) -> bool:
    return gb_qualifying_land(land_part, territories) is not None


def qualifies_irish(land_part: Polygon, territories: Territories) -> bool:
    ni_land = land_part.intersection(territories.ni)
    return not ni_land.is_empty


def qualifies_ci(land_part: Polygon, territories: Territories) -> bool:
    ci_land = land_part.intersection(territories.ci)
    return not ci_land.is_empty


def clip_to_territory(square: Polygon, grid: str, territories: Territories) -> Polygon | None:
    if grid == "osgb":
        # Subtract foreign territories rather than intersecting GB admin (misses islands).
        clipped = square.difference(territories.foreign)
    elif grid == "irish":
        clipped = square.intersection(territories.ni)
    else:
        clipped = square.intersection(territories.ci)
    return _normalize_polygon(clipped)


def _normalize_polygon(geom) -> Polygon | None:
    if geom is None or geom.is_empty:
        return None
    if geom.geom_type == "Polygon":
        return geom if geom.area >= 1e-10 else None
    if geom.geom_type == "MultiPolygon":
        geom = max(geom.geoms, key=lambda g: g.area)
        return geom if geom.area >= 1e-10 else None
    if geom.geom_type == "GeometryCollection":
        polys = [g for g in geom.geoms if g.geom_type in ("Polygon", "MultiPolygon")]
        if not polys:
            return None
        return _normalize_polygon(max(polys, key=lambda g: g.area))
    return None


def boundary_and_point(name: str, square_wgs84: Polygon) -> list[dict]:
    # RFC 7946 MultiLineString: [ lineString1, lineString2, ... ]
    # Each LineString: [ [lon, lat], ... ] — match master nesting (not [[ring]]).
    ring = [[float(lon), float(lat)] for lon, lat in square_wgs84.exterior.coords]
    centroid = square_wgs84.representative_point()
    return [
        {
            "type": "Feature",
            "geometry": {"type": "MultiLineString", "coordinates": [ring]},
            "properties": {"name": f"Small Square {name} Boundry Box", "tessellate": True},
        },
        {
            "type": "Feature",
            "geometry": mapping(centroid),
            "properties": {"name": name},
        },
    ]


def collect_candidates() -> list[tuple[str, Polygon, int, str]]:
    candidates: list[tuple[str, Polygon, int, str]] = []
    for feat in bng_grid_10km:
        candidates.append((feat["properties"]["bng_ref"], shape(feat["geometry"]), 27700, "osgb"))

    # Irish Grid only within Northern Ireland extent (not ROI).
    ni_extent_wgs84 = (-8.25, 54.0, -5.35, 55.35)
    irish_extent = project_extent(ni_extent_wgs84, 29902)
    for name, poly in iter_grid_squares(irish_prefix, irish_extent):
        candidates.append((name, poly, 29902, "irish"))

    ci_extent_wgs84 = (-3.2, 48.8, -1.7, 49.98)
    ci_extent = project_extent(ci_extent_wgs84, 32630)
    for name, poly in iter_grid_squares(mgrs_prefix, ci_extent):
        candidates.append((name, poly, 32630, "ci"))

    return candidates


def filter_candidates(
    candidates: list[tuple[str, Polygon, int, str]],
    land: gpd.GeoDataFrame,
    territories: Territories,
) -> list[SquareRecord]:
    kept: list[SquareRecord] = []
    stats = defaultdict(int)

    for name, poly, crs, grid in candidates:
        square_wgs84 = to_wgs84_polygon(poly, crs)
        land_part = land_intersection(square_wgs84, land)
        if land_part is None:
            stats["no_land"] += 1
            continue

        if grid == "osgb":
            if not qualifies_osgb(land_part, territories):
                stats["osgb_territory_reject"] += 1
                continue
        elif grid == "irish":
            if not qualifies_irish(land_part, territories):
                stats["irish_not_ni"] += 1
                continue
        elif grid == "ci":
            if not qualifies_ci(land_part, territories):
                stats["ci_not_ci"] += 1
                continue

        clipped = clip_to_territory(square_wgs84, grid, territories)
        if clipped is None:
            stats["clip_empty"] += 1
            continue

        # Must still contain qualifying land after territory clip
        clipped_land = land_intersection(clipped, land)
        if clipped_land is None:
            stats["clip_no_land"] += 1
            continue

        kept.append(SquareRecord(name=name, crs=crs, grid=grid, polygon_wgs84=clipped))
        stats[f"kept_{grid}"] += 1

    print("Filter stats:", dict(stats))
    return kept


def validate(records: list[SquareRecord], territories: Territories, land: gpd.GeoDataFrame) -> None:
    names = [r.name for r in records]
    if len(names) != len(set(names)):
        dup = {n for n in names if names.count(n) > 1}
        raise SystemExit(f"Duplicate square names: {sorted(dup)[:10]}")

    missing = REQUIRED_SQUARES - set(names)
    if missing:
        raise SystemExit(f"Required island squares missing: {missing}")

    ci = [r.name for r in records if r.grid == "ci"]
    if not ci:
        raise SystemExit("No Channel Islands squares retained")

    # Exact duplicate label coordinates
    by_coord: dict[tuple, list[str]] = defaultdict(list)
    for r in records:
        c = r.polygon_wgs84.centroid
        by_coord[(round(c.x, 6), round(c.y, 6))].append(r.name)
    coord_dups = {k: v for k, v in by_coord.items() if len(v) > 1}
    if coord_dups:
        raise SystemExit(f"Duplicate centroids: {list(coord_dups.items())[:5]}")

    # Cross-grid polygon overlaps
    overlaps = []
    for i, a in enumerate(records):
        for b in records[i + 1 :]:
            if a.grid == b.grid:
                continue
            if a.polygon_wgs84.intersects(b.polygon_wgs84):
                inter = a.polygon_wgs84.intersection(b.polygon_wgs84)
                if not inter.is_empty and inter.area > MIN_CROSS_GRID_OVERLAP:
                    overlaps.append((a.name, a.grid, b.name, b.grid, inter.area))
    if overlaps:
        sample = overlaps[:8]
        raise SystemExit(f"Cross-grid polygon overlaps ({len(overlaps)}): {sample}")

    # France-only squares (land-based, not bbox)
    france_only = []
    for r in records:
        lp = land_intersection(r.polygon_wgs84, land)
        if lp is None:
            continue
        if r.grid == "osgb":
            if gb_qualifying_land(lp, territories) is None and not lp.intersection(territories.france).is_empty:
                france_only.append(r.name)
        elif lp.intersection(territories.wab).is_empty and not lp.intersection(territories.france).is_empty:
            france_only.append(r.name)
    if france_only:
        raise SystemExit(f"France-only squares retained: {france_only[:10]}")

    # ROI-only Irish grid squares (should be none)
    roi_irish = []
    for r in records:
        if r.grid != "irish":
            continue
        in_ni = not r.polygon_wgs84.intersection(territories.ni).is_empty
        in_roi = not r.polygon_wgs84.intersection(territories.roi).is_empty
        if in_roi and not in_ni:
            roi_irish.append(r.name)
    if roi_irish:
        raise SystemExit(f"Irish grid squares outside NI: {roi_irish[:10]}")

    # OSGB over NI (polygon should not intersect NI after foreign subtract clip)
    osgb_ni = [
        r.name for r in records
        if r.grid == "osgb" and r.polygon_wgs84.intersects(territories.ni)
    ]
    if osgb_ni:
        raise SystemExit(f"OSGB squares intersecting NI: {osgb_ni[:10]}")

    print(f"Validation passed: {len(records)} squares, {len(ci)} CI, 0 cross-grid overlaps")


def main() -> None:
    territories = load_territories()
    land = load_land()
    candidates = collect_candidates()
    print(f"Candidates before filter: {len(candidates)}")

    records = filter_candidates(candidates, land, territories)
    validate(records, territories, land)

    features: list[dict] = []
    counts = defaultdict(int)
    for r in records:
        counts[r.grid] += 1
        features.extend(boundary_and_point(r.name, r.polygon_wgs84))

    geojson = {"type": "FeatureCollection", "features": features}
    OUTPUT.parent.mkdir(parents=True, exist_ok=True)
    with OUTPUT.open("w", encoding="utf-8") as fh:
        json.dump(geojson, fh, indent=4)
        fh.write("\n")

    # Post-write GeoJSON structure validation (Leaflet / RFC 7946)
    sys.path.insert(0, str(Path(__file__).parent))
    from validate_wab_geojson import validate_file

    geo_stats = validate_file(OUTPUT)
    print("GeoJSON validation:", geo_stats)

    size_bytes = OUTPUT.stat().st_size
    print("\n=== Generation complete ===")
    print(f"Output: {OUTPUT}")
    print(f"Squares: {len(records)} (osgb={counts['osgb']}, irish={counts['irish']}, ci={counts['ci']})")
    print(f"Features: {len(features)}")
    print(f"File size: {size_bytes:,} bytes ({size_bytes / 1024 / 1024:.2f} MiB)")
    print(f"Required islands: {', '.join(sorted(REQUIRED_SQUARES))}")


if __name__ == "__main__":
    main()
