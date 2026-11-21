#!/usr/bin/env python3
"""
Build simplified USA-CA county GeoJSON for Cloudlog awards maps.

Reads per-state counties.geojson from a source folder (default: the donated
MM9SQL pack), normalizes names to Usaca award counties, simplifies geometry,
and writes:
  assets/json/counties/US-XX.geojson
  assets/json/counties/US-states.geojson
"""

from __future__ import annotations

import argparse
import csv
import json
import re
import sys
from collections import defaultdict
from pathlib import Path

from shapely.geometry import mapping, shape
from shapely.ops import unary_union
from shapely.validation import make_valid

REPO = Path(__file__).resolve().parents[1]
DEFAULT_SOURCE = Path(r"C:\Users\peter\Downloads\Counties for MM9SQL\Counties for MM9SQL")
OUT_DIR = REPO / "assets" / "json" / "counties"

STATE_NAMES = {
    "Alaska": "AK", "Alabama": "AL", "Arkansas": "AR", "Arizona": "AZ",
    "California": "CA", "Colorado": "CO", "Connecticut": "CT", "Delaware": "DE",
    "Florida": "FL", "Georgia": "GA", "Hawaii": "HI", "Iowa": "IA", "Idaho": "ID",
    "Illinois": "IL", "Indiana": "IN", "Kansas": "KS", "Kentucky": "KY",
    "Louisiana": "LA", "Massachusetts": "MA", "Maryland": "MD", "Maine": "ME",
    "Michigan": "MI", "Minnesota": "MN", "Missouri": "MO", "Mississippi": "MS",
    "Montana": "MT", "North Carolina": "NC", "North Dakota": "ND", "Nebraska": "NE",
    "New Hampshire": "NH", "New Jersey": "NJ", "New Mexico": "NM", "Nevada": "NV",
    "New York": "NY", "Ohio": "OH", "Oklahoma": "OK", "Oregon": "OR",
    "Pennsylvania": "PA", "Rhode Island": "RI", "South Carolina": "SC",
    "South Dakota": "SD", "Tennessee": "TN", "Texas": "TX", "Utah": "UT",
    "Virginia": "VA", "Vermont": "VT", "Washington": "WA", "Wisconsin": "WI",
    "West Virginia": "WV", "Wyoming": "WY",
}

HI_ISLAND_TO_COUNTY = {
    "lanai": "Maui",
    "molokai": "Maui",
    "niihau": "Maui",
}

DROP_NAMES = {
    "HI": {"ford"},
    "MD": {"washingtondc", "districtofcolumbia", "washington dc"},
}

AK_DISTRICT = {
    "1": "First Judicial District",
    "2": "Second Judicial District",
    "3": "Third Judicial District",
    "4": "Fourth Judicial District",
}


def usaca_key(name: str) -> str:
    name = str(name).lower().strip()
    name = name.replace("&", " and ")
    name = re.sub(r"^city of\s+(.*)$", r"\1 city", name)
    name = re.sub(r"^ste\.?\s+", "sainte ", name)
    name = re.sub(r"^st\.?\s+", "saint ", name)
    name = re.sub(
        r"\s+(county|parish|city and borough|borough|census area|municipality)$",
        "",
        name,
    )
    return re.sub(r"[^a-z0-9]+", "", name)


def load_usaca(repo: Path):
    with open(repo / "assets" / "json" / "usaca_map.json", encoding="utf-8") as f:
        umap = json.load(f)

    counties = defaultdict(dict)
    cities = defaultdict(dict)
    renamed = defaultdict(dict)
    alaska = {}

    for district, names in umap["alaska"].items():
        counties["AK"][usaca_key(district)] = district
        for name in names:
            alaska[usaca_key(name)] = district

    for state, pairs in umap.get("renamed", {}).items():
        for old, new in pairs.items():
            renamed[state][usaca_key(old)] = new

    shared = {
        state: {usaca_key(n) for n in names}
        for state, names in umap.get("city_names_shared_with_county", {}).items()
    }
    for state, city_map in umap.get("independent_cities", {}).items():
        for city, neighbors in city_map.items():
            info = {"label": city + " City", "neighbors": neighbors}
            cities[state][usaca_key(city + " City")] = info
            if usaca_key(city) not in shared.get(state, set()):
                cities[state][usaca_key(city)] = info

    with open(repo / "assets" / "json" / "US_counties.csv", encoding="utf-8") as f:
        for row in csv.reader(f):
            if len(row) < 2:
                continue
            state_name = row[0].strip()
            state = STATE_NAMES.get(state_name, state_name if len(state_name) == 2 else "")
            k = usaca_key(row[1])
            if state in ("", "AK") or k == "" or k in cities.get(state, {}) or k in renamed.get(state, {}):
                continue
            counties[state][k] = row[1].strip()

    return counties, cities, renamed, alaska


def map_feature_name(state: str, props: dict, counties, cities, renamed, alaska):
    raw = (props.get("name") or "").strip()
    if not raw:
        return None

    drop = DROP_NAMES.get(state, set())
    if usaca_key(raw) in drop:
        return None

    if state == "AK":
        for field in (raw, props.get("NAME2"), props.get("NAMELSAD"), props.get("NAMELSADCO")):
            if not field:
                continue
            m = re.search(r"#\s*([1-4])", str(field))
            if m:
                return ("county", AK_DISTRICT[m.group(1)])
            k = usaca_key(field)
            if k in alaska:
                return ("county", alaska[k])
            if k in counties["AK"]:
                return ("county", counties["AK"][k])
        return None

    if state == "HI":
        k = usaca_key(raw)
        if k in HI_ISLAND_TO_COUNTY:
            return ("county", HI_ISLAND_TO_COUNTY[k])
        if k in counties.get("HI", {}):
            return ("county", counties["HI"][k])
        return None

    k = usaca_key(raw)
    if k in renamed.get(state, {}):
        return ("county", renamed[state][k])
    if k in cities.get(state, {}):
        city = cities[state][k]
        if len(city["neighbors"]) == 1:
            return ("county", city["neighbors"][0], city["label"])
        return ("city", city["label"], city["neighbors"])
    if k in counties.get(state, {}):
        return ("county", counties[state][k])

    # Carson City and similar: try without forcing city strip failures
    if k.endswith("city") and k in counties.get(state, {}):
        return ("county", counties[state][k])

    return None


def simplify_geom(geom, tolerance: float):
    geom = make_valid(geom)
    if geom.is_empty:
        return None
    simplified = geom.simplify(tolerance, preserve_topology=True)
    simplified = make_valid(simplified)
    if simplified.is_empty:
        return None
    if simplified.geom_type == "GeometryCollection":
        polys = [g for g in simplified.geoms if g.geom_type in ("Polygon", "MultiPolygon") and not g.is_empty]
        if not polys:
            return None
        simplified = unary_union(polys)
    return simplified


def round_coords(obj, ndigits=5):
    if isinstance(obj, (list, tuple)):
        if obj and isinstance(obj[0], (int, float)):
            return [round(float(x), ndigits) for x in obj]
        return [round_coords(x, ndigits) for x in obj]
    if isinstance(obj, dict):
        return {k: round_coords(v, ndigits) for k, v in obj.items()}
    return obj


def process_state(state: str, src_path: Path, counties, cities, renamed, alaska, tolerance: float):
    with open(src_path, encoding="utf-8") as f:
        data = json.load(f)

    buckets = defaultdict(list)  # (kind, county) -> geoms
    city_meta = {}

    for feature in data.get("features", []):
        props = feature.get("properties") or {}
        mapped = map_feature_name(state, props, counties, cities, renamed, alaska)
        if mapped is None:
            continue
        try:
            geom = shape(feature["geometry"])
        except Exception:
            continue
        geom = make_valid(geom)
        if geom.is_empty:
            continue

        kind = mapped[0]
        county = mapped[1]
        if kind == "city":
            city_meta[county] = mapped[2]
        buckets[(kind, county)].append(geom)

    features = []
    for (kind, county), geoms in sorted(buckets.items(), key=lambda x: (x[0][0], x[0][1])):
        merged = unary_union(geoms)
        merged = simplify_geom(merged, tolerance)
        if merged is None:
            continue
        props = {"state": state, "county": county, "kind": kind}
        if kind == "city":
            props["neighbors"] = city_meta.get(county, [])
        features.append({
            "type": "Feature",
            "properties": props,
            "geometry": round_coords(mapping(merged)),
        })

    return {"type": "FeatureCollection", "features": features}


def build_states_layer(state_files: dict, tolerance: float):
    features = []
    for state, collection in sorted(state_files.items()):
        geoms = []
        for feature in collection["features"]:
            try:
                geoms.append(shape(feature["geometry"]))
            except Exception:
                continue
        if not geoms:
            continue
        merged = simplify_geom(unary_union(geoms), tolerance)
        if merged is None:
            continue
        features.append({
            "type": "Feature",
            "properties": {"state": state, "id": f"US-{state}"},
            "geometry": round_coords(mapping(merged)),
        })
    return {"type": "FeatureCollection", "features": features}


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument("--source", type=Path, default=DEFAULT_SOURCE)
    parser.add_argument("--out", type=Path, default=OUT_DIR)
    parser.add_argument("--tolerance", type=float, default=0.005, help="County simplify tolerance in degrees")
    parser.add_argument("--state-tolerance", type=float, default=0.02, help="National state outline simplify tolerance")
    args = parser.parse_args()

    if not args.source.is_dir():
        print(f"Source folder not found: {args.source}", file=sys.stderr)
        sys.exit(1)

    counties, cities, renamed, alaska = load_usaca(REPO)
    args.out.mkdir(parents=True, exist_ok=True)

    written = {}
    total_features = 0
    for folder in sorted(args.source.iterdir()):
        if not folder.is_dir() or not folder.name.startswith("US-"):
            continue
        state = folder.name[3:]
        src = folder / "counties.geojson"
        if not src.exists():
            print(f"skip {folder.name}: no counties.geojson")
            continue
        collection = process_state(state, src, counties, cities, renamed, alaska, args.tolerance)
        out_path = args.out / f"US-{state}.geojson"
        with open(out_path, "w", encoding="utf-8", newline="\n") as f:
            json.dump(collection, f, separators=(",", ":"))
        size_kb = out_path.stat().st_size / 1024
        count = len(collection["features"])
        total_features += count
        written[state] = collection
        print(f"US-{state}: {count} features, {size_kb:.1f} KB")

    states = build_states_layer(written, args.state_tolerance)
    states_path = args.out / "US-states.geojson"
    with open(states_path, "w", encoding="utf-8", newline="\n") as f:
        json.dump(states, f, separators=(",", ":"))
    print(f"US-states: {len(states['features'])} features, {states_path.stat().st_size / 1024:.1f} KB")
    print(f"Done. {len(written)} states, {total_features} county/city features.")


if __name__ == "__main__":
    main()
