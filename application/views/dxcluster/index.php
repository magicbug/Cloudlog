
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><?php echo $page_title; ?></h3>
                    <div>
                        <span id="connectionStatus" class="badge bg-secondary me-2">Connecting...</span>
                        <a href="<?php echo site_url('dxcluster/bandmap'); ?>" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-chart-line"></i> Bandmap
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters and Radio Control Panel -->
                    <div class="card mb-3">
                        <div class="card-body py-2">
                            <div id="radio_status"></div>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <!-- Radio Selection -->
                                <div class="d-flex align-items-center">
                                    <label for="radio" class="form-label mb-0 me-2" style="white-space: nowrap;">
                                        <i class="fas fa-broadcast-tower"></i> Radio:
                                    </label>
                                    <select class="form-select form-select-sm radios" id="radio" name="radio" style="min-width: 150px;">
                                        <option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
                                        <?php foreach ($radios->result() as $row) { ?>
                                            <option value="<?php echo $row->id; ?>" <?php if ($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                
                                <!-- Band Filter -->
                                <div class="d-flex align-items-center">
                                    <label for="bandFilter" class="form-label mb-0 me-2" style="white-space: nowrap;">
                                        <i class="fas fa-wave-square"></i> Band:
                                    </label>
                                    <select class="form-select form-select-sm" id="bandFilter" style="min-width: 120px;">
                                        <option value="all">All Bands</option>
                                        <option value="160m">160m</option>
                                        <option value="80m">80m</option>
                                        <option value="60m">60m</option>
                                        <option value="40m">40m</option>
                                        <option value="30m">30m</option>
                                        <option value="20m">20m</option>
                                        <option value="17m">17m</option>
                                        <option value="15m">15m</option>
                                        <option value="12m">12m</option>
                                        <option value="10m">10m</option>
                                        <option value="6m">6m</option>
                                        <option value="4m">4m</option>
                                        <option value="2m">2m</option>
                                        <option value="70cm">70cm</option>
                                        <option value="23cm">23cm</option>
                                        <option value="ghz">GHz+</option>
                                    </select>
                                </div>
                                
                                <!-- RBN Filter -->
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hideRbnSpots" checked>
                                    <label class="form-check-label" for="hideRbnSpots">
                                        <i class="fas fa-filter"></i> Hide RBN Spots
                                    </label>
                                </div>
                                
                                <!-- Info -->
                                <small class="text-muted ms-auto">
                                    <i class="fas fa-info-circle"></i> Filters active
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table id="dxSpotsTable" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Time</th>
                                    <th>DX Call</th>
                                    <th>Frequency</th>
                                    <th>Spotter</th>
                                    <th>Comment</th>
                                    <th>Age</th>
                                </tr>
                            </thead>
                            <tbody id="spotsTableBody">
                                <!-- Spots will be populated here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <div class="alert alert-info mb-2">
                            <strong><i class="fas fa-info-circle"></i> Legend:</strong>
                            <div class="mt-2">
                                <span class="me-3"><i class="fas fa-check text-success"></i> = Worked on this band</span>
                                <span class="me-3"><i class="fas fa-check text-info"></i> = Worked on another band</span>
                                <span class="me-3"><i class="fas fa-times text-secondary"></i> = Not worked</span>
                            </div>
                            <div class="mt-1">
                                <span class="me-3"><span class="badge bg-danger">New Band</span> = New DXCC on this band</span>
                                <span class="me-3"><span class="badge bg-warning text-dark">New DXCC</span> = New DXCC entity (never worked)</span>
                            </div>
                            <div class="mt-2 pt-2 border-top">
                                <small><i class="fas fa-mouse-pointer"></i> <strong>Tip:</strong> Click any frequency to QSY your radio (if selected above)</small>
                            </div>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Real-time DX cluster spots. Data refreshes automatically via WebSocket connection.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.connection-status.connected {
    background-color: #28a745 !important;
}

.connection-status.disconnected {
    background-color: #dc3545 !important;
}

.connection-status.connecting {
    background-color: #ffc107 !important;
    color: #212529 !important;
}

.spot-age {
    font-size: 0.8em;
}

.spot-fresh {
    background-color: rgba(40, 167, 69, 0.1) !important;
}

.spot-old {
    opacity: 0.7;
}

.frequency-link {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 500;
    font-family: 'Courier New', 'Monaco', 'Menlo', monospace;
    font-size: 0.95em;
}

.frequency-link:hover {
    color: #0a58ca;
    text-decoration: underline;
}

.frequency-cell {
    font-family: 'Courier New', 'Monaco', 'Menlo', monospace;
    font-size: 0.95em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let ws = null;
    let spots = new Map();
    let dataTable = null;
    let hideRbnSpots = true; // Default to hiding RBN spots
    let selectedBand = 'all'; // Default to all bands
    let workedStatus = {}; // Cache for worked status
    let checkWorkedTimeout = null;
    
    const connectionStatus = document.getElementById('connectionStatus');
    const spotsTableBody = document.getElementById('spotsTableBody');
    const hideRbnCheckbox = document.getElementById('hideRbnSpots');
    const bandFilterSelect = document.getElementById('bandFilter');
    
    // Load RBN filter preference from localStorage
    const savedRbnPreference = localStorage.getItem('cloudlog_hideRbnSpots');
    if (savedRbnPreference !== null) {
        hideRbnSpots = savedRbnPreference === 'true';
        hideRbnCheckbox.checked = hideRbnSpots;
    }
    
    // Load band filter preference from localStorage
    const savedBandPreference = localStorage.getItem('cloudlog_bandFilter');
    if (savedBandPreference !== null) {
        selectedBand = savedBandPreference;
        bandFilterSelect.value = selectedBand;
    }
    
    // Listen for checkbox changes
    hideRbnCheckbox.addEventListener('change', function() {
        hideRbnSpots = this.checked;
        localStorage.setItem('cloudlog_hideRbnSpots', hideRbnSpots.toString());
        updateTable();
    });
    
    // Listen for band filter changes
    bandFilterSelect.addEventListener('change', function() {
        selectedBand = this.value;
        localStorage.setItem('cloudlog_bandFilter', selectedBand);
        updateTable();
    });
    
    // Check if a spotter is an RBN spot
    function isRbnSpot(spotter) {
        if (!spotter) return false;
        // RBN spots have callsigns like DM5GG-# or DM5GG-1
        // Match any callsign ending with hyphen followed by # or digits
        const trimmedSpotter = spotter.trim().toUpperCase();
        return /\-[#\d]+$/.test(trimmedSpotter);
    }
    
    // Determine band from frequency (in kHz)
    function getBandFromFrequency(freqKhz) {
        const freq = parseFloat(freqKhz);
        
        if (freq >= 1800 && freq <= 2000) return '160m';
        if (freq >= 3500 && freq <= 4000) return '80m';
        if (freq >= 5250 && freq <= 5450) return '60m';
        if (freq >= 7000 && freq <= 7300) return '40m';
        if (freq >= 10100 && freq <= 10150) return '30m';
        if (freq >= 14000 && freq <= 14350) return '20m';
        if (freq >= 18068 && freq <= 18168) return '17m';
        if (freq >= 21000 && freq <= 21450) return '15m';
        if (freq >= 24890 && freq <= 24990) return '12m';
        if (freq >= 28000 && freq <= 29700) return '10m';
        if (freq >= 50000 && freq <= 54000) return '6m';
        if (freq >= 70000 && freq <= 71000) return '4m';
        if (freq >= 144000 && freq <= 148000) return '2m';
        if (freq >= 420000 && freq <= 450000) return '70cm';
        if (freq >= 1240000 && freq <= 1300000) return '23cm';
        if (freq >= 1000000) return 'ghz'; // 1 GHz and above
        
        return 'unknown';
    }
    
    // Check if spot matches selected band filter
    function matchesBandFilter(freqKhz) {
        if (selectedBand === 'all') return true;
        const spotBand = getBandFromFrequency(freqKhz);
        return spotBand === selectedBand;
    }
    
    // Initialize DataTable
    dataTable = $('#dxSpotsTable').DataTable({
        order: [[0, 'desc']], // Order by time (newest first)
        pageLength: 25,
        responsive: true,
        language: {
            url: getDataTablesLanguageUrl()
        },
        columnDefs: [
            {
                targets: 0, // Time column
                width: '8%',
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                targets: 1, // DX Call column
                width: '35%',
                render: function(data, type, row) {
                    if (type === 'display') {
                        const callsign = data;
                        const status = workedStatus[callsign];
                        let html = `<span class="dx-callsign" data-callsign="${callsign}">${callsign}</span>`;
                        
                        if (status) {
                            // Country name badge
                            if (status.country) {
                                html += `<span class="badge bg-secondary ms-2" title="Country: ${status.country}">${status.country}</span>`;
                            }
                            
                            // Worked status icon
                            if (status.worked_on_band) {
                                html += `<i class="fas fa-check text-success ms-2" title="Worked on ${status.band}"></i>`;
                            } else if (status.worked_overall) {
                                html += `<i class="fas fa-check text-info ms-2" title="Worked on another band"></i>`;
                            } else {
                                html += `<i class="fas fa-times text-secondary ms-2" title="Not worked yet"></i>`;
                            }
                            
                            // New country badges
                            if (status.dxcc && !status.dxcc_worked_on_band) {
                                html += `<span class="badge bg-danger ms-2" title="New DXCC entity on this band">New Band</span>`;
                            } else if (status.dxcc && !status.dxcc_worked_overall) {
                                html += `<span class="badge bg-warning text-dark ms-2" title="New DXCC entity">New DXCC</span>`;
                            }
                        }
                        
                        return html;
                    }
                    return data;
                }
            },
            {
                targets: 2, // Frequency column
                width: '12%',
                className: 'frequency-cell',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<a href="#" class="frequency-link" onclick="handleFrequencyClick('${data}')">${data}</a>`;
                    }
                    return data;
                }
            },
            {
                targets: 3, // Spotter column
                width: '10%'
            },
            {
                targets: 4, // Comment column
                width: '25%'
            },
            {
                targets: 5, // Age column
                width: '10%',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="spot-age">${data}</span>`;
                    }
                    return data;
                }
            }
        ]
    });
    
    function connect() {
        connectionStatus.textContent = 'Connecting...';
        connectionStatus.className = 'badge bg-warning text-dark me-2 connecting';
        
        // Connect to the same WebSocket server as the bandmap
        const wsUrl = 'wss://dxc.cloudlog.org';
        
        ws = new WebSocket(wsUrl);
        
        ws.onopen = () => {
            console.log('Connected to DX Cluster WebSocket server');
            connectionStatus.textContent = 'Connected';
            connectionStatus.className = 'badge bg-success me-2 connected';
        };
        
        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            if (data.type === 'spot') {
                addSpot(data);
            }
        };
        
        ws.onclose = () => {
            console.log('Disconnected from WebSocket server');
            connectionStatus.textContent = 'Disconnected';
            connectionStatus.className = 'badge bg-danger me-2 disconnected';
            setTimeout(connect, 3000); // Reconnect after 3 seconds
        };
        
        ws.onerror = (error) => {
            console.error('WebSocket error:', error);
            connectionStatus.textContent = 'Error';
            connectionStatus.className = 'badge bg-danger me-2 disconnected';
        };
    }
    
    function addSpot(spot) {
        const spotId = `${spot.dx}-${spot.frequency}-${Date.now()}`;
        spot.receivedAt = Date.now();
        spots.set(spotId, spot);
        
        // Clean up old spots (older than 2 hours)
        const cutoffTime = Date.now() - (2 * 60 * 60 * 1000);
        for (let [id, existingSpot] of spots) {
            if (existingSpot.receivedAt < cutoffTime) {
                spots.delete(id);
            }
        }
        
        updateTable();
    }
    
    // Check worked status for callsigns
    async function checkWorkedStatus() {
        // Get unique callsigns from visible spots
        const callsignsToCheck = [];
        const spotArray = Array.from(spots.values());
        const seen = new Set();
        
        spotArray.forEach(spot => {
            // Skip if already checked or if filtered out
            if (workedStatus[spot.dx]) return;
            if (hideRbnSpots && isRbnSpot(spot.spotter)) return;
            if (!matchesBandFilter(spot.frequency)) return;
            if (seen.has(spot.dx)) return; // Avoid duplicates
            
            const band = getBandFromFrequency(spot.frequency);
            callsignsToCheck.push({
                callsign: spot.dx,
                band: band
            });
            seen.add(spot.dx);
        });
        
        console.log('Checking worked status for:', callsignsToCheck.length, 'callsigns');
        
        if (callsignsToCheck.length === 0) return;
        
        // Limit to 30 callsigns per request to reduce load
        const batch = callsignsToCheck.slice(0, 30);
        
        try {
            const response = await fetch('<?php echo site_url('dxcluster/check_worked'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ callsigns: batch })
            });
            
            const data = await response.json();
            console.log('Worked status response:', data);
            
            if (data.success) {
                // Update worked status cache
                Object.assign(workedStatus, data.results);
                
                // Redraw table to show badges
                updateTable();
            }
        } catch (error) {
            console.error('Error checking worked status:', error);
        }
    }
    
    // Update worked status badges in the table (legacy function - now handled in render)
    function updateWorkedBadges() {
        // No longer needed - badges are rendered directly in DataTable column render function
        console.log('Badges updated via table redraw');
    }
    
    function updateTable() {
        // Clear existing data
        dataTable.clear();
        
        // Convert spots to array and sort by received time (most recent first)
        const spotArray = Array.from(spots.values()).sort((a, b) => {
            return b.receivedAt - a.receivedAt;
        });
        
        // Filter and add spots to table
        spotArray.forEach(spot => {
            // Skip RBN spots if filter is enabled
            if (hideRbnSpots && isRbnSpot(spot.spotter)) {
                return;
            }
            
            // Skip spots that don't match band filter
            if (!matchesBandFilter(spot.frequency)) {
                return;
            }
            
            const age = calculateAge(spot.receivedAt);
            const timeFormatted = formatTime(spot.time);
            const frequencyFormatted = `${parseFloat(spot.frequency).toFixed(1)} kHz`;
            
            const rowData = [
                timeFormatted,
                spot.dx || '',
                frequencyFormatted,
                spot.spotter || '',
                spot.comment || '',
                age
            ];
            
            dataTable.row.add(rowData);
        });
        
        dataTable.draw();
        
        // Apply age-based styling
        setTimeout(() => {
            applyAgeBasedStyling();
        }, 100);
        
        // Check worked status after a short delay (debounce)
        clearTimeout(checkWorkedTimeout);
        checkWorkedTimeout = setTimeout(() => {
            checkWorkedStatus();
        }, 500);
    }
    
    function formatTime(timeString) {
        if (!timeString) return '';
        
        // Just return the original time string with Z if it doesn't already have it
        // This matches what the bandmap does: ${spot.time}Z
        if (timeString.toString().trim() === '' || timeString.toString() === '0' || timeString.toString() === 'null' || timeString.toString() === 'undefined') {
            return 'N/A';
        }
        
        const timeStr = timeString.toString();
        if (timeStr.endsWith('Z')) {
            return timeStr;
        } else {
            return timeStr + 'Z';
        }
    }
    
    function calculateAge(receivedAt) {
        const now = Date.now();
        const ageMs = now - receivedAt;
        const ageMinutes = Math.floor(ageMs / (1000 * 60));
        
        if (ageMinutes < 1) {
            return 'Just now';
        } else if (ageMinutes < 60) {
            return `${ageMinutes}m`;
        } else {
            const ageHours = Math.floor(ageMinutes / 60);
            const remainingMinutes = ageMinutes % 60;
            return `${ageHours}h ${remainingMinutes}m`;
        }
    }
    
    function applyAgeBasedStyling() {
        const rows = dataTable.rows().nodes();
        const now = Date.now();
        
        Array.from(spots.values()).forEach((spot, index) => {
            if (index < rows.length) {
                const row = rows[index];
                const age = now - spot.receivedAt;
                
                if (age < 5 * 60 * 1000) { // Less than 5 minutes
                    row.classList.add('spot-fresh');
                } else if (age > 60 * 60 * 1000) { // More than 1 hour
                    row.classList.add('spot-old');
                }
            }
        });
    }
    
    // Update ages every minute
    setInterval(() => {
        if (spots.size > 0) {
            updateTable();
        }
    }, 60000);
    
    // Start the WebSocket connection
    connect();
});

function handleFrequencyClick(frequency) {
    const radioId = document.getElementById('radio').value;
    
    if (radioId === '0') {
        // No radio selected - copy to clipboard
        if (navigator.clipboard) {
            const freqInMHz = (parseFloat(frequency) / 1000).toFixed(3);
            navigator.clipboard.writeText(freqInMHz).then(() => {
                showToast('Frequency ' + freqInMHz + ' MHz copied to clipboard', 'info');
            });
        }
        return;
    }
    
    // QSY the radio
    const freqInMHz = (parseFloat(frequency) / 1000).toFixed(3);
    
    fetch('<?php echo site_url('dxcluster/qsy'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            radio_id: radioId,
            frequency: freqInMHz
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('QSY command sent to radio: ' + freqInMHz + ' MHz', 'success');
        } else {
            showToast('Failed to QSY radio: ' + data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error sending QSY command:', error);
        showToast('Error sending QSY command', 'danger');
    });
}

function showToast(message, type = 'primary') {
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0 position-fixed top-0 end-0 m-3`;
    toast.setAttribute('role', 'alert');
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    document.body.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Helper function for DataTables language URL (if it exists in Cloudlog)
function getDataTablesLanguageUrl() {
    // This function should match the one used in other Cloudlog views
    if (typeof lang_datatables_language !== 'undefined' && lang_datatables_language !== 'english') {
        return "<?php echo base_url(); ?>assets/js/datatables/" + lang_datatables_language + ".json";
    }
    return "";
}
</script>