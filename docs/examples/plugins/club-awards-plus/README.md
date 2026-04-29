# Club Awards Plus (Example Plugin)

This is a minimal example plugin for Cloudlog Phase 1.

## What It Demonstrates

- Hook registration in `manifest.json`
- A filter hook (`qso.filter.before_save`) that can modify payload data
- Action hooks (`qso.action.after_save`, `qso.action.after_edit`) for side effects
- Awards dropdown integration via `award_menu`
- Plugin award page rendering via `renderAwardPage`

## Files

- `manifest.json`
- `Plugin.php`

## Quick Test

1. Zip the contents of this folder.
2. Open Cloudlog Admin > Plugin Manager.
3. Upload the zip.
4. Enable `club-awards-plus`.
5. Add or edit a QSO and check application logs for plugin messages.
6. Open the Awards menu and click `Club Awards Plus` to view the plugin award page.

## Notes

- This sample is intentionally simple and uses only safe, synchronous behavior.
- For heavy processing, use queue-based logic in a later phase.
