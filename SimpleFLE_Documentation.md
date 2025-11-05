# Simple Fast Log Entry (SimpleFLE)

SimpleFLE is a fast, efficient way to log multiple QSOs in Cloudlog using a simple text-based syntax. It's designed for rapid data entry, particularly useful after contests or operating events where you have many contacts to log.

## Getting Started

Navigate to **Logbook > Simple Fast Log Entry** in Cloudlog to access SimpleFLE.

## Basic Syntax

Each QSO is entered on a new line in a simple, space-separated format. The parser is intelligent and recognizes different data types automatically.

### Minimum Required Information

At minimum, you need:
- **Callsign** (e.g., `W9LR`, `K8YSE`, `N8HI`)
- **Band** (e.g., `20m`, `2m`, `70cm`) OR **Frequency** (e.g., `14.250`, `145.500`)
- **Mode** (e.g., `SSB`, `CW`, `FT8`, `FM`)

### Basic Example

```
20m SSB
1522 W9LR 59 59
1535 K8YSE 59 59
1540 N8HI 59 59
```

**Explanation:**
- First line sets the band (`20m`) and mode (`SSB`)
- Following lines log QSOs at specified times with callsigns and signal reports

## Date and Time

### Date Format

```
2025-11-05
```

If no date is specified, today's date is used.

### Time Format

Use 24-hour format (HHMM):
```
1522   (3:22 PM UTC)
1535   (3:35 PM UTC)
```

Times are assumed to be UTC.

## Band and Frequency

### Using Bands

```
20m      (20 meters)
2m       (2 meters / 144 MHz)
70cm     (70 centimeters / 432 MHz)
6m       (6 meters)
```

### Using Frequencies

```
14.250   (automatically sets band to 20m)
145.500  (automatically sets band to 2m)
7.125    (automatically sets band to 40m)
```

## Modes

Common modes include:
- **SSB** (Single Sideband - LSB/USB determined by band)
- **CW** (Morse Code)
- **FM** (Frequency Modulation)
- **FT8** (Digital mode)
- **FT4** (Digital mode)
- **RTTY** (Radio Teletype)
- **PSK31** (Phase Shift Keying)

## RST Reports

### Format

Signal reports can be entered as:
- **RST Sent / RST Received**: `59 59` or `599 599`
- **Default**: If omitted, `59` (SSB) or `599` (CW/Digital) is used

### Examples

```
1522 W9LR 59 58        (sent 59, received 58)
1535 K8YSE 599 589     (CW reports)
1540 N8HI              (defaults to 59 59)
```

## Satellite QSOs

SimpleFLE supports satellite contacts with automatic frequency lookup.

### Basic Satellite Syntax

```
sat
rs-44 v/u
1522 w9lr fn02
1535 k8yse
1540 n8hi
```

**Explanation:**
- `sat` - Sets propagation mode to satellite
- `rs-44` - Satellite name (case-insensitive)
- `v/u` - Satellite mode (VHF uplink / UHF downlink)
- Frequencies are auto-populated from satellite database
- `fn02` - Optional gridsquare

### Satellite Modes

Common satellite modes:
- **V/U** - 2m uplink, 70cm downlink
- **U/V** - 70cm uplink, 2m downlink
- **L/S** - L-band uplink, S-band downlink
- **V** - V-band only

### Supported Satellites

SimpleFLE recognises many satellites including:
- **RS-44** (Radio Sport 44)
- **AO-7** (AMSAT OSCAR 7)
- **FO-29** (Fuji OSCAR 29)
- **SO-50** (Saudi OSCAR 50)
- **ISS** or **ARISS** (International Space Station)

### Visual Feedback

When you enter a satellite name, SimpleFLE displays:
- ✅ **Green alert** - Satellite recognised, showing available modes
- ⚠️ **Yellow warning** - Satellite not found in database

The selected mode is highlighted with a checkmark.

## Gridsquares

Maidenhead locator squares can be included for satellite or VHF/UHF contacts:

```
1522 W9LR EN52
1535 K8YSE EN82qr
1540 N8HI EN81
```

Supports 4, 6, or 8 character gridsquares (e.g., `EN52`, `EN52qr`, `EN52qr12`).

## SOTA/IOTA/POTA/WWFF References

Add summit, island, park, or WWFF references:

```
20m SSB
1522 W9LR 59 59 W4C/NG-001
1535 K8YSE 59 59 NA-005
1540 N8HI 59 59 K-0817
```

References can be:
- **SOTA**: `W4C/NG-001`, `G/LD-001`
- **IOTA**: `NA-005`, `EU-005`
- **POTA**: `K-0817`, `VE-0001`
- **WWFF**: `KFF-0001`, `ONFF-0001`

## Complete Example

```
2025-11-05
20m SSB
1522 W9LR 59 59 W4C/NG-001
1535 K8YSE 59 58
1540 N8HI 59 59

40m CW
1605 DL1ABC 599 579
1612 G4XYZ 599 599

sat
ao-7 v/u
1850 W9LR EN52
1855 K8YSE EN82
```

## Changing Band/Mode Mid-Session

You can switch bands and modes at any time:

```
20m SSB
1522 W9LR
1535 K8YSE

40m CW
1540 N8HI
1545 DL1ABC

2m FM
1600 W9LR
```

## Tips and Best Practices

1. **Set band/mode first** - This applies to all following QSOs until changed
2. **Use clear spacing** - Separate items with spaces for clarity
3. **Time in sequence** - Enter QSOs in chronological order
4. **Check the preview** - Review the QSO list table before saving
5. **Clear session** - Use "Clear Logging Session" to start fresh
6. **Save often** - Click "Save in Cloudlog" to commit your QSOs
7. **Satellite contacts** - Wait for visual feedback to confirm satellite recognition

## Station Profile and Operator

Before logging:
1. **Select Station Profile** - Choose your station location/callsign
2. **Enter Operator** - Specify the operator callsign if different from station

These fields are at the top of the SimpleFLE page.

## Buttons

- **🔄 Reload QSO List** - Refresh the preview (restores from browser cache)
- **💾 Save in Cloudlog** - Commit all QSOs to your logbook
- **🗑️ Clear Logging Session** - Delete all entries and start over
- **❓ Syntax Help** - Show syntax examples and tips

## Mobile Usage

SimpleFLE is mobile-friendly:
- Table scrolls horizontally on narrow screens
- Buttons stack vertically on mobile
- All features work on touch devices
- Swipe left/right to view all QSO columns

## Validation

SimpleFLE validates your entries and warns you about:
- ❌ Missing band or mode
- ❌ Missing time
- ❌ Invalid date format
- ⚠️ Unrecognised satellite name

Fix any warnings before clicking "Save in Cloudlog".

## Keyboard Shortcuts

- **Enter** - Move to next line (new QSO)
- Real-time satellite feedback as you type
- Auto-uppercase for callsigns and modes

## Advanced Features

### Auto-Frequency Population

When entering satellite QSOs, SimpleFLE automatically:
- Looks up uplink and downlink frequencies from satellite database
- Calculates bands from frequencies
- Handles inverting transponders (LSB/USB → SSB conversion)

### Session Persistence

Your work is automatically saved to browser localStorage:
- Reload the page without losing data
- Resume logging after browser restart
- Cleared only when you click "Clear Logging Session"

## Troubleshooting

**Satellite not recognised?**
- Check spelling of satellite name
- Try common variations (e.g., `ISS` or `ARISS`)
- Ensure satellite database is up to date

**Columns not aligning on mobile?**
- Clear browser cache and reload page
- Click "Clear Logging Session" to reset cached table HTML

**Time validation errors?**
- Use 24-hour format (HHMM)
- Ensure times are sequential
- Use valid times (0000-2359)