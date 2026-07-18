# eQSL in Cloudlog (User Guide)

This guide is for radio amateurs using Cloudlog with eQSL.cc. It explains how to set things up, how uploads and downloads work, and what to check if something does not look right.

## What has changed

Cloudlog now uses eQSL mappings.

A mapping links:

- your station location
- your eQSL username and password
- your eQSL QTH nickname

This helps if you run more than one station profile, more than one callsign, or more than one eQSL identity.

## What you need before you start

1. A working eQSL.cc account.
2. QTH nickname(s) created in eQSL.cc.
3. Station location(s) already created in Cloudlog.

## Recommended setup

1. Open eQSL > Mappings in Cloudlog.
2. Add one mapping per station location.
3. Enter the eQSL username and password for that mapping.
4. Enter the exact QTH nickname from eQSL.cc.
5. Tick Enabled.
6. Tick Use for inbox download on the mapping you want used for downloads.

Tip: Match the nickname text exactly, including spaces and capital letters.

## How Upload QSOs works

When you use Upload QSOs:

1. Cloudlog checks for active mappings.
2. If mappings exist, Cloudlog uses them first.
3. Cloudlog uploads each QSO with the matching mapping credentials and QTH nickname.

If no active mappings exist, Cloudlog falls back to legacy settings in your user profile and station profile.

## How Download QSOs works

When you use Download QSOs:

1. Cloudlog checks for active mappings.
2. In mapping mode, it connects to eQSL using each active mapping.
3. It downloads confirmations and matches them to your logged QSOs.

If no active mappings exist, Cloudlog uses legacy settings.

## How cron sync works

If you run scheduled sync:

1. Cloudlog uses mappings when they exist.
2. Upload runs for active mappings.
3. Download runs for mappings marked Use for inbox download.
4. If no mappings exist, Cloudlog uses legacy behaviour.

## Legacy fallback

Cloudlog still supports the older setup for compatibility.

Fallback is used only when no active mappings are available.

Legacy fields are:

- eQSL username and password in your user profile
- eQSL nickname in station location profile

## Common problems and fixes

### Upload says auth failed

Check:

1. Mapping username is correct.
2. Mapping password is correct.
3. Mapping is Enabled.

### QSOs not routed to the correct QTH nickname

Check:

1. QTH nickname in mapping matches eQSL exactly.
2. You are uploading from the expected station location.

### Download does not find expected confirmations

Check:

1. The mapping is marked Use for inbox download.
2. The mapping nickname and callsign match what is on eQSL.

### eQSL menu not visible

Check:

1. You have at least one mapping, or legacy eQSL credentials.
2. Log out and back in after major profile changes.

## Good operating practice

1. Keep one active mapping per station identity unless you have a clear reason not to.
2. Keep one preferred download mapping per station.
3. Review mappings after changing callsign, QTH nickname, or station profile.

## Security notes

1. Your eQSL password is sensitive.
2. Limit access to your Cloudlog host and database backups.
3. Use HTTPS on your Cloudlog site.

## Quick checklist

1. Add mappings for each station location.
2. Confirm upload works.
3. Confirm download works.
4. Enable cron sync if you use scheduled automation.
5. Keep legacy fields only as backup while migrating.

## Terms used in this guide

- QSO: a contact record in your log
- QTH nickname: the nickname configured in your eQSL profile
- Mapping: the Cloudlog link between station location and eQSL identity
