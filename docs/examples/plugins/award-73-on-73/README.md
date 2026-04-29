# 73 on 73 (Award Plugin)

Live award plugin for Cloudlog.

Award definition:

- Work 73 unique stations on satellite AO-73 with LoTW or paper QSL confirmation.

## What It Adds

- A new Awards dropdown entry: `73 on 73`
- A live award page with:
  - current progress
  - remaining stations needed
  - list of unique worked callsigns on AO-73

## Install

1. Zip the contents of this folder.
2. In Cloudlog, go to Admin > Plugin Manager.
3. Upload and install the zip.
4. Enable the plugin.
5. Open Awards > 73 on 73.

## Scope Rules

- Counts only QSOs in the current active logbook scope (station locations linked to active logbook).
- Counts unique callsigns where `COL_SAT_NAME` matches `AO-73` (case-insensitive).
- Requires confirmation by either:
  - `COL_LOTW_QSL_RCVD = Y` (LoTW)
  - `COL_QSL_RCVD = Y` (paper QSL)
