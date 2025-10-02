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
                        echo '<option value="160,1800,2000">160m</option>';
                        echo '<option value="80,3500,4000">80m</option>';
                        echo '<option value="40,7000,7300">40m</option>';
                        echo '<option value="20,14000,14350" selected>20m</option>';
                        echo '<option value="17,18068,18168">17m</option>';
                        echo '<option value="15,21000,21450">15m</option>';
                        echo '<option value="12,24890,24990">12m</option>';
                        echo '<option value="10,28000,29700">10m</option>';
                        echo '<option value="6,50000,54000">6m</option>';
                        echo '<option value="2,144000,148000">2m</option>';
                        echo '<option value="70,430000,440000">70cm</option>';
                        echo '<option value="23,1240000,1300000">23cm</option>';
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
                <div class="stat-item">Range: <span class="stat-value" id="freqRange">14.000-14.350</span></div>
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
        }

        function handleBandChange() {
            const select = document.getElementById('bandSelect');
            const [meters, start, end] = select.value.split(',').map(Number);
            selectBand(meters, start, end);
        }
        
        function selectBand(meters, start, end) {
            currentBandMeters = meters;
            bandStart = start;
            bandEnd = end;
            
            // Apply default zoom level for better initial view
            const center = (start + end) / 2;
            const newRange = (end - start) / zoomLevel;
            viewStart = Math.max(start, center - newRange / 2);
            viewEnd = Math.min(end, center + newRange / 2);

            document.getElementById('currentBand').textContent = `${meters}m`;
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
                spotElement.addEventListener('click', () => tuneToSpot(freq));

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

        function tuneToSpot(frequency) {
            tuneFrequency = frequency;
            document.getElementById('tuneFreq').value = frequency.toFixed(1);
            updateDisplay();
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
            const delta = event.deltaY > 0 ? 1 : -1;
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
                                bandSelect.value = `${band.meters},${band.start},${band.end}`;
                                
                                // Select the band (but don't center yet, we'll do that below)
                                currentBandMeters = band.meters;
                                bandStart = band.start;
                                bandEnd = band.end;
                                
                                document.getElementById('currentBand').textContent = `${band.meters}m`;
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
            
            for (let i = 0; i < options.length; i++) {
                const [meters, start, end] = options[i].value.split(',').map(Number);
                if (freqKHz >= start && freqKHz <= end) {
                    return { meters, start, end };
                }
            }
            
            return null;
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