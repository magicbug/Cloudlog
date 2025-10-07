
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
    
    const connectionStatus = document.getElementById('connectionStatus');
    const spotsTableBody = document.getElementById('spotsTableBody');
    
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
                render: function(data, type, row) {
                    return data;
                }
            },
            {
                targets: 2, // Frequency column
                className: 'frequency-cell',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<a href="#" class="frequency-link" onclick="handleFrequencyClick('${data}')">${data}</a>`;
                    }
                    return data;
                }
            },
            {
                targets: 5, // Age column
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
    
    function updateTable() {
        // Clear existing data
        dataTable.clear();
        
        // Convert spots to array and sort by received time (most recent first)
        const spotArray = Array.from(spots.values()).sort((a, b) => {
            return b.receivedAt - a.receivedAt;
        });
        
        // Add spots to table
        spotArray.forEach(spot => {
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
    // You can implement frequency tuning functionality here
    // For example, send to radio or copy to clipboard
    console.log('Frequency clicked:', frequency);
    
    // Copy to clipboard as example
    if (navigator.clipboard) {
        const freqInMHz = (parseFloat(frequency) / 1000).toFixed(3);
        navigator.clipboard.writeText(freqInMHz).then(() => {
            // Show a temporary notification
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-primary border-0 position-fixed top-0 end-0 m-3';
            toast.setAttribute('role', 'alert');
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        Frequency ${freqInMHz} MHz copied to clipboard
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
        });
    }
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