# Cloudlog Radio Commands API Documentation

## Overview

The Cloudlog Radio Commands API allows external applications (like Aurora) to send radio control commands to Cloudlog and have them executed by connected desktop applications. This system uses a queue-based approach where commands are stored in the database and polled by desktop apps.

## Table of Contents

- [Authentication](#authentication)
- [API Endpoints](#api-endpoints)
- [Command Types](#command-types)
- [Integration Examples](#integration-examples)
- [Error Handling](#error-handling)
- [Best Practices](#best-practices)

## Authentication

All API endpoints require a valid Cloudlog API key. You can generate an API key in Cloudlog under **API Management**.

**API Key Format:** `cl6897ba3088c7b` (example)

## API Endpoints

### 1. Submit Radio Data (CAT Updates)

**Endpoint:** `POST /api/radio`

**Description:** Submit radio frequency, mode, and other CAT data to Cloudlog. This is used by desktop applications (like Aurora) to update Cloudlog with current radio status.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/radio
```

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "key": "cl6897ba3088c7b",
  "radio": "Dummy Rig",
  "frequency": 14074000,
  "mode": "USB",
  "power": 100,
  "timestamp": "2025/10/04 16:47"
}
```

**Required Fields:**
- `key` - Your API key
- `radio` - Radio name (should match name in CAT table)

**Optional Fields:**
- `frequency` - Frequency in Hz
- `mode` - Operating mode (USB, LSB, CW, etc.)
- `power` - Power in watts
- `timestamp` - Timestamp in "YYYY/MM/DD HH:MM" format
- `sat_name` - Satellite name (for satellite operations)
- `prop_mode` - Propagation mode (SAT, etc.)
- `frequency_rx` - Receive frequency (for split operations)
- `mode_rx` - Receive mode (for split operations)

**Response:**
```json
{
  "status": "success"
}
```

**Error Response:**
```json
{
  "status": "failed",
  "reason": "missing api key"
}
```

### 2. Get Pending Commands by Radio Name

**Endpoint:** `GET /api/radio_commands_pending_by_name/{api_key}/{radio_name}`

**Description:** Retrieve pending commands for a specific radio using its name.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/radio_commands_pending_by_name/cl6897ba3088c7b/Dummy%20Rig
```

**Parameters:**
- `{api_key}` - Your Cloudlog API key
- `{radio_name}` - URL-encoded radio name (spaces become `%20`)

**Response:**
```json
{
  "status": "success",
  "commands": [
    {
      "id": "1",
      "radio_id": "1", 
      "radio_name": "Dummy Rig",
      "user_id": "6",
      "station_id": null,
      "command_type": "SET_FREQ",
      "frequency": "14074000",
      "mode": null,
      "bandwidth": null,
      "vfo": null,
      "status": "PENDING",
      "error_message": null,
      "created_at": "2025-10-04 10:31:04",
      "processed_at": null,
      "expires_at": "2025-10-04 11:01:04"
    }
  ],
  "count": 1,
  "radio_name": "Dummy Rig",
  "original_param": "Dummy%20Rig"
}
```

### 3. Get All Pending Commands

**Endpoint:** `GET /api/radio_commands_pending/{api_key}`

**Description:** Retrieve all pending commands for the authenticated user.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/radio_commands_pending/cl6897ba3088c7b
```

### 4. Update Command Status

**Endpoint:** `POST /api/radio_commands_update_status/{api_key}`

**Description:** Update the status of a command after execution.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/radio_commands_update_status/cl6897ba3088c7b
```

**Request Headers:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "command_id": 1,
  "status": "COMPLETED"
}
```

**With Error Message:**
```json
{
  "command_id": 1,
  "status": "FAILED",
  "error_message": "Radio not responding"
}
```

**Response:**
```json
{
  "status": "success",
  "updated": true
}
```

### 5. Get Command by ID

**Endpoint:** `GET /api/radio_commands_get/{api_key}/{command_id}`

**Description:** Retrieve details of a specific command.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/radio_commands_get/cl6897ba3088c7b/1
```

### 6. Queue New Command

**Endpoint:** `POST /api/radio_commands_queue/{api_key}`

**Description:** Create a new radio command programmatically.

**URL Example:**
```
http://your-cloudlog-url/index.php/api/radio_commands_queue/cl6897ba3088c7b
```

**Request Body (using radio name):**
```json
{
  "radio_name": "Dummy Rig",
  "command_type": "SET_FREQ",
  "frequency": 14074000,
  "mode": "USB"
}
```

**Request Body (using radio ID):**
```json
{
  "radio_id": 1,
  "command_type": "SET_FREQ", 
  "frequency": 14074000,
  "mode": "USB"
}
```

## Command Types

### SET_FREQ - Set Frequency
```json
{
  "command_type": "SET_FREQ",
  "frequency": 14074000
}
```
- **frequency**: Frequency in Hz (e.g., 14074000 for 14.074 MHz)

### SET_MODE - Set Mode  
```json
{
  "command_type": "SET_MODE",
  "mode": "USB"
}
```
- **mode**: Radio mode (USB, LSB, CW, AM, FM, FT8, FT4, etc.)

### SET_VFO - Switch VFO
```json
{
  "command_type": "SET_VFO", 
  "vfo": "A"
}
```
- **vfo**: VFO selection (A, B, C, etc.)

### SET_POWER - Set Power Level
```json
{
  "command_type": "SET_POWER",
  "power": 100
}
```
- **power**: Power in watts

## Radio Name URL Encoding

Radio names with spaces or special characters must be URL encoded:

| Radio Name | URL Encoded | API URL |
|------------|-------------|---------|
| `"Dummy Rig"` | `Dummy%20Rig` | `.../Dummy%20Rig` |
| `"IC-7300 Main"` | `IC-7300%20Main` | `.../IC-7300%20Main` |
| `"FT-991A"` | `FT-991A` | `.../FT-991A` |

## Typical Integration Workflow

The radio API system supports bidirectional communication between Cloudlog and desktop applications:

### **Desktop App → Cloudlog (Radio Status Updates)**
1. Desktop app reads current radio frequency/mode via hamlib
2. App submits data to `/api/radio` endpoint
3. Cloudlog updates CAT table and displays current radio status

### **Cloudlog → Desktop App (Radio Control)**
1. User sets frequency/mode in Cloudlog web interface
2. Cloudlog creates command in `radio_commands` table
3. Desktop app polls `/api/radio_commands_pending_by_name/{key}/{radio_name}`
4. Desktop app executes command via hamlib
5. Desktop app updates command status via `/api/radio_commands_update_status/{key}`

### **Complete Integration Flow**
```
┌─────────────────┐    CAT Data     ┌──────────────┐    Web Interface    ┌──────┐
│   Desktop App   │ ──────────────→ │   Cloudlog   │ ←─────────────────── │ User │
│    (Aurora)     │                 │              │                     │      │
│                 │ ←──────────────  │              │                     └──────┘
└─────────────────┘   Commands      └──────────────┘
        │                                   │
        │                                   │
        ▼                                   ▼
┌─────────────────┐                ┌──────────────┐
│     Radio       │                │   Database   │
│   (via hamlib)  │                │ - cat table  │
│                 │                │ - radio_cmd  │
└─────────────────┘                └──────────────┘
```

## Integration Examples

### Python Example (Aurora Integration)

```python
import requests
import urllib.parse
import time
from datetime import datetime

class CloudlogRadioAPI:
    def __init__(self, base_url, api_key):
        self.base_url = base_url.rstrip('/')
        self.api_key = api_key
    
    def get_pending_commands(self, radio_name):
        """Get pending commands for a radio"""
        encoded_name = urllib.parse.quote(radio_name)
        url = f"{self.base_url}/api/radio_commands_pending_by_name/{self.api_key}/{encoded_name}"
        
        response = requests.get(url)
        response.raise_for_status()
        return response.json()
    
    def update_command_status(self, command_id, status, error_message=None):
        """Update command status"""
        url = f"{self.base_url}/api/radio_commands_update_status/{self.api_key}"
        
        data = {
            "command_id": command_id,
            "status": status
        }
        
        if error_message:
            data["error_message"] = error_message
        
        response = requests.post(url, json=data)
        response.raise_for_status()
        return response.json()
    
    def submit_radio_data(self, radio_name, frequency=None, mode=None, power=None):
        """Submit current radio status to Cloudlog"""
        url = f"{self.base_url}/api/radio"
        
        data = {
            "key": self.api_key,
            "radio": radio_name,
            "timestamp": datetime.now().strftime("%Y/%m/%d %H:%M")
        }
        
        if frequency:
            data["frequency"] = frequency
        if mode:
            data["mode"] = mode
        if power:
            data["power"] = power
        
        response = requests.post(url, json=data)
        response.raise_for_status()
        return response.json()
    
    def execute_command(self, command):
        """Execute a radio command via hamlib"""
        command_id = command['id']
        
        try:
            # Update status to processing
            self.update_command_status(command_id, 'PROCESSING')
            
            # Execute command based on type
            if command['command_type'] == 'SET_FREQ':
                # hamlib_set_frequency(command['frequency'])
                print(f"Setting frequency to {command['frequency']} Hz")
                
            elif command['command_type'] == 'SET_MODE':
                # hamlib_set_mode(command['mode'])
                print(f"Setting mode to {command['mode']}")
                
            elif command['command_type'] == 'SET_VFO':
                # hamlib_set_vfo(command['vfo'])
                print(f"Setting VFO to {command['vfo']}")
                
            elif command['command_type'] == 'SET_POWER':
                # hamlib_set_power(command['power'])
                print(f"Setting power to {command['power']} watts")
            
            # Mark as completed
            self.update_command_status(command_id, 'COMPLETED')
            print(f"Command {command_id} completed successfully")
            
        except Exception as e:
            # Mark as failed with error message
            self.update_command_status(command_id, 'FAILED', str(e))
            print(f"Command {command_id} failed: {e}")

# Usage Example - Complete Bidirectional Integration
api = CloudlogRadioAPI("http://localhost", "cl6897ba3088c7b")
radio_name = "Dummy Rig"

def get_current_radio_status():
    """Get current radio status via hamlib"""
    # Replace with actual hamlib calls
    return {
        "frequency": 14074000,  # Hz
        "mode": "USB", 
        "power": 100
    }

# Main integration loop
last_status_update = 0
while True:
    try:
        # 1. Poll for pending commands from Cloudlog
        result = api.get_pending_commands(radio_name)
        
        if result['status'] == 'success' and result['count'] > 0:
            print(f"Found {result['count']} pending commands")
            
            for command in result['commands']:
                api.execute_command(command)
        
        # 2. Periodically submit radio status to Cloudlog (every 30 seconds)
        current_time = time.time()
        if current_time - last_status_update > 30:
            radio_status = get_current_radio_status()
            api.submit_radio_data(
                radio_name=radio_name,
                frequency=radio_status["frequency"],
                mode=radio_status["mode"], 
                power=radio_status["power"]
            )
            last_status_update = current_time
            print("Submitted radio status to Cloudlog")
        
        time.sleep(2)  # Poll every 2 seconds
        
    except Exception as e:
        print(f"Error: {e}")
        time.sleep(10)  # Wait longer on error
```

### JavaScript/Node.js Example

```javascript
const axios = require('axios');

class CloudlogRadioAPI {
    constructor(baseUrl, apiKey) {
        this.baseUrl = baseUrl.replace(/\/$/, '');
        this.apiKey = apiKey;
    }
    
    async getPendingCommands(radioName) {
        const encodedName = encodeURIComponent(radioName);
        const url = `${this.baseUrl}/api/radio_commands_pending_by_name/${this.apiKey}/${encodedName}`;
        
        const response = await axios.get(url);
        return response.data;
    }
    
    async updateCommandStatus(commandId, status, errorMessage = null) {
        const url = `${this.baseUrl}/api/radio_commands_update_status/${this.apiKey}`;
        
        const data = {
            command_id: commandId,
            status: status
        };
        
        if (errorMessage) {
            data.error_message = errorMessage;
        }
        
        const response = await axios.post(url, data);
        return response.data;
    }
    
    async executeCommand(command) {
        const commandId = command.id;
        
        try {
            // Update status to processing
            await this.updateCommandStatus(commandId, 'PROCESSING');
            
            // Execute command
            switch (command.command_type) {
                case 'SET_FREQ':
                    console.log(`Setting frequency to ${command.frequency} Hz`);
                    // await hamlibSetFrequency(command.frequency);
                    break;
                    
                case 'SET_MODE':
                    console.log(`Setting mode to ${command.mode}`);
                    // await hamlibSetMode(command.mode);
                    break;
                    
                case 'SET_VFO':
                    console.log(`Setting VFO to ${command.vfo}`);
                    // await hamlibSetVfo(command.vfo);
                    break;
                    
                case 'SET_POWER':
                    console.log(`Setting power to ${command.power} watts`);
                    // await hamlibSetPower(command.power);
                    break;
            }
            
            // Mark as completed
            await this.updateCommandStatus(commandId, 'COMPLETED');
            console.log(`Command ${commandId} completed successfully`);
            
        } catch (error) {
            // Mark as failed
            await this.updateCommandStatus(commandId, 'FAILED', error.message);
            console.log(`Command ${commandId} failed: ${error.message}`);
        }
    }
}

// Usage
const api = new CloudlogRadioAPI('http://localhost', 'cl6897ba3088c7b');
const radioName = 'Dummy Rig';

async function pollForCommands() {
    try {
        const result = await api.getPendingCommands(radioName);
        
        if (result.status === 'success' && result.count > 0) {
            console.log(`Found ${result.count} pending commands`);
            
            for (const command of result.commands) {
                await api.executeCommand(command);
            }
        }
        
    } catch (error) {
        console.error('Error:', error.message);
    }
}

// Poll every 2 seconds
setInterval(pollForCommands, 2000);
```

### C# Example

```csharp
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Text;
using System.Threading.Tasks;
using System.Web;
using Newtonsoft.Json;

public class CloudlogRadioAPI
{
    private readonly string baseUrl;
    private readonly string apiKey;
    private readonly HttpClient httpClient;
    
    public CloudlogRadioAPI(string baseUrl, string apiKey)
    {
        this.baseUrl = baseUrl.TrimEnd('/');
        this.apiKey = apiKey;
        this.httpClient = new HttpClient();
    }
    
    public async Task<dynamic> GetPendingCommands(string radioName)
    {
        string encodedName = HttpUtility.UrlEncode(radioName);
        string url = $"{baseUrl}/api/radio_commands_pending_by_name/{apiKey}/{encodedName}";
        
        var response = await httpClient.GetStringAsync(url);
        return JsonConvert.DeserializeObject(response);
    }
    
    public async Task<dynamic> UpdateCommandStatus(int commandId, string status, string errorMessage = null)
    {
        string url = $"{baseUrl}/api/radio_commands_update_status/{apiKey}";
        
        var data = new
        {
            command_id = commandId,
            status = status,
            error_message = errorMessage
        };
        
        var json = JsonConvert.SerializeObject(data);
        var content = new StringContent(json, Encoding.UTF8, "application/json");
        
        var response = await httpClient.PostAsync(url, content);
        var responseString = await response.Content.ReadAsStringAsync();
        
        return JsonConvert.DeserializeObject(responseString);
    }
}
```

## Error Handling

### HTTP Status Codes

- **200 OK** - Request successful
- **400 Bad Request** - Invalid parameters
- **401 Unauthorized** - Invalid API key
- **404 Not Found** - Command or radio not found
- **500 Internal Server Error** - Server error

### Error Response Format

```json
{
  "status": "failed",
  "reason": "error description"
}
```

### Common Error Messages

- `"unauthorized"` - Invalid API key
- `"missing command_id or status"` - Required parameters missing
- `"invalid status"` - Status must be PROCESSING, COMPLETED, or FAILED
- `"command not found"` - Command doesn't exist or doesn't belong to user
- `"radio not found"` - Radio name not found for user

## Radio API Summary

Cloudlog provides two main categories of radio-related APIs:

### **CAT Data Submission APIs**
| Endpoint | Purpose | Used By |
|----------|---------|---------|
| `POST /api/radio` | Submit current radio status | Desktop apps (Aurora) |

### **Radio Command APIs**
| Endpoint | Purpose | Used By |
|----------|---------|---------|
| `GET /api/radio_commands_pending/{key}` | Get all pending commands | Desktop apps |
| `GET /api/radio_commands_pending_by_name/{key}/{radio_name}` | Get pending commands for specific radio | Desktop apps (recommended) |
| `POST /api/radio_commands_update_status/{key}` | Update command execution status | Desktop apps |
| `GET /api/radio_commands_get/{key}/{command_id}` | Get specific command details | Desktop apps |
| `POST /api/radio_commands_queue/{key}` | Create new radio command | Web interface, external apps |

### **Data Flow**
- **CAT Data**: Radio → Desktop App → Cloudlog (via `/api/radio`)
- **Commands**: Web Interface → Cloudlog → Desktop App → Radio (via command APIs)

## Best Practices

### 1. Polling Frequency
- Poll every **2-5 seconds** for responsive performance
- Increase to **10-30 seconds** if no commands expected
- Use exponential backoff on errors

### 2. Error Handling
- Always update command status (even on failure)
- Provide detailed error messages
- Log errors for debugging

### 3. Command Expiration
- Commands expire after **30 minutes** by default
- Handle expired commands gracefully
- Don't execute expired commands

### 4. Status Updates
- Update to `PROCESSING` when starting execution
- Update to `COMPLETED` on success
- Update to `FAILED` on error with error message

### 5. Radio Name Handling
- Always URL encode radio names
- Trim whitespace from radio names
- Handle special characters properly

### 6. Connection Management
- Reuse HTTP connections where possible
- Implement timeout handling
- Handle network interruptions gracefully

## Frequency Conversion

Frequencies are stored in **Hz** in the database:

| Display (MHz) | Database (Hz) |
|---------------|---------------|
| 14.074 | 14074000 |
| 21.074 | 21074000 |
| 144.174 | 144174000 |

**Conversion:**
- **MHz to Hz**: `frequency_hz = frequency_mhz * 1000000`
- **Hz to MHz**: `frequency_mhz = frequency_hz / 1000000`

## Command Lifecycle

1. **PENDING** - Command created, waiting for execution
2. **PROCESSING** - Desktop app is executing command
3. **COMPLETED** - Command executed successfully
4. **FAILED** - Command execution failed
5. **EXPIRED** - Command expired before execution (automatic cleanup)

## Security Considerations

- Keep API keys secure and private
- Use HTTPS in production
- Validate all input parameters
- Implement rate limiting if needed
- Log all API access for auditing

## Troubleshooting

### Commands Not Appearing
1. Check API key validity
2. Verify radio name matches exactly (case-sensitive)
3. Ensure commands haven't expired
4. Check user permissions

### Authentication Issues
1. Verify API key is correct
2. Check API key belongs to correct user
3. Ensure API key has appropriate permissions

### Radio Name Issues
1. URL encode radio names with spaces
2. Check exact radio name in Cloudlog CAT table
3. Ensure radio belongs to API key user

---

## Support

For issues or questions:
1. Check Cloudlog logs in `application/logs/`
2. Verify database `radio_commands` table
3. Test with manual API calls using curl or Postman
4. Review this documentation for proper usage

---

**Last Updated:** October 4, 2025  
**API Version:** 1.0  
**Cloudlog Version:** Compatible with dev branch