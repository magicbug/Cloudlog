<div class="container awards-page dxc-page">
    <!-- Topbar -->
    <div class="card awards-topbar mb-3">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="awards-brand-icon d-inline-flex align-items-center justify-content-center">
                    <i class="fas fa-broadcast-tower"></i>
                </span>
                <div>
                    <h1 class="h5 mb-0 fw-semibold"><?php echo $page_title; ?></h1>
                    <div class="small text-muted">Real-time DX cluster feed with live band activity, DXCC alerts, and CAT QSY</div>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span id="connectionStatus" class="dxc-status-pill is-connecting">
                    <span class="dxc-status-dot"></span>
                    <span id="connectionStatusText">Connecting...</span>
                </span>
                <button type="button" id="dxcPauseBtn" class="btn btn-outline-secondary btn-sm" title="Pause or resume live table updates">
                    <i class="fas fa-pause me-1"></i><span>Pause</span>
                </button>
                <button type="button" id="dxcSoundBtn" class="btn btn-outline-secondary btn-sm" title="Play a chime when a New DXCC or New Band spot arrives" aria-pressed="false">
                    <i class="fas fa-volume-mute me-1"></i><span>Alerts Off</span>
                </button>
                <a href="<?php echo site_url('dxcluster/bandmap'); ?>" class="btn btn-primary btn-sm" target="_blank" rel="noopener">
                    <i class="fas fa-chart-line me-1"></i>Bandmap
                </a>
            </div>
        </div>
    </div>

    <!-- Clickable KPI Summary Cards -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card dxped-kpi-card h-100" data-dxc-kpi="all" role="button" tabindex="0">
                <div class="card-body py-2 px-3">
                    <div class="dxped-kpi-label">Live Spots</div>
                    <div class="dxped-kpi-value" id="dxcKpiTotal">0</div>
                    <div class="small text-muted" id="dxcKpiRate">Waiting for spots…</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card dxped-kpi-card h-100" data-dxc-kpi="new_dxcc" role="button" tabindex="0">
                <div class="card-body py-2 px-3">
                    <div class="dxped-kpi-label">New DXCC</div>
                    <div class="dxped-kpi-value text-warning" id="dxcKpiNewDxcc">0</div>
                    <div class="small text-muted">Never worked entity</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card dxped-kpi-card h-100" data-dxc-kpi="new_band" role="button" tabindex="0">
                <div class="card-body py-2 px-3">
                    <div class="dxped-kpi-label">New Band</div>
                    <div class="dxped-kpi-value text-danger" id="dxcKpiNewBand">0</div>
                    <div class="small text-muted">Needed on this band</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card dxped-kpi-card h-100" id="dxcKpiTopBandCard" data-dxc-kpi="top_band" role="button" tabindex="0">
                <div class="card-body py-2 px-3">
                    <div class="dxped-kpi-label">Hottest Band</div>
                    <div class="dxped-kpi-value text-primary" id="dxcKpiTopBand">—</div>
                    <div class="small text-muted" id="dxcKpiTopBandSub">Click to filter band</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Cluster Card -->
    <div class="card dxc-card">
        <!-- Band Activity Strip -->
        <div class="dxc-band-strip px-3 py-2 border-bottom">
            <div class="d-flex align-items-center gap-1 flex-wrap" id="dxcBandStrip" role="group" aria-label="Filter by band">
                <button type="button" class="dxc-band-pill is-active" data-band="all">
                    <span>All</span><span class="dxc-band-count" data-band-count="all">0</span>
                </button>
                <?php
                $dxc_bands = ['160m','80m','60m','40m','30m','20m','17m','15m','12m','10m','6m','4m','2m','70cm','23cm','ghz'];
                foreach ($dxc_bands as $b) {
                    $label = ($b === 'ghz') ? 'GHz+' : $b;
                ?>
                <button type="button" class="dxc-band-pill" data-band="<?php echo $b; ?>">
                    <span><?php echo $label; ?></span><span class="dxc-band-count" data-band-count="<?php echo $b; ?>">0</span>
                </button>
                <?php } ?>
            </div>
        </div>

        <!-- Unified Controls Bar -->
        <div class="card-body py-2 px-3 border-bottom dxc-controls-bar">
            <div id="radio_status"></div>
            <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                <!-- Left: Radio + Mode + Need pills + RBN switch -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Radio Selection -->
                    <div class="input-group input-group-sm dxc-radio-group">
                        <span class="input-group-text" title="Select CAT radio for one-click QSY"><i class="fas fa-broadcast-tower"></i></span>
                        <select class="form-select form-select-sm radios" id="radio" name="radio" aria-label="Radio selection">
                            <option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
                            <?php foreach ($radios->result() as $row) { ?>
                                <option value="<?php echo $row->id; ?>" <?php if ($this->session->userdata('radio') == $row->id) { echo 'selected="selected"'; } ?>><?php echo htmlspecialchars($row->radio, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Mode Pills -->
                    <div class="d-flex align-items-center gap-1" id="dxcModePills" role="group" aria-label="Filter by mode">
                        <button type="button" class="dxped-pill is-active" data-mode="all">All</button>
                        <button type="button" class="dxped-pill" data-mode="cw">CW</button>
                        <button type="button" class="dxped-pill" data-mode="ssb">SSB</button>
                        <button type="button" class="dxped-pill" data-mode="digi">Digi</button>
                    </div>

                    <!-- Need Pills -->
                    <div class="d-flex align-items-center gap-1" id="dxcNeedPills" role="group" aria-label="Filter by DXCC status">
                        <button type="button" class="dxped-pill is-active" data-need="all">All Spots</button>
                        <button type="button" class="dxped-pill" data-need="new_any">New Any</button>
                        <button type="button" class="dxped-pill" data-need="new_dxcc">New DXCC</button>
                        <button type="button" class="dxped-pill" data-need="new_band">New Band</button>
                    </div>

                    <!-- Hide RBN Switch -->
                    <div class="form-check form-switch mb-0 ms-1">
                        <input class="form-check-input" type="checkbox" role="switch" id="hideRbnSpots" checked>
                        <label class="form-check-label small" for="hideRbnSpots">Hide RBN</label>
                    </div>
                </div>

                <!-- Right: Live Search + Reset -->
                <div class="d-flex align-items-center gap-2 ms-auto dxc-search-wrap">
                    <div class="input-group input-group-sm dxc-search-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="search" class="form-control" id="dxcSearchInput" placeholder="Search call, entity, comment…" autocomplete="off">
                    </div>
                    <button type="button" id="dxcResetBtn" class="btn btn-outline-secondary btn-sm d-none" title="Reset all filters">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Spots Table -->
        <div class="table-responsive">
            <table id="dxSpotsTable" class="table table-hover align-middle mb-0 dxped-table dxc-table">
                <thead>
                    <tr>
                        <th style="width: 95px;">Time</th>
                        <th>DX Call &amp; Entity</th>
                        <th style="width: 190px;">Frequency</th>
                        <th style="width: 130px;">Spotter</th>
                        <th>Comment</th>
                        <th class="text-end" style="width: 96px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="spotsTableBody">
                    <tr id="dxcEmptyRow">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-satellite-dish fa-lg mb-2 d-block opacity-50"></i>
                            <span id="dxcEmptyMsg">Connecting to live DX Cluster stream…</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination + Inline Legend Footer -->
        <div class="card-footer py-2 px-3 dxc-footer">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center gap-3 small text-muted">
                    <span><i class="fas fa-check text-success me-1"></i>Worked band</span>
                    <span><i class="fas fa-check text-info me-1"></i>Worked other</span>
                    <span><span class="dxc-tag-new-dxcc">New DXCC</span></span>
                    <span><span class="dxc-tag-new-band">New Band</span></span>
                    <span class="d-none d-md-inline" id="dxcQsyTip"><i class="fas fa-mouse-pointer me-1"></i>Click any frequency to QSY or copy</span>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <small class="text-muted" id="dxcShownInfo">0 spots</small>
                    <select class="form-select form-select-sm dxc-pagesize-select" id="dxcPageSize" aria-label="Spots per page">
                        <option value="25">25</option>
                        <option value="50" selected>50</option>
                        <option value="100">100</option>
                    </select>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Spot pagination">
                        <button type="button" class="btn btn-outline-secondary" id="dxcPrevPage" disabled>Prev</button>
                        <button type="button" class="btn btn-outline-secondary" id="dxcPageIndicator" disabled>1 / 1</button>
                        <button type="button" class="btn btn-outline-secondary" id="dxcNextPage" disabled>Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>assets/js/dxcluster-utils.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var ws = null;
    var spots = new Map();
    var workedStatus = {}; // Keyed by "CALL|band"
    var alertedKeys = new Set(); // Avoid double-beeping same spot
    var checkWorkedTimeout = null;
    var renderTimer = null;
    var isPaused = false;
    var pausedNewCount = 0;
    var soundEnabled = localStorage.getItem('cloudlog_dxcSound') === 'true';

    var hideRbnSpots = true;
    var selectedBand = 'all';
    var selectedMode = 'all';
    var selectedNewDxcc = 'all';
    var searchQuery = '';
    var pageSize = parseInt(localStorage.getItem('cloudlog_dxcPageSize') || '50', 10) || 50;
    var currentPage = 1;
    var topBandName = '';

    var connectionStatus = document.getElementById('connectionStatus');
    var connectionStatusText = document.getElementById('connectionStatusText');
    var pauseBtn = document.getElementById('dxcPauseBtn');
    var soundBtn = document.getElementById('dxcSoundBtn');
    var spotsTableBody = document.getElementById('spotsTableBody');
    var hideRbnCheckbox = document.getElementById('hideRbnSpots');
    var searchInput = document.getElementById('dxcSearchInput');
    var resetBtn = document.getElementById('dxcResetBtn');
    var pageSizeSelect = document.getElementById('dxcPageSize');
    var prevPageBtn = document.getElementById('dxcPrevPage');
    var nextPageBtn = document.getElementById('dxcNextPage');
    var pageIndicator = document.getElementById('dxcPageIndicator');
    var shownInfo = document.getElementById('dxcShownInfo');
    var radioSelect = document.getElementById('radio');
    var qsyTip = document.getElementById('dxcQsyTip');

    var kpiTotal = document.getElementById('dxcKpiTotal');
    var kpiRate = document.getElementById('dxcKpiRate');
    var kpiNewDxcc = document.getElementById('dxcKpiNewDxcc');
    var kpiNewBand = document.getElementById('dxcKpiNewBand');
    var kpiTopBand = document.getElementById('dxcKpiTopBand');
    var kpiTopBandSub = document.getElementById('dxcKpiTopBandSub');

    var isRbnSpot = DXCluster.isRbnSpot;
    var getBandFromFrequency = DXCluster.getBandFromFrequency;
    var detectMode = DXCluster.detectMode;

    // Restore saved preferences
    var savedRbn = localStorage.getItem('cloudlog_hideRbnSpots');
    if (savedRbn !== null) {
        hideRbnSpots = savedRbn === 'true';
        hideRbnCheckbox.checked = hideRbnSpots;
    }
    var savedBand = localStorage.getItem('cloudlog_bandFilter');
    if (savedBand) selectedBand = savedBand;
    var savedMode = localStorage.getItem('cloudlog_modeFilter');
    if (savedMode) selectedMode = savedMode;
    var savedNeed = localStorage.getItem('cloudlog_newDxccFilter');
    if (savedNeed) selectedNewDxcc = savedNeed;
    if (pageSizeSelect) pageSizeSelect.value = String(pageSize);

    function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function updateSoundButton() {
        if (!soundBtn) return;
        soundBtn.setAttribute('aria-pressed', soundEnabled ? 'true' : 'false');
        soundBtn.classList.toggle('btn-primary', soundEnabled);
        soundBtn.classList.toggle('btn-outline-secondary', !soundEnabled);
        soundBtn.innerHTML = soundEnabled
            ? '<i class="fas fa-volume-up me-1"></i><span>Alerts On</span>'
            : '<i class="fas fa-volume-mute me-1"></i><span>Alerts Off</span>';
    }

    function playAlertChime(isNewDxcc) {
        if (!soundEnabled) return;
        try {
            var AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            var ctx = new AudioCtx();
            var now = ctx.currentTime;
            var freqs = isNewDxcc ? [880, 1174.66] : [659.25, 880];
            freqs.forEach(function(f, idx) {
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(f, now + idx * 0.11);
                gain.gain.setValueAtTime(0.0001, now + idx * 0.11);
                gain.gain.exponentialRampToValueAtTime(0.08, now + idx * 0.11 + 0.02);
                gain.gain.exponentialRampToValueAtTime(0.0001, now + idx * 0.11 + 0.22);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now + idx * 0.11);
                osc.stop(now + idx * 0.11 + 0.24);
            });
            setTimeout(function() { ctx.close(); }, 600);
        } catch (e) {}
    }

    function updateQsyTip() {
        if (!qsyTip || !radioSelect) return;
        if (radioSelect.value && radioSelect.value !== '0') {
            var name = radioSelect.options[radioSelect.selectedIndex].text;
            qsyTip.innerHTML = '<i class="fas fa-broadcast-tower text-success me-1"></i>Click freq to QSY <strong>' + escapeHtml(name) + '</strong>';
        } else {
            qsyTip.innerHTML = '<i class="fas fa-mouse-pointer me-1"></i>Click any frequency to copy MHz (or select a radio to QSY)';
        }
    }

    function syncActivePills() {
        document.querySelectorAll('#dxcBandStrip .dxc-band-pill').forEach(function(btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-band') === selectedBand);
        });
        document.querySelectorAll('#dxcModePills .dxped-pill').forEach(function(btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-mode') === selectedMode);
        });
        document.querySelectorAll('#dxcNeedPills .dxped-pill').forEach(function(btn) {
            btn.classList.toggle('is-active', btn.getAttribute('data-need') === selectedNewDxcc);
        });
        var hasActiveFilter = (selectedBand !== 'all' || selectedMode !== 'all' || selectedNewDxcc !== 'all' || searchQuery !== '');
        if (resetBtn) resetBtn.classList.toggle('d-none', !hasActiveFilter);
    }

    function applyCwRbnLock() {
        if (selectedMode === 'cw') {
            if (hideRbnSpots) {
                hideRbnSpots = false;
                hideRbnCheckbox.checked = false;
                localStorage.setItem('cloudlog_hideRbnSpots', 'false');
            }
            hideRbnCheckbox.disabled = true;
            hideRbnCheckbox.title = 'Cannot hide RBN while CW mode is selected';
        } else {
            hideRbnCheckbox.disabled = false;
            hideRbnCheckbox.title = '';
        }
    }

    applyCwRbnLock();
    syncActivePills();
    updateSoundButton();
    updateQsyTip();

    // Event bindings
    if (radioSelect) {
        radioSelect.addEventListener('change', updateQsyTip);
    }

    document.querySelectorAll('#dxcBandStrip .dxc-band-pill').forEach(function(btn) {
        btn.addEventListener('click', function() {
            selectedBand = this.getAttribute('data-band');
            localStorage.setItem('cloudlog_bandFilter', selectedBand);
            currentPage = 1;
            syncActivePills();
            renderNow();
        });
    });

    document.querySelectorAll('#dxcModePills .dxped-pill').forEach(function(btn) {
        btn.addEventListener('click', function() {
            selectedMode = this.getAttribute('data-mode');
            localStorage.setItem('cloudlog_modeFilter', selectedMode);
            currentPage = 1;
            applyCwRbnLock();
            syncActivePills();
            renderNow();
        });
    });

    document.querySelectorAll('#dxcNeedPills .dxped-pill').forEach(function(btn) {
        btn.addEventListener('click', function() {
            selectedNewDxcc = this.getAttribute('data-need');
            localStorage.setItem('cloudlog_newDxccFilter', selectedNewDxcc);
            currentPage = 1;
            syncActivePills();
            renderNow();
        });
    });

    hideRbnCheckbox.addEventListener('change', function() {
        hideRbnSpots = this.checked;
        localStorage.setItem('cloudlog_hideRbnSpots', hideRbnSpots.toString());
        currentPage = 1;
        renderNow();
    });

    searchInput.addEventListener('input', function() {
        searchQuery = this.value.trim().toLowerCase();
        currentPage = 1;
        syncActivePills();
        scheduleRender();
    });

    resetBtn.addEventListener('click', function() {
        selectedBand = 'all';
        selectedMode = 'all';
        selectedNewDxcc = 'all';
        searchQuery = '';
        searchInput.value = '';
        localStorage.setItem('cloudlog_bandFilter', 'all');
        localStorage.setItem('cloudlog_modeFilter', 'all');
        localStorage.setItem('cloudlog_newDxccFilter', 'all');
        currentPage = 1;
        applyCwRbnLock();
        syncActivePills();
        renderNow();
    });

    // KPI Card clicks
    document.querySelectorAll('.dxped-kpi-card[data-dxc-kpi]').forEach(function(card) {
        card.addEventListener('click', function() {
            var action = this.getAttribute('data-dxc-kpi');
            if (action === 'all') {
                selectedBand = 'all';
                selectedMode = 'all';
                selectedNewDxcc = 'all';
                searchQuery = '';
                searchInput.value = '';
            } else if (action === 'new_dxcc' || action === 'new_band') {
                selectedNewDxcc = (selectedNewDxcc === action) ? 'all' : action;
            } else if (action === 'top_band' && topBandName) {
                selectedBand = (selectedBand === topBandName) ? 'all' : topBandName;
            }
            localStorage.setItem('cloudlog_bandFilter', selectedBand);
            localStorage.setItem('cloudlog_modeFilter', selectedMode);
            localStorage.setItem('cloudlog_newDxccFilter', selectedNewDxcc);
            currentPage = 1;
            applyCwRbnLock();
            syncActivePills();
            renderNow();
        });
    });

    pauseBtn.addEventListener('click', function() {
        isPaused = !isPaused;
        if (isPaused) {
            pausedNewCount = 0;
            pauseBtn.classList.remove('btn-outline-secondary');
            pauseBtn.classList.add('btn-warning');
            pauseBtn.innerHTML = '<i class="fas fa-play me-1"></i><span>Resume</span>';
            updateConnectionPill('paused', 'Paused');
        } else {
            pauseBtn.classList.remove('btn-warning');
            pauseBtn.classList.add('btn-outline-secondary');
            pauseBtn.innerHTML = '<i class="fas fa-pause me-1"></i><span>Pause</span>';
            if (ws && ws.readyState === WebSocket.OPEN) {
                updateConnectionPill('connected', 'Live');
            }
            pausedNewCount = 0;
            renderNow();
        }
    });

    soundBtn.addEventListener('click', function() {
        soundEnabled = !soundEnabled;
        localStorage.setItem('cloudlog_dxcSound', soundEnabled ? 'true' : 'false');
        updateSoundButton();
        if (soundEnabled) {
            playAlertChime(false);
        }
    });

    pageSizeSelect.addEventListener('change', function() {
        pageSize = parseInt(this.value, 10) || 50;
        localStorage.setItem('cloudlog_dxcPageSize', String(pageSize));
        currentPage = 1;
        renderNow();
    });

    prevPageBtn.addEventListener('click', function() {
        if (currentPage > 1) { currentPage--; renderNow(); }
    });

    nextPageBtn.addEventListener('click', function() {
        currentPage++;
        renderNow();
    });

    function updateConnectionPill(state, label) {
        connectionStatus.className = 'dxc-status-pill is-' + state;
        connectionStatusText.textContent = label;
    }

    function getStatusKey(callsign, band) {
        return String(callsign || '').toUpperCase() + '|' + String(band || '');
    }

    function getWorkedStatus(spot) {
        var band = getBandFromFrequency(spot.frequency);
        return workedStatus[getStatusKey(spot.dx, band)] || null;
    }

    function matchesNewDxccFilter(spot) {
        if (selectedNewDxcc === 'all') return true;
        var status = getWorkedStatus(spot);
        if (!status || !status.dxcc) return false;
        if (selectedNewDxcc === 'new_any') {
            return !status.dxcc_worked_overall || !status.dxcc_worked_on_band;
        }
        if (selectedNewDxcc === 'new_dxcc') {
            return !status.dxcc_worked_overall;
        }
        if (selectedNewDxcc === 'new_band') {
            return status.dxcc_worked_overall && !status.dxcc_worked_on_band;
        }
        return true;
    }

    function matchesSearch(spot, status) {
        if (!searchQuery) return true;
        var hay = [
            spot.dx || '',
            spot.spotter || '',
            spot.comment || '',
            spot.frequency || '',
            status && status.country ? status.country : ''
        ].join(' ').toLowerCase();
        return hay.indexOf(searchQuery) !== -1;
    }

    function formatTime(timeString) {
        if (!timeString) return '—';
        var s = String(timeString).trim();
        if (!s || s === '0' || s === 'null' || s === 'undefined') return '—';
        return s.endsWith('Z') ? s : (s + 'Z');
    }

    function calculateAge(receivedAt) {
        var diffMin = Math.floor((Date.now() - receivedAt) / 60000);
        if (diffMin < 1) return 'Just now';
        if (diffMin < 60) return diffMin + 'm ago';
        var h = Math.floor(diffMin / 60);
        var m = diffMin % 60;
        return h + 'h ' + m + 'm';
    }

    function formatComment(raw) {
        var safe = escapeHtml(raw || '');
        if (!safe) return '<span class="text-muted">—</span>';
        // Highlight useful DX keywords (POTA, SOTA, IOTA, WWFF, UP, QSX, SPLIT, FT8, FT4)
        return safe.replace(/\b(POTA|SOTA|IOTA|WWFF|UP\s*\d*(?:\.\d+)?|QSX\s*\d*(?:\.\d+)?|SPLIT|FT8|FT4|RTTY)\b/gi, function(m) {
            return '<span class="dxc-tag-kw">' + m + '</span>';
        });
    }

    function getCatModeForSpot(spot) {
        var m = detectMode(spot);
        var comment = ((spot.comment || '') + '').toUpperCase();
        if (m === 'cw') return 'CW';
        if (m === 'digi') {
            if (comment.indexOf('FT4') !== -1) return 'FT4';
            if (comment.indexOf('RTTY') !== -1) return 'RTTY';
            return 'FT8';
        }
        var freq = parseFloat(spot.frequency);
        return (!isNaN(freq) && freq < 10000) ? 'LSB' : 'USB';
    }

    function scheduleRender() {
        if (isPaused) return;
        if (renderTimer) return;
        renderTimer = setTimeout(function() {
            renderTimer = null;
            renderNow();
        }, 180);
    }

    function renderNow() {
        var now = Date.now();
        var allSpots = Array.from(spots.values()).sort(function(a, b) {
            return b.receivedAt - a.receivedAt;
        });

        // Base-filtered spots (respecting Hide RBN + Mode for band activity strip)
        var bandCounts = { all: 0 };
        var newDxccCount = 0;
        var newBandCount = 0;
        var recentFiveMin = 0;
        var filtered = [];

        allSpots.forEach(function(spot) {
            if (hideRbnSpots && isRbnSpot(spot.spotter)) return;
            var mode = detectMode(spot);
            if (selectedMode !== 'all' && mode !== selectedMode) return;

            var band = getBandFromFrequency(spot.frequency);
            bandCounts.all++;
            if (band && band !== 'unknown') {
                bandCounts[band] = (bandCounts[band] || 0) + 1;
            }

            if (now - spot.receivedAt <= 5 * 60 * 1000) {
                recentFiveMin++;
            }

            var status = getWorkedStatus(spot);
            if (status && status.dxcc) {
                if (!status.dxcc_worked_overall) newDxccCount++;
                else if (!status.dxcc_worked_on_band) newBandCount++;
            }

            if (selectedBand !== 'all' && band !== selectedBand) return;
            if (!matchesNewDxccFilter(spot)) return;
            if (!matchesSearch(spot, status)) return;

            filtered.push({ spot: spot, band: band, mode: mode, status: status });
        });

        // Update Band Activity Strip counts
        var bestBand = '';
        var bestCount = 0;
        document.querySelectorAll('#dxcBandStrip .dxc-band-pill').forEach(function(btn) {
            var b = btn.getAttribute('data-band');
            var count = bandCounts[b] || 0;
            var countEl = btn.querySelector('[data-band-count]');
            if (countEl) countEl.textContent = String(count);
            btn.classList.toggle('has-spots', count > 0);
            if (b !== 'all' && count > bestCount) {
                bestCount = count;
                bestBand = b;
            }
        });

        // Update KPI cards
        topBandName = bestBand;
        kpiTotal.textContent = String(filtered.length);
        var ratePerMin = (recentFiveMin / 5).toFixed(1);
        kpiRate.textContent = bandCounts.all + ' active · ' + ratePerMin + '/min';
        kpiNewDxcc.textContent = String(newDxccCount);
        kpiNewBand.textContent = String(newBandCount);
        if (bestBand) {
            kpiTopBand.textContent = bestBand === 'ghz' ? 'GHz+' : bestBand;
            kpiTopBandSub.textContent = bestCount + ' spot' + (bestCount === 1 ? '' : 's') + ' · Click to filter';
        } else {
            kpiTopBand.textContent = '—';
            kpiTopBandSub.textContent = 'Waiting for activity';
        }

        // Pagination
        var totalFiltered = filtered.length;
        var totalPages = Math.max(1, Math.ceil(totalFiltered / pageSize));
        if (currentPage > totalPages) currentPage = totalPages;
        var startIdx = (currentPage - 1) * pageSize;
        var endIdx = Math.min(startIdx + pageSize, totalFiltered);
        var pageItems = filtered.slice(startIdx, endIdx);

        if (totalFiltered === 0) {
            var emptyText = (spots.size === 0)
                ? 'Connected — waiting for live DX Cluster spots…'
                : 'No spots match your current filters.';
            spotsTableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">'
                + '<i class="fas fa-satellite-dish fa-lg mb-2 d-block opacity-50"></i>'
                + '<span>' + escapeHtml(emptyText) + '</span></td></tr>';
            shownInfo.textContent = '0 spots';
        } else {
            var rowsHtml = pageItems.map(function(item) {
                var spot = item.spot;
                var band = item.band;
                var mode = item.mode;
                var status = item.status;
                var ageMs = now - spot.receivedAt;
                var isFresh = ageMs < 3 * 60 * 1000;
                var isOld = ageMs > 60 * 60 * 1000;
                var isNewDxcc = status && status.dxcc && !status.dxcc_worked_overall;
                var isNewBand = status && status.dxcc && status.dxcc_worked_overall && !status.dxcc_worked_on_band;

                var rowClasses = ['dxc-row'];
                if (isNewDxcc) rowClasses.push('is-new-dxcc');
                else if (isNewBand) rowClasses.push('is-new-band');
                else if (isFresh) rowClasses.push('is-fresh');
                if (isOld) rowClasses.push('is-old');

                // Worked icon
                var workedIcon = '';
                if (status) {
                    if (status.worked_on_band) {
                        workedIcon = '<i class="fas fa-check text-success ms-1" title="Worked on ' + escapeHtml(status.band) + '"></i>';
                    } else if (status.worked_overall) {
                        workedIcon = '<i class="fas fa-check text-info ms-1" title="Worked on another band"></i>';
                    } else {
                        workedIcon = '<i class="fas fa-circle text-muted ms-1 opacity-25" style="font-size:0.45rem;vertical-align:middle;" title="Not worked yet"></i>';
                    }
                }

                var callClass = (status && status.worked_on_band) ? 'dxped-call-chip is-worked-call' : 'dxped-call-chip';
                var entityBadge = (status && status.country)
                    ? '<span class="dxc-tag-entity">' + escapeHtml(status.country) + '</span>'
                    : '';
                var needBadge = '';
                if (isNewDxcc) {
                    needBadge = '<span class="dxc-tag-new-dxcc" title="New DXCC Entity (never worked)"><i class="fas fa-star me-1"></i>New DXCC</span>';
                } else if (isNewBand) {
                    needBadge = '<span class="dxc-tag-new-band" title="New DXCC on this band">New Band</span>';
                }

                var freqNum = parseFloat(spot.frequency);
                var freqDisplay = isNaN(freqNum) ? escapeHtml(spot.frequency) : freqNum.toFixed(1);
                var catMode = getCatModeForSpot(spot);
                var modeLabel = (mode === 'digi') ? 'DIGI' : mode.toUpperCase();
                var modeClass = 'dxc-mode-' + mode;
                var bandLabel = (band && band !== 'unknown') ? (band === 'ghz' ? 'GHz+' : band) : '';
                var spotterIsRbn = isRbnSpot(spot.spotter);

                return '<tr class="' + rowClasses.join(' ') + '">'
                    + '<td class="text-nowrap">'
                        + '<div class="fw-semibold font-monospace small">' + escapeHtml(formatTime(spot.time)) + '</div>'
                        + '<div class="dxc-age-sub">' + escapeHtml(calculateAge(spot.receivedAt)) + '</div>'
                    + '</td>'
                    + '<td>'
                        + '<div class="d-flex flex-wrap align-items-center gap-1">'
                            + '<a href="https://www.qrz.com/db/' + encodeURIComponent(spot.dx || '') + '" target="_blank" rel="noopener" class="' + callClass + ' text-decoration-none">' + escapeHtml(spot.dx || '') + '</a>'
                            + workedIcon
                            + needBadge
                            + entityBadge
                        + '</div>'
                    + '</td>'
                    + '<td class="text-nowrap">'
                        + '<button type="button" class="dxc-freq-btn" onclick="handleFrequencyClick(\'' + escapeHtml(spot.frequency) + '\', \'' + escapeHtml(catMode) + '\')" title="Click to QSY (' + escapeHtml(catMode) + ') or copy MHz">'
                            + '<i class="fas fa-broadcast-tower me-1 opacity-75"></i>' + freqDisplay + ' <span class="opacity-75">kHz</span>'
                        + '</button>'
                        + '<div class="d-flex align-items-center gap-1 mt-1">'
                            + (bandLabel ? '<span class="dxc-band-mini">' + escapeHtml(bandLabel) + '</span>' : '')
                            + '<span class="dxc-mode-mini ' + modeClass + '">' + modeLabel + '</span>'
                        + '</div>'
                    + '</td>'
                    + '<td class="text-nowrap small">'
                        + '<span class="font-monospace">' + escapeHtml(spot.spotter || '') + '</span>'
                        + (spotterIsRbn ? ' <span class="dxc-tag-rbn">RBN</span>' : '')
                    + '</td>'
                    + '<td class="dxc-comment-cell small">' + formatComment(spot.comment) + '</td>'
                    + '<td class="text-end text-nowrap">'
                        + '<button type="button" class="btn btn-outline-primary btn-sm dxped-act-btn me-1" onclick="handleFrequencyClick(\'' + escapeHtml(spot.frequency) + '\', \'' + escapeHtml(catMode) + '\')" title="QSY / Copy Frequency"><i class="fas fa-crosshairs"></i></button>'
                        + '<a href="https://www.qrz.com/db/' + encodeURIComponent(spot.dx || '') + '" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm dxped-act-btn" title="Lookup on QRZ"><i class="fas fa-external-link-alt"></i></a>'
                    + '</td>'
                + '</tr>';
            }).join('');

            spotsTableBody.innerHTML = rowsHtml;
            shownInfo.textContent = 'Showing ' + (startIdx + 1) + '–' + endIdx + ' of ' + totalFiltered;
        }

        pageIndicator.textContent = currentPage + ' / ' + totalPages;
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;

        // Schedule worked-status batch lookup
        clearTimeout(checkWorkedTimeout);
        checkWorkedTimeout = setTimeout(checkWorkedStatus, 450);
    }

    async function checkWorkedStatus() {
        var callsignsToCheck = [];
        var seen = new Set();

        Array.from(spots.values()).forEach(function(spot) {
            if (!spot.dx) return;
            if (hideRbnSpots && isRbnSpot(spot.spotter)) return;
            if (selectedMode !== 'all' && detectMode(spot) !== selectedMode) return;

            var band = getBandFromFrequency(spot.frequency);
            var key = getStatusKey(spot.dx, band);
            if (workedStatus[key] || seen.has(key)) return;

            // Avoid duplicate callsign in same batch (backend keys results by callsign)
            var callUpper = spot.dx.toUpperCase();
            if (seen.has('CALL:' + callUpper)) return;

            callsignsToCheck.push({ callsign: spot.dx, band: band });
            seen.add(key);
            seen.add('CALL:' + callUpper);
        });

        if (callsignsToCheck.length === 0) return;

        var batch = callsignsToCheck.slice(0, 35);
        try {
            var response = await fetch('<?php echo site_url('dxcluster/check_worked'); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ callsigns: batch })
            });
            var data = await response.json();
            if (data && data.success && data.results) {
                var shouldChimeDxcc = false;
                var shouldChimeBand = false;

                Object.keys(data.results).forEach(function(call) {
                    var res = data.results[call];
                    var key = getStatusKey(call, res.band);
                    workedStatus[key] = res;

                    if (res.dxcc && !alertedKeys.has(key)) {
                        if (!res.dxcc_worked_overall) {
                            shouldChimeDxcc = true;
                            alertedKeys.add(key);
                        } else if (!res.dxcc_worked_on_band) {
                            shouldChimeBand = true;
                            alertedKeys.add(key);
                        }
                    }
                });

                if (shouldChimeDxcc) playAlertChime(true);
                else if (shouldChimeBand) playAlertChime(false);

                renderNow();
            }
        } catch (err) {
            console.error('Error checking worked status:', err);
        }
    }

    function addSpot(spot) {
        var spotId = (spot.dx || '') + '-' + (spot.frequency || '') + '-' + Date.now() + '-' + Math.random().toString(36).slice(2, 6);
        spot.receivedAt = Date.now();
        spots.set(spotId, spot);

        // Trim spots older than 2 hours
        var cutoff = Date.now() - (2 * 60 * 60 * 1000);
        spots.forEach(function(existing, id) {
            if (existing.receivedAt < cutoff) {
                spots.delete(id);
            }
        });

        if (isPaused) {
            pausedNewCount++;
            updateConnectionPill('paused', 'Paused (+' + pausedNewCount + ')');
        } else {
            scheduleRender();
        }
    }

    function connect() {
        updateConnectionPill('connecting', 'Connecting...');
        ws = new WebSocket('wss://dxc.cloudlog.org');

        ws.onopen = function() {
            if (!isPaused) updateConnectionPill('connected', 'Live');
        };

        ws.onmessage = function(event) {
            try {
                var data = JSON.parse(event.data);
                if (data && data.type === 'spot') {
                    addSpot(data);
                }
            } catch (e) {}
        };

        ws.onclose = function() {
            updateConnectionPill('disconnected', 'Reconnecting...');
            setTimeout(connect, 3000);
        };

        ws.onerror = function() {
            updateConnectionPill('disconnected', 'Error');
        };
    }

    // Refresh relative ages every 30s
    setInterval(function() {
        if (!isPaused && spots.size > 0) {
            renderNow();
        }
    }, 30000);

    connect();
});

function handleFrequencyClick(frequency, mode) {
    var radioId = document.getElementById('radio').value;
    var freqInMHz = (parseFloat(frequency) / 1000).toFixed(3);

    if (radioId === '0') {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(freqInMHz).then(function() {
                showToast('Copied ' + freqInMHz + ' MHz to clipboard', 'info');
            });
        }
        return;
    }

    var payload = { radio_id: radioId, frequency: freqInMHz };
    if (mode) payload.mode = mode;

    fetch('<?php echo site_url('dxcluster/qsy'); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        if (data.success) {
            showToast('QSY sent: ' + freqInMHz + ' MHz' + (mode ? ' (' + mode + ')' : ''), 'success');
        } else {
            showToast('Failed to QSY radio: ' + data.message, 'danger');
        }
    })
    .catch(function(err) {
        console.error('Error sending QSY command:', err);
        showToast('Error sending QSY command', 'danger');
    });
}

function showToast(message, type) {
    type = type || 'primary';
    var toast = document.createElement('div');
    toast.className = 'toast align-items-center text-white bg-' + type + ' border-0 position-fixed top-0 end-0 m-3';
    toast.setAttribute('role', 'alert');
    toast.style.zIndex = '9999';
    toast.innerHTML = '<div class="d-flex"><div class="toast-body"></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    toast.querySelector('.toast-body').textContent = message;
    document.body.appendChild(toast);

    var bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    setTimeout(function() { toast.remove(); }, 3200);
}
</script>
