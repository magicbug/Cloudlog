<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DX Cluster Bandmap</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Consolas', 'Monaco', monospace;
            background: #1e1e1e;
            color: #ffffff;
            overflow: hidden;
        }

        .bandmap-container {
            height: 100vh;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            display: flex;
            flex-direction: column;
        }

        .toolbar {
            background: #2c3e50;
            padding: 10px 20px;
            border-bottom: 2px solid #34495e;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            min-height: 60px;
        }

        .bandmap-display {
            flex: 1;
            position: relative;
            background: #1a1a1a;
            overflow: hidden;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toolbar-label {
            color: #3498db;
            font-size: 0.9em;
            font-weight: 600;
            white-space: nowrap;
        }

        .band-select {
            background: #34495e;
            color: white;
            border: 1px solid #555;
            border-radius: 4px;
            padding: 6px 8px;
            font-size: 0.9em;
            cursor: pointer;
        }

        .band-select:focus {
            outline: none;
            border-color: #3498db;
        }

        .frequency-input {
            width: 100px;
            padding: 6px 8px;
            background: #34495e;
            border: 1px solid #555;
            color: white;
            border-radius: 4px;
            font-size: 0.9em;
        }

        .frequency-input:focus {
            outline: none;
            border-color: #3498db;
        }

        .toolbar-btn {
            background: #34495e;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background 0.2s;
        }

        .toolbar-btn:hover {
            background: #3498db;
        }

        .zoom-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1em;
        }

        .stats-display {
            display: flex;
            gap: 15px;
            font-size: 0.85em;
        }

        .stat-item {
            color: #bdc3c7;
        }

        .stat-value {
            color: #3498db;
            font-weight: bold;
        }

        .frequency-scale {
            position: absolute;
            left: 0;
            top: 0;
            width: 80px;
            height: 100%;
            background: rgba(44, 62, 80, 0.9);
            border-right: 2px solid #3498db;
            overflow: hidden;
        }

        .frequency-marker {
            position: absolute;
            right: 5px;
            font-size: 14px;
            color: #3498db;
            font-weight: bold;
            white-space: nowrap;
        }

        .spots-canvas {
            position: absolute;
            left: 80px;
            top: 0;
            right: 0;
            height: 100%;
            overflow: hidden;
        }

        .spot-marker {
            position: absolute;
            background: #e74c3c;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            cursor: pointer;
            white-space: nowrap;
            border: 1px solid #c0392b;
            transition: all 0.2s;
            z-index: 10;
        }

        .spot-marker:hover {
            background: #f39c12;
            border-color: #d68910;
            transform: scale(1.1);
            z-index: 20;
        }

        .spot-marker.aged {
            background: #7f8c8d;
            color: #ecf0f1;
            border-color: #95a5a6;
        }

        .spot-marker.aged:hover {
            background: #95a5a6;
            border-color: #bdc3c7;
        }

        .spot-marker.new {
            animation: spotAppear 0.5s ease-out;
        }
        
        /* Worked status styling */
        .spot-marker.worked-on-band {
            background: #27ae60;
            border-color: #229954;
        }
        
        .spot-marker.worked-on-band:hover {
            background: #2ecc71;
            border-color: #27ae60;
        }
        
        .spot-marker.worked-other-band {
            background: #3498db;
            border-color: #2980b9;
        }
        
        .spot-marker.worked-other-band:hover {
            background: #5dade2;
            border-color: #3498db;
        }
        
        .spot-marker.not-worked {
            background: #e74c3c;
            border-color: #c0392b;
        }
        
        .spot-marker.new-dxcc {
            box-shadow: 0 0 10px #f39c12, 0 0 20px #f39c12;
            border: 2px solid #f39c12;
        }
        
        .spot-marker.new-band-dxcc {
            box-shadow: 0 0 10px #e74c3c, 0 0 20px #e74c3c;
            border: 2px solid #e74c3c;
        }

        .tune-indicator {
            position: absolute;
            left: 0;
            right: 0;
            height: 2px;
            background: #f39c12;
            box-shadow: 0 0 10px #f39c12;
            z-index: 100;
        }

        .connection-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .connected {
            background: #27ae60;
            color: white;
        }

        .disconnected {
            background: #e74c3c;
            color: white;
        }

        @keyframes spotAppear {
            from {
                opacity: 0;
                transform: scale(0.5) translateY(-10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .age-indicator {
            position: absolute;
            left: -3px;
            top: 0;
            bottom: 0;
            width: 3px;
            border-radius: 1px;
        }

        .age-new { background: #27ae60; }
        .age-recent { background: #f39c12; }
        .age-old { background: #95a5a6; }

        .spot-tooltip {
            position: absolute;
            background: #2c3e50;
            border: 2px solid #3498db;
            border-radius: 5px;
            padding: 10px;
            font-size: 12px;
            pointer-events: none;
            z-index: 1000;
            display: none;
            min-width: 200px;
        }

        .frequency-line {
            position: absolute;
            height: 1px;
            background: #7f8c8d;
            z-index: 5;
            pointer-events: none;
        }

        .frequency-line.active {
            background: #f39c12;
            height: 2px;
        }

        /* Toggle switch styles */
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-switch {
            position: relative;
            width: 50px;
            height: 24px;
            background: #34495e;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s;
            border: 1px solid #555;
        }

        .toggle-switch.active {
            background: #27ae60;
        }

        .toggle-slider {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 18px;
            height: 18px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .toggle-switch.active .toggle-slider {
            transform: translateX(26px);
        }

        .auto-track-status {
            font-size: 0.8em;
            color: #bdc3c7;
        }

        .auto-track-status.active {
            color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="bandmap-container">
        <div class="toolbar">
            <div class="toolbar-group">
                <span class="toolbar-label">Band:</span>
                <select class="band-select" id="bandSelect" onchange="handleBandChange()">
                    <?php 
                    if (isset($bands) && is_array($bands) && count($bands) > 0) {
                        $defaultSelected = false;
                        foreach ($bands as $band) {
                            // Extract the numeric part from band (e.g., "20" from "20m")
                            $bandMeters = str_replace('m', '', $band['band']);
                            $selected = (!$defaultSelected && $band['band'] == '20m') ? ' selected' : '';
                            if ($selected) {
                                $defaultSelected = true;
                            }
                            echo '<option value="' . $bandMeters . ',' . $band['start'] . ',' . $band['end'] . '"' . $selected . '>' . $band['band'] . '</option>' . "\n";
                        }
                    } else {
                        // Fallback to default bands if no user bands configured
                        // HF Bands
                        echo '<option value="2190,135700,137800">2190m</option>';
                        echo '<option value="630,472000,479000">630m</option>';
                        echo '<option value="560,501000,504000">560m</option>';
                        echo '<option value="160,1800,2000">160m</option>';
                        echo '<option value="80,3500,4000">80m</option>';
                        echo '<option value="60,5351500,5366500">60m</option>';
                        echo '<option value="40,7000,7300">40m</option>';
                        echo '<option value="30,10100,10150">30m</option>';
                        echo '<option value="20,14000,14350" selected>20m</option>';
                        echo '<option value="17,18068,18168">17m</option>';
                        echo '<option value="15,21000,21450">15m</option>';
                        echo '<option value="12,24890,24990">12m</option>';
                        echo '<option value="10,28000,29700">10m</option>';
                        // VHF Bands
                        echo '<option value="6,50000,54000">6m</option>';
                        echo '<option value="4,70000,70500">4m</option>';
                        echo '<option value="2,144000,148000">2m</option>';
                        echo '<option value="1.25,222000,225000">1.25m</option>';
                        // UHF Bands
                        echo '<option value="70,420000,450000">70cm</option>';
                        echo '<option value="33,902000,928000">33cm</option>';
                        // SHF Bands (Microwave)
                        echo '<option value="23,1240000,1300000">23cm</option>';
                        echo '<option value="13,2300000,2450000">13cm</option>';
                        echo '<option value="9,3300000,3500000">9cm</option>';
                        echo '<option value="6,5650000,5925000">6cm</option>';
                        echo '<option value="3,10000000,10500000">3cm</option>';
                        echo '<option value="1.2,24000000,24250000">1.2cm</option>';
                        // EHF Bands (Millimeter wave)
                        echo '<option value="6mm,47000000,47200000">6mm</option>';
                        echo '<option value="4mm,75500000,81000000">4mm</option>';
                        echo '<option value="2.5mm,119980000,120020000">2.5mm</option>';
                        echo '<option value="2mm,142000000,149000000">2mm</option>';
                        echo '<option value="1mm,241000000,250000000">1mm</option>';
                    }
                    ?>
                </select>
            </div>
            
            <div class="toolbar-group">
                <span class="toolbar-label">Zoom:</span>
                <button class="toolbar-btn zoom-btn" onclick="zoomOut()" title="Zoom Out">−</button>
                <button class="toolbar-btn zoom-btn" onclick="zoomIn()" title="Zoom In">+</button>
            </div>
            
            <div class="toolbar-group">
                <span class="toolbar-label">Tune:</span>
                <input type="number" class="frequency-input" id="tuneFreq" placeholder="kHz" title="Tune Frequency">
                <button class="toolbar-btn" onclick="centerOnFrequency()" title="Center on Frequency">Center</button>
            </div>
            
            <div class="toolbar-group">
                <span class="toolbar-label">Auto Track:</span>
                <div class="toggle-container">
                    <div class="toggle-switch active" id="autoTrackToggle" onclick="toggleAutoTrack()" title="Toggle Radio Auto-Tracking">
                        <div class="toggle-slider"></div>
                    </div>
                    <span class="auto-track-status active" id="autoTrackStatus">ON</span>
                </div>
            </div>
            
            <div class="stats-display">
                <div class="stat-item">Band: <span class="stat-value" id="currentBand">20m</span></div>
                    <div class="stat-item">Viewing: <span class="stat-value" id="freqRange">14.000-14.350</span> MHz</div>
                <div class="stat-item">Spots: <span class="stat-value" id="bandSpotCount">0</span></div>
                <div class="stat-item">Total: <span class="stat-value" id="totalSpots">0</span></div>
            </div>
        </div>

        <div class="bandmap-display" id="bandmapDisplay">
            <div class="connection-status disconnected" id="connectionStatus">Disconnected</div>
            <div class="frequency-scale" id="frequencyScale"></div>
            <div class="spots-canvas" id="spotsCanvas"></div>
            <div class="tune-indicator" id="tuneIndicator" style="display: none;"></div>
            <div class="spot-tooltip" id="spotTooltip"></div>
        </div>
    </div>

    <script>
        let ws;
        let spots = new Map();
        let workedStatus = {}; // Cache for worked status
        let checkWorkedTimeout = null;
        let currentBandMeters = 20;
        let bandStart = 14000;
        let bandEnd = 14350;
        let viewStart = 14000;
        let viewEnd = 14350;
        let tuneFrequency = null;
        let zoomLevel = 4; // Start zoomed in for better detail
        let lastSelectedRadio = null; // Track the last known value
        let autoTrackEnabled = true; // Control auto-tracking feature

        const bandmapDisplay = document.getElementById('bandmapDisplay');
        const frequencyScale = document.getElementById('frequencyScale');
        const spotsCanvas = document.getElementById('spotsCanvas');
        const connectionStatus = document.getElementById('connectionStatus');
        const spotTooltip = document.getElementById('spotTooltip');

        function connect() {
            // Connect to the remote DX Cluster server
            const wsUrl = 'wss://dxc.cloudlog.org';
            
            ws = new WebSocket(wsUrl);

            ws.onopen = () => {
                console.log('Connected to WebSocket server');
                connectionStatus.textContent = 'Connected';
                connectionStatus.className = 'connection-status connected';
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
                connectionStatus.className = 'connection-status disconnected';
                setTimeout(connect, 3000);
            };

            ws.onerror = (error) => {
                console.error('WebSocket error:', error);
            };
        }

        function addSpot(spot) {
            const freq = parseFloat(spot.frequency);
            const frequencyTolerance = 1.0; // 1 kHz tolerance for considering spots at "same" frequency
            
            // Add timestamp for aging
            spot.receivedAt = Date.now();
            
            // Remove any existing spots at the same or very close frequency
            for (let [id, existingSpot] of spots) {
                const existingFreq = parseFloat(existingSpot.frequency);
                if (Math.abs(freq - existingFreq) <= frequencyTolerance) {
                    spots.delete(id);
                }
            }
            
            // Add the new spot
            const spotId = `${spot.dx}-${freq}-${spot.receivedAt}`;
            spots.set(spotId, spot);
            
            // Spot cleanup is now handled in updateDisplay()

            updateDisplay();
            updateStats();
            
            // Check worked status after a short delay (debounce)
            clearTimeout(checkWorkedTimeout);
            checkWorkedTimeout = setTimeout(() => {
                checkWorkedStatus();
            }, 500);
        }

        function handleBandChange() {
            const select = document.getElementById('bandSelect');
            const [meters, start, end] = select.value.split(',').map(Number);
            selectBand(meters, start, end);
        }
        
        // Helper function to format band names properly
        function formatBandName(meters) {
            // Handle all centimeter bands
            if (meters === 70) return '70cm';
            if (meters === 33) return '33cm';
            if (meters === 23) return '23cm';
            if (meters === 13) return '13cm';
            if (meters === 9) return '9cm';
            if (meters === 6) return '6cm';
            if (meters === 3) return '3cm';
            if (meters === 1.2) return '1.2cm';
            
            // Handle millimeter bands
            if (meters === '6mm') return '6mm';
            if (meters === '4mm') return '4mm';
            if (meters === '2.5mm') return '2.5mm';
            if (meters === '2mm') return '2mm';
            if (meters === '1mm') return '1mm';
            
            // Handle decimal meter bands
            if (meters === 1.25) return '1.25m';
            
            // Default meter bands
            return `${meters}m`;
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
            if (freq >= 1000000) return 'ghz';
            
            return 'unknown';
        }
        
        // Check worked status for callsigns
        async function checkWorkedStatus() {
            const callsignsToCheck = [];
            const seen = new Set();
            
            for (let [id, spot] of spots) {
                if (workedStatus[spot.dx]) continue;
                if (seen.has(spot.dx)) continue;
                
                const band = getBandFromFrequency(spot.frequency);
                callsignsToCheck.push({
                    callsign: spot.dx,
                    band: band
                });
                seen.add(spot.dx);
            }
            
            if (callsignsToCheck.length === 0) return;
            
            // Limit to 30 callsigns per request
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
                
                if (data.success) {
                    Object.assign(workedStatus, data.results);
                    updateDisplay();
                }
            } catch (error) {
                console.error('Error checking worked status:', error);
            }
        }
        
        function selectBand(meters, start, end) {
            currentBandMeters = meters;
            
            // Add 5% buffer padding on each end so spots near edges are visible
            const bandRange = end - start;
            const padding = bandRange * 0.05;
            
            bandStart = start - padding;
            bandEnd = end + padding;
            
            // Apply default zoom level for better initial view
            const center = (start + end) / 2;
            const newRange = (bandEnd - bandStart) / zoomLevel;
            viewStart = Math.max(bandStart, center - newRange / 2);
            viewEnd = Math.min(bandEnd, center + newRange / 2);

            // Handle band display properly for all bands
            document.getElementById('currentBand').textContent = formatBandName(meters);
            document.getElementById('freqRange').textContent = `${(viewStart/1000).toFixed(3)}-${(viewEnd/1000).toFixed(3)}`;

            updateDisplay();
            updateStats();
        }

        function updateDisplay() {
            // Clear existing display
            spotsCanvas.innerHTML = '';
            frequencyScale.innerHTML = '';

            const displayHeight = bandmapDisplay.clientHeight;
            const freqRange = viewEnd - viewStart;
            
            // Draw frequency scale with adaptive spacing based on zoom level
            const pixelsPerKHz = displayHeight / freqRange;
            
            // Determine step size based on available space
            let stepSize;
            let showMinor = false;
            
            if (pixelsPerKHz > 30) {
                // Very zoomed in - show 1 kHz steps with minor markers
                stepSize = 1;
                showMinor = true;
            } else if (pixelsPerKHz > 15) {
                // Medium zoom - show 2 kHz steps
                stepSize = 2;
            } else if (pixelsPerKHz > 8) {
                // Normal zoom - show 5 kHz steps
                stepSize = 5;
            } else if (pixelsPerKHz > 4) {
                // Zoomed out - show 10 kHz steps
                stepSize = 10;
            } else {
                // Very zoomed out - show 25 kHz steps
                stepSize = 25;
            }
            
            // Find the first boundary within our view
            const startFreqKHz = Math.ceil(viewStart / stepSize) * stepSize;
            const endFreqKHz = Math.floor(viewEnd / stepSize) * stepSize;
            
            for (let freqKHz = startFreqKHz; freqKHz <= endFreqKHz; freqKHz += stepSize) {
                const freq = freqKHz;
                if (freq >= viewStart && freq <= viewEnd) {
                    const y = displayHeight - ((freq - viewStart) / freqRange * displayHeight);
                    
                    // Only show if there's enough vertical space (at least 18px between markers)
                    let showThisMarker = true;
                    if (stepSize === 1 && !showMinor && freq % 5 !== 0) {
                        showThisMarker = false;
                    }
                    
                    if (showThisMarker) {
                        const marker = document.createElement('div');
                        marker.className = 'frequency-marker';
                        marker.style.top = `${y - 8}px`; // Adjusted for larger text
                        
                        // Major markers (every 5 kHz for fine steps, or the main step size)
                        const isMajor = (stepSize <= 2 && freq % 5 === 0) || (stepSize > 2);
                        
                        if (isMajor) {
                            marker.style.fontWeight = 'bold';
                            marker.style.color = '#3498db';
                            marker.style.fontSize = '13px';
                        } else {
                            marker.style.fontSize = '11px';
                            marker.style.color = '#7f8c8d';
                        }
                        
                        // Format frequency display
                        if (freq >= 1000) {
                            const mhz = freq / 1000;
                            marker.textContent = mhz.toFixed(3);
                        } else {
                            marker.textContent = freq.toString();
                        }
                        
                        frequencyScale.appendChild(marker);
                    }
                }
            }

            // Clean up old spots (30+ minutes) before drawing
            const thirtyMinutesAgo = Date.now() - (30 * 60 * 1000);
            for (let [id, spot] of spots) {
                if (spot.receivedAt < thirtyMinutesAgo) {
                    spots.delete(id);
                }
            }

            // Draw spots
            const spotsInView = Array.from(spots.values()).filter(spot => {
                const freq = parseFloat(spot.frequency);
                return freq >= viewStart && freq <= viewEnd;
            });

            spotsInView.forEach((spot, index) => {
                const freq = parseFloat(spot.frequency);
                const y = displayHeight - ((freq - viewStart) / freqRange * displayHeight);
                const spotLeft = 10 + (index % 3) * 80;
                const age = Date.now() - spot.receivedAt;
                const fifteenMinutes = 15 * 60 * 1000;
                
                // Create connecting line from frequency scale to spot
                const frequencyLine = document.createElement('div');
                frequencyLine.className = 'frequency-line';
                
                // Age the connecting line too
                if (age >= fifteenMinutes) {
                    frequencyLine.style.background = '#95a5a6';
                }
                
                frequencyLine.style.top = `${y}px`;
                frequencyLine.style.left = '0px'; // Start from frequency scale
                frequencyLine.style.width = `${spotLeft + 40}px`; // Connect all the way to middle of spot
                spotsCanvas.appendChild(frequencyLine);
                
                const spotElement = document.createElement('div');
                spotElement.className = 'spot-marker';
                
                // Apply aging class if spot is 15+ minutes old
                if (age >= fifteenMinutes) {
                    spotElement.classList.add('aged');
                }
                
                // Add worked status styling
                const status = workedStatus[spot.dx];
                if (status) {
                    if (status.worked_on_band) {
                        spotElement.classList.add('worked-on-band');
                    } else if (status.worked_overall) {
                        spotElement.classList.add('worked-other-band');
                    } else {
                        spotElement.classList.add('not-worked');
                    }
                    
                    // Add DXCC new badge styling
                    if (status.dxcc && !status.dxcc_worked_on_band) {
                        spotElement.classList.add('new-band-dxcc');
                    } else if (status.dxcc && !status.dxcc_worked_overall) {
                        spotElement.classList.add('new-dxcc');
                    }
                }
                
                spotElement.textContent = spot.dx;
                spotElement.style.top = `${y - 10}px`;
                spotElement.style.left = `${spotLeft}px`;

                // Age indicator
                const ageIndicator = document.createElement('div');
                ageIndicator.className = 'age-indicator';
                if (age < 5 * 60 * 1000) ageIndicator.classList.add('age-new');
                else if (age < 15 * 60 * 1000) ageIndicator.classList.add('age-recent');
                else ageIndicator.classList.add('age-old');
                spotElement.appendChild(ageIndicator);

                // Event handlers
                spotElement.addEventListener('mouseenter', (e) => {
                    showTooltip(e, spot);
                    frequencyLine.classList.add('active'); // Highlight line on hover
                });
                spotElement.addEventListener('mouseleave', () => {
                    hideTooltip();
                    frequencyLine.classList.remove('active');
                });
                spotElement.addEventListener('click', () => tuneToSpot(freq, spot));

                spotsCanvas.appendChild(spotElement);
            });

            // Update tune indicator
            if (tuneFrequency && tuneFrequency >= viewStart && tuneFrequency <= viewEnd) {
                const tuneIndicator = document.getElementById('tuneIndicator');
                const y = displayHeight - ((tuneFrequency - viewStart) / freqRange * displayHeight);
                tuneIndicator.style.top = `${y - 1}px`;
                tuneIndicator.style.display = 'block';
            } else {
                document.getElementById('tuneIndicator').style.display = 'none';
            }
        }

        function showTooltip(event, spot) {
            const tooltip = spotTooltip;
            const age = Math.floor((Date.now() - spot.receivedAt) / 60000);
            
            tooltip.innerHTML = `
                <div><strong>${spot.dx}</strong></div>
                <div>Frequency: ${spot.frequency} kHz</div>
                <div>Spotter: ${spot.spotter}</div>
                <div>Time: ${spot.time}Z</div>
                <div>Comment: ${spot.comment}</div>
                <div>Age: ${age} min ago</div>
            `;
            
            // Get the spot element's position relative to the bandmap container
            const spotElement = event.target;
            const bandmapRect = bandmapDisplay.getBoundingClientRect();
            const spotRect = spotElement.getBoundingClientRect();
            
            // Position tooltip just to the right of the callsign
            const tooltipLeft = spotRect.right - bandmapRect.left + 5;
            const tooltipTop = spotRect.top - bandmapRect.top - 5;
            
            tooltip.style.left = `${tooltipLeft}px`;
            tooltip.style.top = `${tooltipTop}px`;
            tooltip.style.display = 'block';
        }

        function hideTooltip() {
            spotTooltip.style.display = 'none';
        }

        function tuneToSpot(frequency, spot = null) {
            tuneFrequency = frequency;
            document.getElementById('tuneFreq').value = frequency.toFixed(1);
            updateDisplay();
            
            // Check if a radio is selected and send QSY command
            const selectedRadio = localStorage.getItem('selectedRadio');
            if (selectedRadio && selectedRadio !== 'none') {
                // Check if this is an RBN spot (spotter callsign ends with -#)
                let mode = null;
                if (spot && spot.spotter) {
                    console.log(`Checking spotter callsign: ${spot.spotter}`);
                    if (spot.spotter.endsWith('-#')) {
                        mode = 'CW'; // RBN spots are CW
                        console.log(`RBN spot detected from ${spot.spotter}, setting mode to CW`);
                    } else {
                        console.log(`Regular spot from ${spot.spotter}, no mode override`);
                    }
                }
                
                sendQSYCommand(selectedRadio, frequency, mode);
            }
        }

        function centerOnFrequency() {
            const freq = parseFloat(document.getElementById('tuneFreq').value);
            if (!isNaN(freq)) {
                tuneFrequency = freq;
                const halfRange = (viewEnd - viewStart) / 2;
                viewStart = Math.max(bandStart, freq - halfRange);
                viewEnd = Math.min(bandEnd, freq + halfRange);
                updateDisplay();
            }
        }

        function zoomIn() {
            if (zoomLevel < 8) {
                zoomLevel *= 2;
                const center = (viewStart + viewEnd) / 2;
                const newRange = (bandEnd - bandStart) / zoomLevel;
                viewStart = Math.max(bandStart, center - newRange / 2);
                viewEnd = Math.min(bandEnd, center + newRange / 2);
                updateDisplay();
            }
        }

        function zoomOut() {
            if (zoomLevel > 1) {
                zoomLevel /= 2;
                const center = (viewStart + viewEnd) / 2;
                const newRange = (bandEnd - bandStart) / zoomLevel;
                viewStart = Math.max(bandStart, center - newRange / 2);
                viewEnd = Math.min(bandEnd, center + newRange / 2);
                updateDisplay();
            }
        }

        function updateStats() {
            const spotsInBand = Array.from(spots.values()).filter(spot => {
                const freq = parseFloat(spot.frequency);
                return freq >= bandStart && freq <= bandEnd;
            });

            document.getElementById('bandSpotCount').textContent = spotsInBand.length;
            document.getElementById('totalSpots').textContent = spots.size;
        }

        // Mouse wheel scrolling
        bandmapDisplay.addEventListener('wheel', (event) => {
            event.preventDefault();
            const delta = event.deltaY > 0 ? -1 : 1; // Inverted: scroll down = move down freq scale
            const scrollStep = (viewEnd - viewStart) * 0.1;
            
            viewStart += delta * scrollStep;
            viewEnd += delta * scrollStep;
            
            // Keep within band limits
            if (viewStart < bandStart) {
                viewEnd += bandStart - viewStart;
                viewStart = bandStart;
            }
            if (viewEnd > bandEnd) {
                viewStart -= viewEnd - bandEnd;
                viewEnd = bandEnd;
            }
            
            // Update frequency range display
            document.getElementById('freqRange').textContent = `${(viewStart/1000).toFixed(3)}-${(viewEnd/1000).toFixed(3)}`;
            
            updateDisplay();
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            setTimeout(updateDisplay, 100);
        });

        // Monitor localStorage for selectedRadio changes
        function monitorSelectedRadio() {
            const currentValue = localStorage.getItem('selectedRadio');
            
            // Check if value has changed
            if (currentValue !== lastSelectedRadio) {
                if (currentValue === null && lastSelectedRadio !== null) {
                    console.log('selectedRadio removed from localStorage (was:', lastSelectedRadio + ')');
                } else if (lastSelectedRadio === null && currentValue !== null) {
                    console.log('selectedRadio added to localStorage:', currentValue);
                } else if (currentValue !== null && lastSelectedRadio !== null) {
                    console.log('selectedRadio changed in localStorage:', lastSelectedRadio, '->', currentValue);
                }
                
                lastSelectedRadio = currentValue;
            }
        }

        // Toggle auto-tracking function
        function toggleAutoTrack() {
            autoTrackEnabled = !autoTrackEnabled;
            
            const toggle = document.getElementById('autoTrackToggle');
            const status = document.getElementById('autoTrackStatus');
            
            if (autoTrackEnabled) {
                toggle.classList.add('active');
                status.classList.add('active');
                status.textContent = 'ON';
                console.log('Radio auto-tracking enabled');
            } else {
                toggle.classList.remove('active');
                status.classList.remove('active');
                status.textContent = 'OFF';
                console.log('Radio auto-tracking disabled');
            }
        }

        // Poll the radio endpoint for CAT data
        function pollRadioEndpoint() {
            const radioID = localStorage.getItem('selectedRadio');
            
            // Only poll if we have a valid radio ID and it's not '0'
            if (!radioID || radioID === '0') {
                return;
            }

            // Fetch CAT data from the radio endpoint
            fetch(`<?php echo site_url(); ?>/radio/json/${radioID}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Radio CAT data for radio', radioID + ':', data);
                    
                    if (data.error) {
                        console.log('Error from radio endpoint:', data.error);
                        return;
                    }

                    // Process frequency data if available and auto-tracking is enabled
                    if (data.frequency && autoTrackEnabled) {
                        // Check if this is satellite mode - use RX frequency if satellite is active
                        let freqHz;
                        if (data.satname && data.frequency_rx) {
                            freqHz = parseInt(data.frequency_rx);
                            console.log('Satellite mode detected (' + data.satname + '), using RX frequency');
                        } else {
                            freqHz = parseInt(data.frequency);
                        }
                        
                        const freqKHz = freqHz / 1000; // Convert Hz to kHz
                        
                        console.log('Radio frequency:', freqKHz, 'kHz' + (data.satname ? ' (RX for ' + data.satname + ')' : ''));
                        
                        // Find which band this frequency belongs to
                        const band = findBandForFrequency(freqKHz);
                        
                        if (band) {
                            // Check if we need to change bands
                            if (currentBandMeters !== band.meters || 
                                bandStart !== band.start || 
                                bandEnd !== band.end) {
                                console.log('Switching to band:', band.meters + 'm');
                                
                                // Update the band select dropdown
                                const bandSelect = document.getElementById('bandSelect');
                                console.log('Trying to set dropdown to:', `${band.meters},${band.start},${band.end}`);
                                
                                // Find the exact matching option by comparing ranges
                                for (let i = 0; i < bandSelect.options.length; i++) {
                                    const option = bandSelect.options[i];
                                    const [optMeters, optStart, optEnd] = option.value.split(',').map(Number);
                                    if (optStart === band.start && optEnd === band.end) {
                                        bandSelect.selectedIndex = i;
                                        console.log('Set dropdown to option:', option.text, 'value:', option.value);
                                        break;
                                    }
                                }
                                
                                // Select the band (but don't center yet, we'll do that below)
                                currentBandMeters = band.meters;
                                bandStart = band.start;
                                bandEnd = band.end;
                                
                                // Use the band name from the dropdown if available, otherwise format properly
                                if (band.name) {
                                    document.getElementById('currentBand').textContent = band.name;
                                } else {
                                    document.getElementById('currentBand').textContent = formatBandName(band.meters);
                                }
                            }
                            
                            // Set tune frequency to current radio frequency
                            tuneFrequency = freqKHz;
                            
                            // Always center the view on the radio frequency when auto-tracking
                            const currentRange = viewEnd - viewStart;
                            viewStart = Math.max(bandStart, freqKHz - currentRange / 2);
                            viewEnd = Math.min(bandEnd, freqKHz + currentRange / 2);
                            
                            // Adjust if we hit band boundaries
                            if (viewStart < bandStart) {
                                viewEnd += bandStart - viewStart;
                                viewStart = bandStart;
                            }
                            if (viewEnd > bandEnd) {
                                viewStart -= viewEnd - bandEnd;
                                viewEnd = bandEnd;
                            }
                            
                            // Update the frequency range display
                            document.getElementById('freqRange').textContent = `${(viewStart/1000).toFixed(3)}-${(viewEnd/1000).toFixed(3)}`;
                            
                            // Update display to show tune indicator (will be centered)
                            updateDisplay();
                            updateStats();
                        } else {
                            console.log('Frequency', freqKHz, 'kHz not in any configured band');
                        }
                    } else if (data.frequency && !autoTrackEnabled) {
                        console.log('Radio frequency received but auto-tracking is disabled');
                    }
                })
                .catch(error => {
                    console.error('Error fetching radio data:', error);
                });
        }

        // Find which band a frequency belongs to
        function findBandForFrequency(freqKHz) {
            const bandSelect = document.getElementById('bandSelect');
            const options = bandSelect.options;
            
            console.log('Looking for band for frequency:', freqKHz, 'kHz');
            console.log('Available band options:', options.length);
            
            for (let i = 0; i < options.length; i++) {
                console.log('Raw option value:', options[i].value);
                console.log('Option text:', options[i].text);
                const parts = options[i].value.split(',');
                console.log('Split parts:', parts);
                const [meters, start, end] = parts.map(Number);
                console.log(`Parsed: meters=${meters}, start=${start}, end=${end}`);
                console.log(`Checking band ${meters}m: ${start}-${end} kHz`);
                if (freqKHz >= start && freqKHz <= end) {
                    console.log(`Found matching band: ${meters}m (${start}-${end})`);
                    // Use the option text instead of trying to reconstruct the band name
                    return { meters, start, end, name: options[i].text };
                }
            }
            
            console.log('No matching band found for', freqKHz, 'kHz');
            return null;
        }

        // Send QSY command to radio
        function sendQSYCommand(radioId, frequency, mode = null) {
            // Convert kHz to MHz for the API
            const frequencyMHz = frequency / 1000;
            
            console.log(`Sending QSY command: Radio ${radioId}, Frequency ${frequencyMHz} MHz${mode ? ', Mode ' + mode : ''}`);
            
            const payload = {
                radio_id: radioId,
                frequency: frequencyMHz
            };
            
            if (mode) {
                payload.mode = mode;
            }
            
            fetch('<?php echo site_url('dxcluster/qsy'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(payload)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('QSY command sent successfully:', data);
                    // Optionally show a brief success indicator
                    const modeText = data.mode ? ` ${data.mode}` : '';
                    showQSYFeedback('success', `QSY to ${frequencyMHz.toFixed(3)} MHz${modeText} sent to radio`);
                } else {
                    console.error('QSY command failed:', data.message);
                    showQSYFeedback('error', `QSY failed: ${data.message}`);
                }
            })
            .catch(error => {
                console.error('Error sending QSY command:', error);
                showQSYFeedback('error', 'Failed to send QSY command');
            });
        }

        // Show brief feedback for QSY commands
        function showQSYFeedback(type, message) {
            // Remove any existing feedback
            const existingFeedback = document.getElementById('qsyFeedback');
            if (existingFeedback) {
                existingFeedback.remove();
            }
            
            // Create feedback element
            const feedback = document.createElement('div');
            feedback.id = 'qsyFeedback';
            feedback.textContent = message;
            feedback.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 10px 15px;
                border-radius: 4px;
                color: white;
                font-weight: bold;
                z-index: 1000;
                opacity: 0.9;
                ${type === 'success' ? 'background-color: #28a745;' : 'background-color: #dc3545;'}
            `;
            
            document.body.appendChild(feedback);
            
            // Auto-remove after 3 seconds
            setTimeout(() => {
                feedback.remove();
            }, 3000);
        }

        // Initialize with proper zoom
        function initializeBandmap() {
            // Check if selectedRadio is in localStorage
            const selectedRadio = localStorage.getItem('selectedRadio');
            if (selectedRadio) {
                console.log('selectedRadio found in localStorage:', selectedRadio);
                lastSelectedRadio = selectedRadio;
            } else {
                console.log('selectedRadio not found in localStorage');
                lastSelectedRadio = null;
            }
            
            // Start monitoring for changes (poll every 500ms)
            setInterval(monitorSelectedRadio, 500);
            
            // Start polling the radio endpoint (poll every 1 second)
            setInterval(pollRadioEndpoint, 1000);
            
            // Set initial 20m band with 4x zoom
            const center = (bandStart + bandEnd) / 2;
            const newRange = (bandEnd - bandStart) / zoomLevel;
            viewStart = Math.max(bandStart, center - newRange / 2);
            viewEnd = Math.min(bandEnd, center + newRange / 2);
            
            document.getElementById('freqRange').textContent = `${(viewStart/1000).toFixed(3)} - ${(viewEnd/1000).toFixed(3)}`;
            
            connect();
            updateDisplay();
        }
        
        // Initialize
        initializeBandmap();
    </script>
</body>
</html>