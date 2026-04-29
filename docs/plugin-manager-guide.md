# Cloudlog Plugin Manager Guide (Developer Reference)

This guide is a detailed technical reference for Cloudlog's Plugin Manager.

It focuses on what developers can do, how the manager works internally, and how to build plugin packages that install and upgrade cleanly.

## 1. Scope and Audience

This document is for developers who:

- Build Cloudlog plugins.
- Maintain plugin zip releases.
- Need predictable install/upgrade behavior.
- Need to troubleshoot plugin install or runtime issues.

For writing award-specific plugin logic, also see:

- [docs/awards-plugin-guide.md](docs/awards-plugin-guide.md)

For system-level architecture overview, see:

- [docs/plugin-system-phase1.md](docs/plugin-system-phase1.md)

## 2. Plugin Manager Capabilities

The Plugin Manager currently supports:

- Uploading and installing plugin zip packages.
- Metadata registration in the `plugins` database table.
- Enabling/disabling plugins.
- Deleting plugins (files on disk + registry record).
- In-place upgrade when a zip is uploaded with an existing plugin slug.
- Upgrade backup and rollback on failure.
- Validation checks for archive safety and manifest structure.

Current implementation entry points:

- Controller: [application/controllers/Plugins.php](application/controllers/Plugins.php)
- Runtime library: [application/libraries/Plugin_manager.php](application/libraries/Plugin_manager.php)
- Data model: [application/models/Plugins_model.php](application/models/Plugins_model.php)
- Admin UI view: [application/views/plugins/index.php](application/views/plugins/index.php)

## 3. Data Model and Persistence

Migration:

- [application/migrations/268_create_plugins_table.php](application/migrations/268_create_plugins_table.php)

Table: `plugins`

Key fields:

- `plugin_slug`: unique plugin identity.
- `plugin_name`, `plugin_version`, `plugin_description`.
- `plugin_status`: `enabled` or `disabled`.
- `plugin_manifest`: stored JSON manifest snapshot.
- `installed_at`, `updated_at`.

Important behavior:

- The DB record is the plugin registry.
- On listing, manager attempts to read manifest from disk first, then falls back to DB snapshot.
- `upsert_plugin` preserves enabled status during upgrades.

## 4. Plugin Package Requirements

A valid plugin zip must:

1. Contain `manifest.json` either at zip root or inside a single top-level folder.
2. Include a valid `slug` in manifest matching pattern `^[a-z0-9_-]+$`.
3. Include code files referenced by `entry` and `class` manifest fields (or defaults).

Minimum recommended package:

```text
my-plugin/
  manifest.json
  Plugin.php
  README.md
```

Reference examples:

- [docs/examples/plugins/club-awards-plus/](docs/examples/plugins/club-awards-plus)
- [docs/examples/plugins/award-73-on-73/](docs/examples/plugins/award-73-on-73)

## 5. Upload and Install Workflow (What Happens)

When an admin uploads a plugin zip in Plugin Manager:

1. CSRF token is validated.
2. Zip upload is accepted only for `.zip` extension and configured limits.
3. Archive is inspected for unsafe paths.
4. Archive is extracted to a temp directory.
5. `manifest.json` is located and parsed.
6. Manifest and slug are validated.
7. Plugin files are copied to `application/plugins/<slug>/`.
8. Plugin metadata is upserted into DB.
9. Plugin remains disabled by default (unless existing plugin was already enabled and upgraded).

Key code paths:

- `Plugins::upload()` in [application/controllers/Plugins.php](application/controllers/Plugins.php)
- `Plugin_manager::install_from_zip()` in [application/libraries/Plugin_manager.php](application/libraries/Plugin_manager.php)

## 6. Upgrade-in-Place Behavior

If uploaded plugin slug already exists on disk:

1. Existing plugin directory is moved to backup.
2. New files are copied in.
3. DB metadata is updated.
4. Backup is deleted on success.

If file copy or DB update fails:

1. New files are removed.
2. Backup is restored.

Result:

- Reduced risk of partial updates.
- Safer iterative plugin release workflow.

## 7. Security and Safety Checks

The manager applies multiple protection layers.

### 7.1 Authorization

- Plugin Manager UI/actions require admin authorization (`authorize(99)`).

### 7.2 CSRF on Admin Actions

Custom session token is required for:

- Upload
- Enable
- Disable
- Delete

### 7.3 Archive Path Validation

Installer rejects zip entries containing:

- Path traversal (`../`)
- Absolute paths
- Drive-root style paths

This protects against writing outside plugin directory.

### 7.4 Manifest Validation

Installer validates:

- JSON parseability
- Valid slug pattern
- Award menu method shape (warning if declaration looks invalid/missing)

### 7.5 Runtime Resilience

- Hook and award-render exceptions are caught/logged.
- Core QSO workflows continue if plugin execution fails.

## 8. Enable/Disable Semantics

Enable/disable toggles update `plugin_status` in DB.

Effects:

- Disabled plugins do not run hooks.
- Disabled plugins do not appear in dynamic Awards plugin entries.
- Files remain on disk; only runtime activation changes.

Methods:

- `Plugin_manager::set_enabled()`
- `Plugins::enable()` and `Plugins::disable()`

## 9. Award Menu Integration via Plugin Manager

Plugin Manager provides menu entry discovery for enabled plugins through:

- `Plugin_manager::get_award_menu_entries()`

Manifest `award_menu` fields interpreted:

- `title`: required for menu inclusion.
- `route`: defaults to `plugin_awards/view/<slug>`.
- `icon`: defaults to `fas fa-award`.
- `order`: sort key (then title).

Rendered from header template:

- [application/views/interface_assets/header.php](application/views/interface_assets/header.php)

Award route controller:

- [application/controllers/Plugin_awards.php](application/controllers/Plugin_awards.php)

## 10. Delete/Uninstall Behavior

Delete in Plugin Manager performs two actions:

1. Removes the plugin directory from `application/plugins/<slug>/`.
2. Removes the plugin record from the `plugins` table.

Important notes:

- Delete is an admin-only action and CSRF protected.
- If file removal fails, metadata is not removed.
- If metadata removal fails, an error is returned and action is reported as failed.

## 11. Disk Layout and Git Workflow

Installed plugins live under:

- `application/plugins/<slug>/`

Repository ignore strategy should keep folder scaffold while ignoring installed plugin contents.

Current `.gitignore` behavior:

- Ignores all under `application/plugins/*`
- Re-includes `application/plugins/index.html`

This allows:

- Folder existence after clone.
- Local plugin installations without git noise during pull/rebase.

## 12. Developer Packaging Checklist

Before shipping a plugin zip:

1. Validate `manifest.json` syntax.
2. Confirm `slug` is stable and lowercase.
3. Confirm `entry` file exists in package.
4. Confirm class name matches manifest `class`.
5. Confirm `award_menu.method` exists if used.
6. Increment version for each release.
7. Test upgrade path by installing old version then uploading new version.

## 13. Common Errors and Fixes

### Error: "manifest.json was not found"

Cause:

- Incorrect zip structure.

Fix:

- Place `manifest.json` at root or within one top-level folder.

### Error: "manifest.json is invalid JSON"

Cause:

- Trailing comma or malformed JSON.

Fix:

- Validate with a JSON linter before zipping.

### Error: "Invalid plugin slug"

Cause:

- Slug has spaces/uppercase/special characters.

Fix:

- Use lowercase letters, numbers, `_`, `-`.

### Warning about `award_menu.method`

Cause:

- Method declared but missing in entry file.

Fix:

- Implement method or correct spelling in manifest.

### Plugin installs but does not appear in Awards

Cause:

- Plugin disabled or missing valid `award_menu` block.

Fix:

- Enable plugin and verify `award_menu.title` + method declaration.

### Upgrade appears not applied

Cause:

- Same version uploaded or old package re-zipped accidentally.

Fix:

- Bump version, verify zip contents, reinstall.

## 14. Operational Best Practices

- Keep plugin slugs permanent.
- Treat manifest as contract/API for manager.
- Keep plugin code self-contained inside plugin folder.
- Avoid side effects in constructors.
- Log meaningful errors with plugin slug context.
- Keep upgrade releases atomic and small.

## 15. What Plugin Manager Does Not Do (Yet)

Current known limitations:

- No plugin signature verification.
- No marketplace/remote catalog.
- No capability/permission prompt model.
- No staged/canary rollout.

These are potential future-phase enhancements.

## 16. Useful Source References

- Plugin Manager controller:
  - [application/controllers/Plugins.php](application/controllers/Plugins.php)
- Plugin manager runtime logic:
  - [application/libraries/Plugin_manager.php](application/libraries/Plugin_manager.php)
- Plugin data access model:
  - [application/models/Plugins_model.php](application/models/Plugins_model.php)
- Plugin awards route:
  - [application/controllers/Plugin_awards.php](application/controllers/Plugin_awards.php)
- Hook runtime:
  - [application/libraries/Cloudlog_hooks.php](application/libraries/Cloudlog_hooks.php)
- Plugin Manager UI:
  - [application/views/plugins/index.php](application/views/plugins/index.php)
- Phase 1 architecture document:
  - [docs/plugin-system-phase1.md](docs/plugin-system-phase1.md)
