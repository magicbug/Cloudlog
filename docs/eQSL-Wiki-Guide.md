# eQSL

The eQSL functions synchronise your log with the state in eQSL.cc, so Cloudlog can handle your eQSL tasks without you having to keep logging in to eQSL manually.

This page keeps the original workflow and adds the new mapping setup.

## Basic Setup

### Legacy setup (still supported)

- Add your eQSL login details in your Cloudlog user profile.
- Add an eQSL QTH nickname in each station profile you use.

### Recommended setup (new)

Use **eQSL > Mappings** and add one mapping per station location.

Each mapping contains:

- station location
- eQSL username
- eQSL password
- eQSL QTH nickname
- enabled flag
- preferred download flag

If you use the same eQSL account for several station locations, enter the same username and leave password blank on additional mappings. Cloudlog will reuse the saved password for that username.

### How Cloudlog decides what to use

1. If at least one active mapping exists, Cloudlog uses mappings first.
2. If no active mappings exist, Cloudlog falls back to legacy profile fields.

Tip: Keep nickname text exactly the same as eQSL.cc, including spaces and capitals.

## Upload QSOs

When you open **Upload QSOs**, Cloudlog shows QSOs not yet sent to eQSL.

Press **Upload QSOs** and successful records are marked as sent.

### In mapping mode

- Cloudlog uploads using credentials from the mapping.
- Cloudlog sends the mapping QTH nickname with each QSO.

### In legacy mode

- Cloudlog uploads using user profile eQSL credentials.
- Cloudlog uses station profile eQSL nickname.

### Debugging issues

If uploads fail, enable debugging in `application/config/config.php` and check logs in `application/logs`.

Also check:

1. Username and password are correct.
2. Mapping is enabled.
3. QTH nickname matches eQSL.cc exactly.

## Importing

Import can work in two ways:

1. Upload an exported Inbox ADIF file from eQSL.cc.
2. Pull directly from eQSL (automatic fetch).

### Direct fetch in mapping mode

- Cloudlog fetches confirmations per active mapping.
- It uses mapping password and mapping QTH nickname.

### Direct fetch in legacy mode

- Cloudlog uses your user profile eQSL password.
- It uses station profile nickname.

## View Digital Cards

Cloudlog lets you open received eQSL cards from the logbook and caches card images locally for faster viewing.

For card image retrieval by station:

1. Cloudlog tries the preferred mapping for that station.
2. If no mapping is available, it falls back to legacy password.

## Tools

### Mark All QSOs as Sent to eQSL

Use this when you have uploaded ADIF manually outside Cloudlog and want Cloudlog to mark records as sent.

This is helpful on fresh installs with large historical logs.

### Mappings page

Use **eQSL > Mappings** to:

- add a mapping
- edit a mapping
- disable a mapping
- choose preferred mapping for inbox download

Good practice:

1. Keep one active mapping per station identity unless you need more.
2. Keep one preferred download mapping per station.

## Cron Job

Example scheduled job:

```bash
# Upload/download QSOs to/from eQSL
9 */6 * * * curl --silent https://<URL-To-Cloudlog>/index.php/eqsl/sync &>/dev/null
```

Cron behaviour:

1. Uses mapping mode when mappings exist.
2. Upload runs for active mappings.
3. Download runs for mappings marked preferred for download.
4. Uses legacy mode only if no mappings exist.

## See More

- [LoTW Import & Export Documentation](https://github.com/magicbug/Cloudlog/wiki/LoTW-Import-&-Export-Documentation)

## Quick Troubleshooting Checklist

1. Mapping enabled?
2. Username and password correct?
3. QTH nickname exact match with eQSL.cc?
4. Preferred download flag set where needed?
5. Still in fallback mode because no active mappings?
6. Using same eQSL username on a new mapping but left password blank before saving the first mapping for that username?
