# Cloudlog QSO Hooks Plugin Guide

This guide explains how to build a plugin that uploads or syncs QSOs to third-party systems.

Primary use case:

- push newly created QSOs to an external service
- re-sync edited QSOs so external records stay accurate
- normalize outgoing QSO data before sync

Related docs:

- [docs/plugin-system-phase1.md](docs/plugin-system-phase1.md)
- [docs/plugin-manager-guide.md](docs/plugin-manager-guide.md)
- [docs/awards-plugin-guide.md](docs/awards-plugin-guide.md)

## 1. Why Use QSO Hooks For Third-Party Uploads

QSO hooks let you keep Cloudlog as the source of truth while automatically syncing to external systems.

Common sync scenarios:

- Send each new QSO to a club API or internal stats platform.
- Update or overwrite a third-party record when a QSO is edited.
- Trigger webhooks for Discord, Slack, Matrix, or custom automation.
- Bridge Cloudlog to award trackers or event tools that are not built in.

## 2. Phase 1 Hooks You Will Use

Current QSO hooks:

- `qso.filter.before_save`
- `qso.action.after_save`
- `qso.action.after_edit`

Dispatch points in core:

- Create flow applies `qso.filter.before_save` before insert.
- Add flow dispatches `qso.action.after_save` after insert and cache clear.
- Edit flow dispatches `qso.action.after_edit` after update and cache clear.

## 3. Which Hook Does What In Sync Workflows

### 3.1 `qso.filter.before_save` (optional, pre-sync normalization)

- Called before QSO data is persisted.
- Receives `payload` and `context`.
- Should return:
  - modified payload array to change data
  - same payload to keep data unchanged
  - `null` to leave current payload as-is

Typical sync use:

- trim and uppercase text fields
- set defaults when a value is missing
- normalize contest exchange fields

### 3.2 `qso.action.after_save` and `qso.action.after_edit` (main sync triggers)

- Called after database persistence.
- Receive `payload` and `context`.
- Return value is ignored.

Typical sync use:

- call external APIs
- send webhooks
- update remote records

## 4. Payload and Context You Can Send Externally

These are the shapes currently passed by core dispatch points.

### 4.1 `qso.filter.before_save`

Payload: QSO data array before insert.

Context:

- `source`: `manual`
- `station_id`: station id used for the QSO

### 4.2 `qso.action.after_save`

Payload:

- `qso_id`: inserted QSO id
- `qso`: inserted QSO data array

Context:

- `source`: `manual` or `import`
- `station_id`: station id from QSO payload

### 4.3 `qso.action.after_edit`

Payload:

- `qso_id`: edited QSO id
- `qso`: updated QSO data array

Context:

- `station_id`: station id for the edited QSO

## 5. Minimal Third-Party Sync Plugin Template

Folder layout:

```text
my-qso-sync-plugin/
  manifest.json
  Plugin.php
  README.md
```

`manifest.json` example:

```json
{
  "slug": "my-qso-sync-plugin",
  "name": "My QSO Sync Plugin",
  "version": "1.0.0",
  "description": "Syncs new and edited QSOs to an external system.",
  "entry": "Plugin.php",
  "class": "MyQsoSyncPlugin",
  "hooks": {
    "qso.filter.before_save": "beforeSave",
    "qso.action.after_save": "afterSave",
    "qso.action.after_edit": "afterEdit"
  }
}
```

`Plugin.php` example:

```php
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MyQsoSyncPlugin {

    private $CI;

    public function __construct($ci)
    {
        $this->CI = $ci;
    }

    public function beforeSave($payload, $context = array())
    {
        if (!is_array($payload)) {
            return $payload;
        }

        if (isset($payload['COL_CALL'])) {
            $payload['COL_CALL'] = strtoupper(trim((string)$payload['COL_CALL']));
        }

        if (isset($payload['COL_GRIDSQUARE'])) {
            $payload['COL_GRIDSQUARE'] = strtoupper(trim((string)$payload['COL_GRIDSQUARE']));
        }

        return $payload;
    }

    public function afterSave($payload, $context = array())
    {
        $qso_id = isset($payload['qso_id']) ? (int)$payload['qso_id'] : 0;
        $qso = isset($payload['qso']) && is_array($payload['qso']) ? $payload['qso'] : array();
        $source = isset($context['source']) ? (string)$context['source'] : 'unknown';

        // Replace with your real HTTP client call.
        $call = isset($qso['COL_CALL']) ? (string)$qso['COL_CALL'] : '';
        log_message('info', 'MyQsoSyncPlugin afterSave qso_id=' . $qso_id . ' source=' . $source . ' call=' . $call);
    }

    public function afterEdit($payload, $context = array())
    {
        $qso_id = isset($payload['qso_id']) ? (int)$payload['qso_id'] : 0;
        $qso = isset($payload['qso']) && is_array($payload['qso']) ? $payload['qso'] : array();

        // Replace with your real HTTP client call.
        $call = isset($qso['COL_CALL']) ? (string)$qso['COL_CALL'] : '';
        log_message('info', 'MyQsoSyncPlugin afterEdit qso_id=' . $qso_id . ' call=' . $call);
    }
}
```

## 6. Install and Enable

1. Zip plugin contents.
2. Go to Admin > Plugin Manager.
3. Upload and install zip.
4. Enable the plugin.
5. Create and edit QSOs to verify behavior.

## 7. Testing Strategy For External Sync

Use a small test matrix:

1. Manual create QSO:
Check outbound payload contains expected normalized values.
2. Import create QSO:
Check after-save source context reports `import` and still syncs.
3. Edit QSO:
Check after-edit re-sync sends updated fields.
4. Temporary API outage:
Confirm QSO save still succeeds and plugin logs sync failure.

Also review logs in application logs for plugin messages and any auto-disable events.

## 8. Reliability and Security Notes

- Plugin code runs in-process, so it must be trusted.
- Hook exceptions are caught and plugin may be auto-disabled.
- Plugin Manager enforces read-only and command-execution policy checks.
- Keep hooks fast to avoid slowing down QSO saves.

Recommended practices:

- Use timeouts and fail-safe handling for external API calls.
- Keep constructor side-effect free.
- Validate all array keys before use.
- Prefer idempotent remote writes (same QSO sent twice should be safe).

## 9. Recommended Sync Pattern

Use this baseline pattern for robust integrations:

1. Build a stable external key (for example `qso_id` + `station_id`).
2. Send create/update through one upsert-style endpoint where possible.
3. On failure, log details and return without throwing fatal errors.
4. Keep request payload minimal and explicit.
5. Reuse the same mapping logic for both after-save and after-edit.

## 10. Good First Third-Party Integrations

- Club leaderboard API sync.
- Team notification webhook on new DXCC.
- Event scoring backend sync.
- Custom analytics pipeline webhook.
