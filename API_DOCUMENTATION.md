# Cloudlog API Documentation

## Overview

Cloudlog provides a comprehensive REST API that allows external applications to interact with your logbook data, station information, and various Cloudlog features. This API supports QSO logging, data retrieval, statistics, and integration with third-party applications.

## Table of Contents

- [Authentication](#authentication)
- [General API Information](#general-api-information)
- [Authentication Endpoints](#authentication-endpoints)
- [Station Management](#station-management)
- [QSO Management](#qso-management)
- [Logbook Operations](#logbook-operations)
- [Statistics](#statistics)
- [Lookup Services](#lookup-services)
- [Radio Control](#radio-control)
- [Public Data Access](#public-data-access)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)

## Authentication

All API endpoints require a valid Cloudlog API key unless specified as public. You can generate API keys in Cloudlog under **API Management**.

**API Key Types:**
- **Read-only (`r`)**: Can read data but not modify anything
- **Write (`w`)**: Can create, update, and delete data
- **Read-write (`rw`)**: Full access to read and write operations

**API Key Format:** `cl6897ba3088c7b` (example)

## General API Information

**Base URL:** `http://your-cloudlog-url/index.php/api/`

**Content Types:**
- **Request:** `application/json` (for POST requests)
- **Response:** `application/json`

**HTTP Methods:**
- `GET` - Retrieve data
- `POST` - Create/update data
- `PUT` - Update data
- `DELETE` - Remove data

## Authentication Endpoints

### Check API Key Validity

**Endpoint:** `GET /api/auth/{key}`

**Description:** Validates an API key and returns the access level.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/auth/cl6897ba3088c7b
```

**Response:**
```json
{
  "status": "valid",
  "rights": "rw"
}
```

**Error Response:**
```json
{
  "status": "invalid"
}
```

---

### Check Authentication Status

**Endpoint:** `GET /api/check_auth/{key}`

**Description:** Extended authentication check that provides detailed information about API key permissions.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/check_auth/cl6897ba3088c7b
```

**Response:**
```json
{
  "status": "valid",
  "rights": "rw"
}
```

## Station Management

### Get Station Information

**Endpoint:** `GET /api/station_info/{key}`

**Description:** Retrieves all station profiles associated with the API key's user account.

**Requirements:** Read access (`r` or `rw`)

**URL Example:**
```
http://your-cloudlog-url/index.php/api/station_info/cl6897ba3088c7b
```

**Response:**
```json
[
  {
    "station_id": "1",
    "station_profile_name": "Home Station",
    "station_gridsquare": "FN20bb",
    "station_callsign": "N0CALL",
    "station_active": "1"
  },
  {
    "station_id": "2",
    "station_profile_name": "Portable",
    "station_gridsquare": "FN30cc",
    "station_callsign": "N0CALL/P",
    "station_active": "0"
  }
]
```

**Error Response:**
```json
{
  "status": "failed",
  "reason": "missing or invalid api key"
}
```

## QSO Management

### Add QSO(s)

**Endpoint:** `POST /api/qso`

**Description:** Import QSO data in ADIF format or add individual QSOs.

**Requirements:** Write access (`w` or `rw`)

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "key": "cl6897ba3088c7b",
  "station_profile_id": "1",
  "type": "adif",
  "string": "<call:4>W1AW<qso_date:8>20241004<time_on:4>1430<band:3>20M<mode:3>SSB<rst_sent:2>59<rst_rcvd:2>59<eor>"
}
```

**Alternative Single QSO Format:**
```json
{
  "key": "cl6897ba3088c7b",
  "station_profile_id": "1",
  "type": "qso",
  "call": "W1AW",
  "qso_date": "20241004",
  "time_on": "1430",
  "band": "20M",
  "mode": "SSB",
  "rst_sent": "59",
  "rst_rcvd": "59"
}
```

**Response:**
```json
{
  "status": "created",
  "type": "adif",
  "string": "<call:4>W1AW...",
  "imported_count": 1,
  "messages": []
}
```

**Error Response:**
```json
{
  "status": "failed",
  "reason": "missing api key"
}
```

## Logbook Operations

### Check if Callsign Worked Before

**Endpoint:** `POST /api/logbook_check_callsign`

**Description:** Check if a specific callsign has been worked before in a given logbook, optionally filtered by band, mode, or date.

**Requirements:** Valid API key

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "key": "cl6897ba3088c7b",
  "logbook_public_slug": "my-logbook",
  "callsign": "W1AW",
  "band": "20M",
  "mode": "SSB"
}
```

**Response:**
```json
{
  "workedBefore": true,
  "confirmed": {
    "qsl": true,
    "lotw": false,
    "eqsl": true,
    "qrz": false
  }
}
```

---

### Check Grid Square Status

**Endpoint:** `POST /api/logbook_check_grid`

**Description:** Check if a specific grid square has been worked before in a given logbook.

**Requirements:** Valid API key

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "key": "cl6897ba3088c7b",
  "logbook_public_slug": "my-logbook",
  "gridsquare": "FN20bb",
  "band": "20M",
  "mode": "SSB"
}
```

**Response:**
```json
{
  "workedBefore": true,
  "confirmed": {
    "qsl": false,
    "lotw": true,
    "eqsl": false,
    "qrz": false
  }
}
```

---

### Check Country Status

**Endpoint:** `POST /api/logbook_check_country`

**Description:** Check if a specific country (DXCC entity) has been worked before in a given logbook.

**Requirements:** Valid API key

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "key": "cl6897ba3088c7b",
  "logbook_public_slug": "my-logbook",
  "callsign": "G0ABC",
  "band": "20M",
  "mode": "SSB",
  "type": "terrestrial"
}
```

**Response:**
```json
{
  "workedBefore": true,
  "confirmed": {
    "qsl": true,
    "lotw": true,
    "eqsl": false,
    "qrz": false
  }
}
```

## Statistics

### Get QSO Statistics

**Endpoint:** `GET /api/statistics/{key}`

**Description:** Retrieve basic QSO statistics for the user's logbook.

**Requirements:** Valid API key

**URL Example:**
```
http://your-cloudlog-url/index.php/api/statistics/cl6897ba3088c7b
```

**Response:**
```json
{
  "Today": 5,
  "total_qsos": 1247,
  "month_qsos": 89,
  "year_qsos": 432
}
```

## Lookup Services

### Callsign Lookup

**Endpoint:** `POST /api/lookup`

**Description:** Comprehensive callsign lookup that returns DXCC information, previous QSO data, and available callbook data.

**Requirements:** User must be logged in (not API key based)

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "callsign": "W1AW"
}
```

**Response:**
```json
{
  "callsign": "W1AW",
  "dxcc": "United States",
  "dxcc_lat": "42.3601",
  "dxcc_long": "-71.0589",
  "dxcc_cqz": "5",
  "name": "Hiram Percy Maxim Memorial Station",
  "gridsquare": "FN42hg",
  "location": "Newington, CT",
  "iota_ref": "",
  "state": "CT",
  "us_county": "Hartford",
  "qsl_manager": "",
  "bearing": "45",
  "workedBefore": true,
  "lotw_member": true,
  "suffix_slash": ""
}
```

---

### Grid Square to Lat/Lng Conversion

**Endpoint:** `GET /api/qralatlng/{grid_square}`

**Description:** Convert a Maidenhead grid square to latitude and longitude coordinates.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/qralatlng/FN20bb
```

**Response:**
```json
{
  "lat": 40.625,
  "lng": -74.375
}
```

## Radio Control

### Update Radio Status

**Endpoint:** `POST /api/radio`

**Description:** Update radio status information (frequency, mode, etc.) from external applications.

**Requirements:** Valid API key

**Content-Type:** `application/json`

**Request Body:**
```json
{
  "key": "cl6897ba3088c7b",
  "radio": "FT-991A",
  "frequency": "14074000",
  "mode": "USB",
  "timestamp": "2024/10/04 16:47"
}
```

**Response:**
```json
{
  "status": "success"
}
```

**Note:** For detailed radio commands functionality, see the separate [RADIO_COMMANDS_API.md](RADIO_COMMANDS_API.md) documentation.

## Public Data Access

### Get Recent QSOs from Public Logbook

**Endpoint:** `GET /api/recent_qsos/{public_slug}/{limit}`

**Description:** Retrieve recent QSOs from a public logbook. This endpoint does not require authentication.

**Parameters:**
- `public_slug` (required): Public slug identifier for the logbook
- `limit` (optional): Number of QSOs to return (default: 10, max: 50)

**URL Example:**
```
http://your-cloudlog-url/index.php/api/recent_qsos/my-public-logbook/5
```

**Response:**
```json
{
  "qsos": [
    {
      "date": "2024-10-04",
      "time": "14:30",
      "callsign": "W1AW",
      "mode": "SSB",
      "band": "20M",
      "rst_sent": "59",
      "rst_rcvd": "59",
      "country": "United States",
      "gridsquare": "FN42hg",
      "name": "John",
      "qth": "Newington, CT"
    }
  ],
  "count": 1,
  "logbook_slug": "my-public-logbook"
}
```

**Error Response:**
```json
{
  "status": "failed",
  "reason": "logbook not found"
}
```

## Error Handling

### Standard Error Response Format

All API endpoints follow a consistent error response format:

```json
{
  "status": "failed",
  "reason": "descriptive error message"
}
```

### Common HTTP Status Codes

- **200 OK** - Request successful
- **201 Created** - Resource created successfully
- **400 Bad Request** - Invalid request format or missing required parameters
- **401 Unauthorized** - Invalid or missing API key
- **404 Not Found** - Resource not found
- **500 Internal Server Error** - Server error

### Common Error Scenarios

1. **Invalid API Key:**
```json
{
  "status": "failed",
  "reason": "missing or invalid api key"
}
```

2. **Missing Required Fields:**
```json
{
  "status": "failed",
  "reason": "missing fields"
}
```

3. **Logbook Not Found:**
```json
{
  "status": "failed",
  "reason": "logbook not found"
}
```

4. **Invalid JSON Format:**
```json
{
  "status": "failed",
  "reason": "wrong JSON"
}
```

## Rate Limiting

Cloudlog does not currently implement strict rate limiting, but it's recommended to:

- **Respect server resources** - Don't make excessive concurrent requests
- **Implement backoff** - Add delays between requests if making many calls
- **Cache responses** - Store frequently accessed data locally when possible
- **Batch operations** - Use bulk endpoints when available (like ADIF imports)

## Best Practices

### Security
1. **Protect API Keys** - Never expose API keys in client-side code
2. **Use HTTPS** - Always use encrypted connections in production
3. **Rotate Keys** - Regularly regenerate API keys
4. **Least Privilege** - Use read-only keys when write access isn't needed

### Performance
1. **Minimize Requests** - Use bulk operations when possible
2. **Cache Data** - Store frequently accessed data locally
3. **Handle Errors** - Implement proper error handling and retry logic
4. **Validate Input** - Check data before sending to API

### Integration
1. **Test Thoroughly** - Test all error scenarios and edge cases
2. **Monitor Usage** - Track API usage and response times
3. **Document Integration** - Keep your integration well documented
4. **Stay Updated** - Check for API updates and new features

## Example Integration Code

### Python Example

```python
import requests
import json

class CloudlogAPI:
    def __init__(self, base_url, api_key):
        self.base_url = base_url.rstrip('/')
        self.api_key = api_key
    
    def get_station_info(self):
        url = f"{self.base_url}/index.php/api/station_info/{self.api_key}"
        response = requests.get(url)
        return response.json()
    
    def add_qso(self, qso_data):
        url = f"{self.base_url}/index.php/api/qso"
        qso_data['key'] = self.api_key
        response = requests.post(url, json=qso_data)
        return response.json()
    
    def check_worked_before(self, logbook_slug, callsign, band=None, mode=None):
        url = f"{self.base_url}/index.php/api/logbook_check_callsign"
        data = {
            'key': self.api_key,
            'logbook_public_slug': logbook_slug,
            'callsign': callsign
        }
        if band:
            data['band'] = band
        if mode:
            data['mode'] = mode
        
        response = requests.post(url, json=data)
        return response.json()

# Usage
api = CloudlogAPI('http://your-cloudlog-url', 'your-api-key')
stations = api.get_station_info()
print(f"Found {len(stations)} stations")
```

### JavaScript/Node.js Example

```javascript
class CloudlogAPI {
    constructor(baseUrl, apiKey) {
        this.baseUrl = baseUrl.replace(/\/$/, '');
        this.apiKey = apiKey;
    }
    
    async getStationInfo() {
        const url = `${this.baseUrl}/index.php/api/station_info/${this.apiKey}`;
        const response = await fetch(url);
        return await response.json();
    }
    
    async addQso(qsoData) {
        const url = `${this.baseUrl}/index.php/api/qso`;
        qsoData.key = this.apiKey;
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(qsoData)
        });
        
        return await response.json();
    }
    
    async getStatistics() {
        const url = `${this.baseUrl}/index.php/api/statistics/${this.apiKey}`;
        const response = await fetch(url);
        return await response.json();
    }
}

// Usage
const api = new CloudlogAPI('http://your-cloudlog-url', 'your-api-key');

api.getStationInfo()
    .then(stations => console.log(`Found ${stations.length} stations`))
    .catch(error => console.error('Error:', error));
```

## Changelog

### Version 2.7.0
- Added comprehensive API documentation
- Improved error handling consistency
- Added public logbook recent QSOs endpoint
- Enhanced radio control APIs

### Future Enhancements

Planned improvements for future versions:
- OAuth 2.0 authentication support
- GraphQL endpoint
- WebSocket real-time updates
- Enhanced bulk operations
- Advanced filtering and pagination
- Rate limiting implementation

---

**Note:** This documentation covers the standard Cloudlog API endpoints. For radio commands functionality, please refer to the separate [RADIO_COMMANDS_API.md](RADIO_COMMANDS_API.md) documentation which provides detailed information about the radio control command system.

For the latest API updates and additional endpoints, please check the Cloudlog GitHub repository and release notes.