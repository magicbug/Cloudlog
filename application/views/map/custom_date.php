<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/map-enhancements.css">

<style>
.callsign-label-text {
    background: rgba(0, 0, 0, 0.8) !important;
    color: white !important;
    font-family: Arial, sans-serif !important;
    font-weight: bold !important;
    padding: 1px 3px !important;
    border-radius: 2px !important;
    border: 1px solid #fff !important;
    white-space: nowrap !important;
    text-align: center !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.5) !important;
    display: inline-block !important;
    line-height: 1 !important;
    margin: 0 !important;
    min-width: auto !important;
    width: auto !important;
    height: auto !important;
    transform-origin: center !important;
    font-size: 12px !important; /* Ensure consistent font size */
}

</style>

<div class="container custom-map-QSOs">
    <br>
    
    <!-- Enhanced Header Section -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 mb-4">
                <div class="card-body rounded">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1">
                                <i class="fas fa-map-marked-alt me-2"></i>
                                <?php echo $logbook_name; echo strpos($logbook_name, 'logbook') ? '' : ' logbook'; ?> QSOs (Custom Dates)
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ($this->session->flashdata('notice')) { ?>
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <?php echo $this->session->flashdata('notice'); ?>
        </div>
    <?php } ?>

    <!-- Filter Controls Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filter Controls
            </h5>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo site_url('map/custom'); ?>">
                
                <!-- Quick Action Buttons -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0">
                            <div class="card-body py-3">
                                <h6 class="card-title mb-3">
                                    <i class="fas fa-bolt text-warning me-2"></i>Quick Filters
                                </h6>
                                <div class="row g-2">
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDateRange('today')">
                                            <i class="fas fa-calendar-day me-1"></i>Today
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDateRange('week')">
                                            <i class="fas fa-calendar-week me-1"></i>This Week
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDateRange('month')">
                                            <i class="fas fa-calendar-alt me-1"></i>This Month
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="setDateRange('year')">
                                            <i class="fas fa-calendar me-1"></i>This Year
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="get_oldest_qso_date()">
                                            <i class="fas fa-history me-1"></i>All Time
                                        </button>
                                    </div>
                                    <div class="col-6 col-md-2">
                                        <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="clearFilters()">
                                            <i class="fas fa-eraser me-1"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Range Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-calendar-range me-2"></i>Date Range
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="from" class="form-label fw-semibold">
                                            <i class="fas fa-play text-success me-1"></i><?php echo lang('gen_from_date') ?>
                                        </label>
                                        <input name="from" id="from" type="date" class="form-control" value="<?php echo $date_from; ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="to" class="form-label fw-semibold">
                                            <i class="fas fa-stop text-danger me-1"></i><?php echo lang('gen_to_date') ?>
                                        </label>
                                        <input name="to" id="to" type="date" class="form-control" value="<?php echo $date_to; ?>" max="<?php echo $date_to; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Filters Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title text-secondary mb-3">
                                    <i class="fas fa-sliders-h me-2"></i>Advanced Filters
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="band2" class="form-label fw-semibold">
                                            Band
                                        </label>
                                        <select id="band2" name="band" class="form-select">
                                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>>
                                                <i class="fas fa-asterisk"></i> Every band
                                            </option>
                                            <?php foreach ($worked_bands as $band) {
                                                echo '<option value="' . $band . '"';
                                                if ($this->input->post('band') == $band) echo ' selected';
                                                echo '>' . $band . '</option>' . "\n";
                                            } ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="mode" class="form-label fw-semibold">
                                        Mode
                                        </label>
                                        <select id="mode" name="mode" class="form-select">
                                            <option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>>
                                                <?php echo lang('general_word_all') ?>
                                            </option>
                                            <?php
                                            foreach ($modes as $mode) {
                                                if ($mode->submode ?? '' == '') {
                                                    echo '<option value="' . $mode . '">' . strtoupper($mode) . '</option>'."\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <?php
                                        // Sort translated propagation modes alphabetically
                                        $prop_modes = ['AS' => lang('gen_hamradio_propagation_AS'),
                                                       'AUR' => lang('gen_hamradio_propagation_AUR'),
                                                       'AUE' => lang('gen_hamradio_propagation_AUE'),
                                                       'BS' => lang('gen_hamradio_propagation_BS'),
                                                       'ECH' => lang('gen_hamradio_propagation_ECH'),
                                                       'EME' => lang('gen_hamradio_propagation_EME'),
                                                       'ES' => lang('gen_hamradio_propagation_ES'),
                                                       'FAI' => lang('gen_hamradio_propagation_FAI'),
                                                       'F2' => lang('gen_hamradio_propagation_F2'),
                                                       'INTERNET' => lang('gen_hamradio_propagation_INTERNET'),
                                                       'ION' => lang('gen_hamradio_propagation_ION'),
                                                       'IRL' => lang('gen_hamradio_propagation_IRL'),
                                                       'MS' => lang('gen_hamradio_propagation_MS'),
                                                       'RPT' => lang('gen_hamradio_propagation_RPT'),
                                                       'RS' => lang('gen_hamradio_propagation_RS'),
                                                       'SAT' => lang('gen_hamradio_propagation_SAT'),
                                                       'TEP' => lang('gen_hamradio_propagation_TEP'),
                                                       'TR' => lang('gen_hamradio_propagation_TR')];
                                        asort($prop_modes);
                                    ?>
                                    <div class="col-md-4 mb-3">
                                        <label for="selectPropagation" class="form-label fw-semibold">
                                            Propagation Mode
                                        </label>
                                        <select class="form-select" id="selectPropagation" name="prop_mode">
                                            <option value="All" <?php if ($this->input->post('prop_mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>>
                                                <?php echo lang('general_word_all') ?>
                                            </option>
                                            <option value="" <?php if ($this->input->post('prop_mode') == "" && $this->input->method() == 'post') echo ' selected'; ?>>
                                                <?php echo lang('general_word_undefined') ?>
                                            </option>
                                            <?php
                                                foreach($prop_modes as $key => $label) {
                                                    echo '<option value="' . $key . '"';
                                                    if ($this->input->post('prop_mode') == $key) echo ' selected';
                                                    echo '>' . $label . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons and Status -->
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary btn_submit_map_custom me-3" type="button">
                            <i class="fas fa-map-marker-alt me-2"></i>Load Map
                            <span class="spinner-border spinner-border-sm ms-2 d-none" id="load-spinner" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </span>
                        </button>
                        <button class="btn btn-outline-info" type="button" onclick="exportMap()">
                            <i class="fas fa-download me-2"></i>Export Map
                        </button>
                    </div>
                    <div class="col-md-6">
                        <div class="alert alert-danger warningOnSubmit" style="display:none;">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <span class="warningOnSubmit_txt">Error loading map data</span>
                        </div>
                        <div class="alert alert-success d-none" id="map-success-alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <span id="qso-count-display">Map loaded successfully</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<!-- Map Container -->
<div class="card">
    <div class="card-header py-3">
        <div class="row align-items-center">
            <div class="col-md-4">
                <h5 class="mb-0">
                    <i class="fas fa-globe-americas me-2"></i>World Map View
                </h5>
                <small class="text-muted">Showing: <strong id="active-logbook-display"><?php echo $logbook_name ?></strong></small>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="me-4">
                        <small class="text-muted">QSOs Displayed:</small>
                        <span class="badge bg-primary ms-1" id="qso-count">0</span>
                    </div>
                    <div class="alert alert-success mb-0 py-1 px-2 d-none" id="map-status">
                        <small><i class="fas fa-check-circle me-1"></i>Map loaded successfully</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-outline-success" onclick="fitMapToMarkers()" title="Zoom to fit all QSOs">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="toggleGridSquares()" title="Toggle grid squares">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="toggleCallsignLabels()" title="Toggle callsign labels" id="callsign-labels-btn">
                        <i class="fas fa-tags"></i>
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="toggleFullscreen()" title="Toggle fullscreen">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="custommap" class="map-leaflet" style="width: 100%; height: 1000px;"></div>
    </div>
</div>

<!-- Map Legend -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted fw-semibold me-3">Legend:</small>
                        <span class="me-3">
                            <i class="fas fa-dot-circle text-danger me-1"></i>
                            <small>QSO</small>
                        </span>
                        <span class="me-3">
                            <i class="fas fa-home text-primary me-1"></i>
                            <small>Home Station</small>
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Click on markers for QSO details • Use 
                            <i class="fas fa-tags"></i> to toggle callsign labels
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced JavaScript functionality for improved UX
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for date changes - removed updateDateRangeDisplay calls
    // since the date-range-display element doesn't exist
});

function setDateRange(period) {
    const today = new Date();
    const fromInput = document.getElementById('from');
    const toInput = document.getElementById('to');
    
    let fromDate, toDate = today;
    
    switch (period) {
        case 'today':
            fromDate = new Date(today);
            break;
        case 'week':
            fromDate = new Date(today);
            fromDate.setDate(today.getDate() - 7);
            break;
        case 'month':
            // Create first day of current month
            fromDate = new Date(today.getFullYear(), today.getMonth(), 1);
            console.log('Month calculation - Today:', today.toDateString(), 'First of month:', fromDate.toDateString());
            break;
        case 'year':
            fromDate = new Date(today.getFullYear(), 0, 1);
            break;
    }
    
    if (fromDate) {
        // Use local date formatting to avoid timezone issues
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };
        
        fromInput.value = formatDate(fromDate);
        toInput.value = formatDate(today);
    }
}

function clearFilters() {
    document.getElementById('band2').selectedIndex = 0;
    document.getElementById('mode').selectedIndex = 0;
    document.getElementById('selectPropagation').selectedIndex = 0;
    
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('from').value = today;
    document.getElementById('to').value = today;
}

function exportMap() {
    // This would integrate with the existing Leaflet print functionality
    if (typeof map !== 'undefined' && map._container) {
        // Trigger the existing print functionality
        alert('Map export functionality - integrate with existing Leaflet print plugin');
    }
}

function fitMapToMarkers() {
    if (typeof map !== 'undefined' && plotlayers.length > 0) {
        const group = new L.featureGroup(plotlayers);
        map.fitBounds(group.getBounds().pad(0.1));
    }
}

function toggleGridSquares() {
    if (typeof maidenhead !== 'undefined') {
        if (map.hasLayer(maidenhead)) {
            map.removeLayer(maidenhead);
        } else {
            map.addLayer(maidenhead);
        }
    }
}

function toggleFullscreen() {
    const mapContainer = document.getElementById('custommap');
    if (!document.fullscreenElement) {
        mapContainer.requestFullscreen().then(() => {
            mapContainer.style.height = '100vh';
            if (typeof map !== 'undefined') {
                map.invalidateSize();
            }
        });
    } else {
        document.exitFullscreen().then(() => {
            mapContainer.style.height = '1000px'; // Restore original height
            if (typeof map !== 'undefined') {
                map.invalidateSize();
            }
        });
    }
}

// Global variable to track if callsign labels are shown
let callsignLabelsVisible = false;
let callsignLabels = [];

function clearCallsignLabels() {
    // Remove all existing callsign labels from the map
    callsignLabels.forEach(function(labelMarker) {
        if (map.hasLayer(labelMarker)) {
            map.removeLayer(labelMarker);
        }
    });
    
    // Clear the labels array
    callsignLabels = [];
    
    // Reset the visibility state
    callsignLabelsVisible = false;
    
    // Reset button appearance
    const button = document.getElementById('callsign-labels-btn');
    if (button) {
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-success');
    }
}

function toggleCallsignLabels() {
    const button = document.getElementById('callsign-labels-btn');
    
    if (!callsignLabelsVisible) {
        // Show callsign labels
        if (typeof plotlayers !== 'undefined' && plotlayers.length > 0) {
            plotlayers.forEach(function(marker) {
                if (marker.data && marker.data.callsign) {
                    // Only create labels when callsign is specifically provided
                    const callsign = marker.data.callsign;
                    
                    // Try a different approach - create a DivIcon instead of tooltip
                    const labelIcon = L.divIcon({
                        className: 'callsign-label-icon',
                        html: `<div class="callsign-label-text">${callsign}</div>`,
                        iconSize: [0, 0], // No fixed size, let CSS handle it
                        iconAnchor: [0, -5] // Position above the marker with minimal offset
                    });
                    
                    // Create a separate marker for the label
                    const labelMarker = L.marker(marker.getLatLng(), {
                        icon: labelIcon,
                        interactive: false,
                        zIndexOffset: 1000
                    });
                    
                    map.addLayer(labelMarker);
                    callsignLabels.push(labelMarker);
                }
            });
            
            callsignLabelsVisible = true;
            button.classList.remove('btn-outline-success');
            button.classList.add('btn-success');
        }
    } else {
        // Hide callsign labels
        callsignLabels.forEach(function(labelMarker) {
            map.removeLayer(labelMarker);
        });
        
        callsignLabels = [];
        callsignLabelsVisible = false;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-success');
    }
}

// Update statistics when map loads
function updateMapStatistics(plotjson) {
    if (plotjson && plotjson.markers) {
        const qsoCountElement = document.getElementById('qso-count');
        if (qsoCountElement) {
            qsoCountElement.textContent = plotjson.markers.length;
        }
        
        // Count unique countries and gridsquares
        const countries = new Set();
        const gridsquares = new Set();
        
        plotjson.markers.forEach(marker => {
            if (marker.country) countries.add(marker.country);
            if (marker.gridsquare) gridsquares.add(marker.gridsquare);
        });
        
        // Only update if elements exist
        const countryCountElement = document.getElementById('country-count');
        if (countryCountElement) {
            countryCountElement.textContent = countries.size;
        }
        
        const gridCountElement = document.getElementById('grid-count');
        if (gridCountElement) {
            gridCountElement.textContent = gridsquares.size;
        }
        
        // Show success message
        const mapStatusElement = document.getElementById('map-status');
        if (mapStatusElement) {
            mapStatusElement.classList.remove('d-none');
            setTimeout(() => {
                mapStatusElement.classList.add('d-none');
            }, 3000);
        }
        
        // Handle callsign labels when new data is loaded
        const labelsWereVisible = callsignLabelsVisible;
        
        // Clear any existing callsign labels before processing new data
        clearCallsignLabels();
        
        // Reapply callsign labels if they were previously enabled
        if (labelsWereVisible) {
            setTimeout(() => {
                toggleCallsignLabels();
            }, 100); // Small delay to ensure markers are fully rendered
        }
    }
}
</script>