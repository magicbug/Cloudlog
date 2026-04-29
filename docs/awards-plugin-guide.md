# Cloudlog Awards Plugin Guide (Full)

This guide explains how to create a full Awards plugin for Cloudlog (CodeIgniter 3), from folder layout to packaging, installation, upgrades, and troubleshooting.

It is written for the plugin system implemented in Phase 1.

For Plugin Manager internals and lifecycle details, see:

- [docs/plugin-manager-guide.md](docs/plugin-manager-guide.md)

## 1. What an Awards Plugin Can Do

An awards plugin can:

- Add a new item to the Awards dropdown menu.
- Render a custom award page at runtime.
- Query Cloudlog logbook data and compute custom award metrics.
- Optionally participate in hook events (before save, after save, after edit).

The core idea is:

1. Cloudlog reads your plugin manifest.
2. If `award_menu` exists, your plugin appears in Awards.
3. Cloudlog loads your plugin class and calls your render method.

## 2. Prerequisites

- Cloudlog with plugin system migrations applied.
- Access to Admin > Plugin Manager.
- A working development install with sample config already set.
- Basic PHP + SQL understanding.

## 3. Plugin Folder Layout

Create a folder for your plugin package:

```text
my-award-plugin/
  manifest.json
  Plugin.php
  README.md
```

Minimum required files:

- `manifest.json`
- your entry PHP file (usually `Plugin.php`)

Useful references in this repository:

- [docs/examples/plugins/award-73-on-73/manifest.json](docs/examples/plugins/award-73-on-73/manifest.json)
- [docs/examples/plugins/award-73-on-73/Plugin.php](docs/examples/plugins/award-73-on-73/Plugin.php)
- [docs/examples/plugins/club-awards-plus/manifest.json](docs/examples/plugins/club-awards-plus/manifest.json)

## 4. Manifest: Required and Optional Fields

Create `manifest.json`:

```json
{
  "slug": "my-award-plugin",
  "name": "My Award Plugin",
  "version": "1.0.0",
  "description": "Custom award for my club.",
  "entry": "Plugin.php",
  "class": "MyAwardPlugin",
  "award_menu": {
    "title": "My Award",
    "icon": "fas fa-award",
    "route": "plugin_awards/view/my-award-plugin",
    "method": "renderAwardPage",
    "order": 110
  }
}
```

### Required

- `slug`: lowercase letters, numbers, `_`, `-`.
- `name`
- `version`

### Strongly recommended

- `entry`
- `class`
- `description`

### `award_menu` fields

- `title`: text shown in Awards dropdown.
- `icon`: Font Awesome class.
- `route`: normally `plugin_awards/view/<slug>`.
- `method`: class method used to render the page.
- `order`: lower numbers appear earlier.

## 5. Plugin Class: Minimal Award Renderer

Create `Plugin.php`:

```php
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MyAwardPlugin {

    private $CI;

    public function __construct($ci)
    {
        $this->CI = $ci;
    }

    public function renderAwardPage($context = array())
    {
        $content = '';
        $content .= '<div class="alert alert-info">My Award Plugin is active.</div>';
        $content .= '<p>Replace this with your custom award stats and tables.</p>';

        return array(
            'page_title' => 'Awards - My Award',
            'content' => $content,
        );
    }
}
```

Cloudlog loads this class and calls the method defined in `award_menu.method`.

## 6. Accessing Active Logbook Scope

Most award plugins should only use station locations attached to the current active logbook.

Pattern:

```php
$this->CI->load->model('logbooks_model');

$active_logbook = $this->CI->session->userdata('active_station_logbook');
$station_ids = $this->CI->logbooks_model->list_logbook_relationships($active_logbook);

if (empty($station_ids) || !is_array($station_ids)) {
    return array(
        'page_title' => 'Awards - My Award',
        'content' => '<div class="alert alert-warning">No station locations are associated with your active logbook.</div>',
    );
}
```

This keeps your award results consistent with the rest of the UI.

## 7. Querying QSO Data Safely

Use CI Query Builder. Example:

```php
$table = $this->CI->config->item('table_name');

$this->CI->db->select('COL_CALL, COL_TIME_ON, COL_MODE, COL_GRIDSQUARE, COL_VUCC_GRIDS');
$this->CI->db->from($table);
$this->CI->db->where_in('station_id', $station_ids);
$this->CI->db->where('UPPER(TRIM(COL_SAT_NAME))', 'AO-73');
$this->CI->db->group_start();
$this->CI->db->where('COL_LOTW_QSL_RCVD', 'Y');
$this->CI->db->or_where('COL_QSL_RCVD', 'Y');
$this->CI->db->group_end();
$this->CI->db->order_by('COL_CALL', 'ASC');
$this->CI->db->order_by('COL_TIME_ON', 'ASC');

$query = $this->CI->db->get();
$rows = $query ? $query->result() : array();
```

Good practices:

- Filter by active logbook station IDs.
- Normalize fields like callsign and gridsquare (`trim`, `strtoupper`).
- Keep SQL conditions explicit for confirmations.
- Favor deterministic ordering.

## 8. Date and Time Formatting (User Preference)

Use user-custom date format if set, fallback to system format:

```php
$custom_date_format = $this->CI->session->userdata('user_date_format');
if (!$custom_date_format) {
    $custom_date_format = $this->CI->config->item('qso_date_format');
}

$ts = strtotime($qso_time);
$display = $ts ? date($custom_date_format, $ts) . ' ' . date('H:i', $ts) : '-';
```

This matches Cloudlog conventions used in core pages.

## 9. Grid Display Rule (VUCC Preferred)

If your plugin shows grid information, follow Cloudlog behavior:

1. Prefer `COL_VUCC_GRIDS` when present.
2. Otherwise use `COL_GRIDSQUARE`.

Example:

```php
$vucc = strtoupper(trim((string)($row->COL_VUCC_GRIDS ?? '')));
$grid = strtoupper(trim((string)($row->COL_GRIDSQUARE ?? '')));
$grid_to_show = $vucc !== '' ? $vucc : $grid;
```

## 10. Building the Award Page UI

Most plugins generate HTML as a string and return it in `content`.

Guidelines:

- Escape dynamic output with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- Keep layout Bootstrap-compatible (`card`, `table`, `alert`, etc).
- Return clear empty-state messages.

Return format:

```php
return array(
    'page_title' => 'Awards - My Award',
    'content' => $content,
);
```

## 11. CSV Export for Table Data

A common pattern in Cloudlog awards is browser-side CSV export from the visible table.

Typical approach:

1. Give your table an ID.
2. Add an Export CSV button.
3. Add a small JS function that reads table header/body and downloads CSV.

Use your plugin-specific IDs to avoid collisions.

## 12. Optional: Hook Integration

Awards plugins may also register hooks to enrich QSO data or trigger side effects.

Manifest example:

```json
"hooks": {
  "qso.filter.before_save": "beforeSave",
  "qso.action.after_save": "afterSave",
  "qso.action.after_edit": "afterEdit"
}
```

Example methods:

```php
public function beforeSave($payload, $context = array())
{
    if (!is_array($payload)) {
        return $payload;
    }
    return $payload;
}

public function afterSave($payload, $context = array())
{
    // side effects/logging only
}

public function afterEdit($payload, $context = array())
{
    // side effects/logging only
}
```

Hook errors are logged; core saves continue.

## 13. Packaging and Installing

1. Zip plugin folder contents.
2. In Cloudlog, open Admin > Plugin Manager.
3. Upload zip and install.
4. Enable plugin.
5. Open Awards dropdown and click your plugin.

Installer notes:

- If same slug already exists, installer upgrades in place.
- Existing files are backed up and restored automatically on failure.

## 14. Versioning and Upgrade Strategy

When releasing changes:

1. Update `version` in `manifest.json`.
2. Keep `slug` stable.
3. Upload zip via Plugin Manager.
4. Verify plugin still enabled after upgrade.

Recommended version pattern:

- Patch: `1.0.0` -> `1.0.1` for fixes.
- Minor: `1.0.x` -> `1.1.0` for non-breaking features.

## 15. Security Checklist

- Escape all user/database output in HTML.
- Never trust raw POST/GET without validation.
- Do not execute arbitrary shell commands.
- Keep plugin logic synchronous and predictable.
- Install only trusted plugin packages.

## 16. Testing Checklist

Functional checks:

1. Plugin appears in Plugin Manager after install.
2. Enable/disable works.
3. Awards menu entry appears only when enabled.
4. Award page renders without PHP errors.
5. Empty logbook scope shows safe warning.
6. Counts match expected QSOs.
7. Date/time follows user format preference.
8. CSV export file opens correctly in spreadsheet tools.

Upgrade checks:

1. Upload same slug with higher version.
2. Confirm new behavior appears.
3. Confirm no stale files remain.

## 17. Troubleshooting

### Plugin does not appear in Awards

- Check plugin is enabled in Plugin Manager.
- Check `award_menu` exists in manifest.
- Check `award_menu.method` exists in your class.
- Check slug/route consistency.

### "Unable to load plugin class"

- Verify `entry` filename and `class` name in manifest.
- Verify class is defined exactly as declared.
- Verify file path is inside plugin root.

### Empty award page

- Ensure render method returns non-empty `content`.
- Check active logbook has related station IDs.
- Check SQL filters are not too strict.

### CSV export button does nothing

- Confirm table ID matches JS selector.
- Confirm button calls existing function name.
- Confirm browser blocks are not preventing download.

## 18. Reference Files in Cloudlog

- Plugin runtime and install logic:
  - [application/libraries/Plugin_manager.php](application/libraries/Plugin_manager.php)
  - [application/controllers/Plugins.php](application/controllers/Plugins.php)
- Award page routing:
  - [application/controllers/Plugin_awards.php](application/controllers/Plugin_awards.php)
  - [application/views/plugins/award_page.php](application/views/plugins/award_page.php)
- Hook runtime:
  - [application/libraries/Cloudlog_hooks.php](application/libraries/Cloudlog_hooks.php)
- Example plugins:
  - [docs/examples/plugins/award-73-on-73/](docs/examples/plugins/award-73-on-73)
  - [docs/examples/plugins/club-awards-plus/](docs/examples/plugins/club-awards-plus)

## 19. Quick Start Template

Copy this minimal starter and adjust names:

1. Create folder `my-award-plugin`.
2. Add manifest with `slug`, `name`, `version`, `entry`, `class`, `award_menu`.
3. Add class with `renderAwardPage` method.
4. Zip folder contents.
5. Upload, install, enable.

If you want a production-ready baseline, start from:

- [docs/examples/plugins/award-73-on-73/Plugin.php](docs/examples/plugins/award-73-on-73/Plugin.php)
