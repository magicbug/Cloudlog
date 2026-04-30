# Cloudlog Plugin System Phase 1

This document describes the Phase 1 plugin system implemented for Cloudlog.

Related guides:

- [docs/plugin-manager-guide.md](docs/plugin-manager-guide.md)
- [docs/awards-plugin-guide.md](docs/awards-plugin-guide.md)
- [docs/qso-hooks-plugin-guide.md](docs/qso-hooks-plugin-guide.md)

## Goals

- Allow admin users to upload plugin zip files in the UI.
- Install plugins without patching core files.
- Enable and disable plugins from an admin page.
- Provide a first set of hook points for QSO workflow integration.

## What Is Included

- Admin UI page: Plugin Manager.
- Plugin upload and install flow.
- Plugin metadata persistence in the database.
- Plugin enable or disable controls.
- Hook runtime for filter and action events.
- Initial QSO hooks in add and edit flows.
- Dynamic Award dropdown entries from enabled plugins.

## Database

Migration: application/migrations/268_create_plugins_table.php

Table: plugins

Columns:

- id
- plugin_slug (unique)
- plugin_name
- plugin_version
- plugin_description
- plugin_status (enabled or disabled)
- plugin_manifest (json)
- installed_at
- updated_at

## Admin UI

Controller: application/controllers/Plugins.php
View: application/views/plugins/index.php

Menu entry: Admin dropdown in interface header.

Functions:

- Upload and install a plugin zip.
- Uploading a zip with an existing plugin slug upgrades that plugin in place.
- See installed plugins and status.
- Enable or disable plugin execution.

## Plugin Package Format

A plugin zip must contain a manifest.json in the plugin root (or one level down in a single top folder).

Reference example plugin:

- docs/examples/plugins/club-awards-plus/
- docs/examples/plugins/award-73-on-73/

Required manifest fields:

- slug: lowercase letters, numbers, underscore, dash
- name
- version

Optional manifest fields:

- description
- entry (default: Plugin.php)
- class (default: Plugin)
- hooks (map of hook name to method name)
- award_menu (optional object for Awards dropdown integration)

Award menu object fields:

- title (required if award_menu is present)
- icon (optional, default: fas fa-award)
- route (optional, default: `plugin_awards/view/{plugin-slug}`)
- method (optional, default: renderAwardPage)
- order (optional integer, default: 100)

Example manifest:

```json
{
  "slug": "club-awards-plus",
  "name": "Club Awards Plus",
  "version": "1.0.0",
  "description": "Custom award logic for local club workflows",
  "entry": "Plugin.php",
  "class": "ClubAwardsPlusPlugin",
  "award_menu": {
    "title": "Club Awards Plus",
    "icon": "fas fa-trophy",
    "route": "plugin_awards/view/club-awards-plus",
    "method": "renderAwardPage",
    "order": 120
  },
  "hooks": {
    "qso.filter.before_save": "beforeSave",
    "qso.action.after_save": "afterSave",
    "qso.action.after_edit": "afterEdit"
  }
}
```

## Hook Runtime

Library: application/libraries/Cloudlog_hooks.php

### Filter Hook

- qso.filter.before_save
- Called before QSO data is persisted.
- Plugin method receives payload and context and may return modified payload.

### Action Hooks

- qso.action.after_save
- qso.action.after_edit
- Called after persistence.
- Plugin method receives payload and context.

## Award Dropdown Integration

Controller: application/controllers/Plugin_awards.php
View: application/views/plugins/award_page.php

Controller route:

- `plugin_awards/view/{plugin-slug}`

Behavior:

- Enabled plugins with an `award_menu` manifest section are shown in the Awards dropdown.
- Clicking the menu entry renders the plugin award page through the plugin method defined by `award_menu.method`.
- If `award_menu.method` is omitted, `renderAwardPage` is used.

## QSO Integration Points

File: application/models/Logbook_model.php

Current Phase 1 dispatch points:

- create_qso: applies qso.filter.before_save before add_qso.
- add_qso: dispatches qso.action.after_save after insert and cache clear.
- edit: dispatches qso.action.after_edit after update and cache clear.

## Safety Checks in Upload

Library: application/libraries/Plugin_manager.php

Implemented checks:

- Admin-only access via controller authorization.
- Session-bound CSRF token validation on plugin upload, enable, and disable admin actions.
- ZipArchive availability check.
- Reject path traversal and absolute paths in zip entries.
- Require manifest.json.
- Validate slug format.
- Prevent overwrite when slug already exists.
- Install disabled by default.
- Validate award_menu method declaration and show install warning if missing.
- Backup existing plugin files before upgrade and auto-rollback if upgrade fails.

## Operational Notes

- Run migrations before using Plugin Manager.
- Plugins are stored under `application/plugins/<slug>`.
- Plugin code runs in-process, so only install trusted plugins.
- Plugin exceptions are caught and logged to avoid breaking QSO saves.

## Sample Template Packaging Tip

When creating a zip from the sample template, ensure `manifest.json` is present at the zip root
or in a single top-level folder. The installer supports both patterns.

## Next Steps (Phase 2 Suggestions)

- Signed plugin packages and publisher trust list.
- UI uninstall and upgrade flow with rollback.
- Hook instrumentation in ADIF import and award computations.
- Capability declarations and permission prompts.
- Async queue for heavy plugin actions.
