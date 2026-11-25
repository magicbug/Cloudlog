<?php $is_manual = (isset($_GET['manual']) && $_GET['manual'] == 1); ?>
<div class="container qso_panel contesting contesting-cockpit">
    <form id="qso_input" name="qsos">
        <!-- 1. TOP COCKPIT HEADER BAR (Always visible: Title, LIVE/POST, UTC Clock, Rig/Band/Mode/Freq, Actions) -->
        <div class="card contesting-topbar mb-3">
            <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <!-- Left: Title + LIVE/POST Pill + Active Contest Meta -->
                <div class="d-flex align-items-center gap-2">
                    <span class="contesting-brand-icon d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-trophy"></i>
                    </span>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h2 class="h5 mb-0 fw-bold"><?php echo lang('contesting_page_title'); ?></h2>
                            <?php if (!$is_manual) { ?>
                                <span style="cursor: pointer;" class="badge rounded-pill text-bg-success contesting-mode-pill" onclick="window.location.href='<?php echo site_url('contesting'); ?>?manual=1'" title="Switch to POST mode">
                                    <i class="fas fa-circle fa-2xs me-1"></i>LIVE
                                </span>
                            <?php } else { ?>
                                <span style="cursor: pointer;" class="badge rounded-pill text-bg-danger contesting-mode-pill" onclick="window.location.href='<?php echo site_url('contesting'); ?>?manual=0'" title="Switch to LIVE mode">
                                    <i class="fas fa-pen fa-2xs me-1"></i>POST
                                </span>
                            <?php } ?>
                        </div>
                        <div class="small text-body-secondary contesting-meta-line">
                            <span id="hdr-contest-name" class="fw-medium">Contest</span>
                            <span class="mx-1">&middot;</span>
                            <span>Op: <strong id="hdr-op" class="font-monospace"><?php echo htmlspecialchars($this->session->userdata('operator_callsign') ?: $this->session->userdata('user_callsign')); ?></strong></span>
                            <?php if (!empty($my_gridsquare)) { ?>
                                <span class="mx-1">&middot;</span>
                                <span>Grid: <strong class="font-monospace"><?php echo htmlspecialchars($my_gridsquare); ?></strong></span>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Center: Always-Visible UTC Clock + Band / Mode / Frequency Readout -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <?php if (!$is_manual) { ?>
                        <div class="contesting-clock-pill px-3 py-1">
                            <div class="font-monospace fw-bold contesting-clock-time"><span id="contest-utc-clock"><?php echo gmdate('H:i:s'); ?></span> <span class="contesting-utc-tag">UTC</span></div>
                            <div class="font-monospace contesting-clock-date" id="contest-utc-date"><?php echo gmdate('d-m-Y'); ?></div>
                        </div>
                        <input class="input_time" type="hidden" id="start_time" name="start_time" value="<?php echo gmdate('H:i:s'); ?>" />
                        <input class="input_date" type="hidden" id="start_date" name="start_date" value="<?php echo gmdate('d-m-Y'); ?>" />
                    <?php } else { ?>
                        <div class="d-flex align-items-center gap-1 contesting-post-datetime">
                            <input type="text" class="form-control form-control-sm input_date font-monospace" name="start_date" id="start_date" aria-label="<?php echo lang('general_word_date'); ?>" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_date'); } else { echo gmdate('d-m-Y'); } ?>" style="width: 105px;">
                            <input type="text" class="form-control form-control-sm input_time font-monospace" name="start_time" id="start_time" aria-label="<?php echo lang('general_word_time'); ?>" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo substr($this->session->userdata('start_time'), 0, 5); } else { echo gmdate('H:i'); } ?>" style="width: 78px;">
                        </div>
                    <?php } ?>

                    <!-- Compact Rig / Band / Mode / Frequency Bar -->
                    <div class="contesting-rig-pill d-flex align-items-center gap-1 px-2 py-1">
                        <select id="band" class="form-select form-select-sm contesting-inline-select fw-bold" name="band" aria-label="<?php echo lang('gen_hamradio_band'); ?>">
                            <?php foreach ($bands as $key => $bandgroup) {
                                echo '<optgroup label="' . strtoupper($key) . '">';
                                foreach ($bandgroup as $band) {
                                    echo '<option value="' . $band . '"';
                                    if ($this->session->userdata('band') == $band) echo ' selected';
                                    echo '>' . $band . '</option>' . "\n";
                                }
                                echo '</optgroup>';
                            } ?>
                        </select>
                        <span class="contesting-pill-sep">|</span>
                        <select id="mode" class="form-select form-select-sm mode contesting-inline-select fw-semibold" name="mode" aria-label="<?php echo lang('gen_hamradio_mode'); ?>">
                            <?php foreach ($modes->result() as $mode) {
                                if ($mode->submode == null) {
                                    printf("<option value=\"%s\" %s>%s</option>", $mode->mode, $this->session->userdata('mode') == $mode->mode ? "selected=\"selected\"" : "", $mode->mode);
                                } else {
                                    printf("<option value=\"%s\" %s>&rArr; %s</option>", $mode->submode, $this->session->userdata('mode') == $mode->submode ? "selected=\"selected\"" : "", $mode->submode);
                                }
                            } ?>
                        </select>
                        <span class="contesting-pill-sep">|</span>
                        <input type="text" class="form-control form-control-sm contesting-inline-freq font-monospace" id="frequency" name="freq_display" aria-label="<?php echo lang('gen_hamradio_frequency'); ?>" value="<?php echo $this->session->userdata('freq'); ?>" placeholder="Freq (Hz)" />
                    </div>
                </div>

                <!-- Right: Session Setup Drawer Toggle + Actions -->
                <div class="d-flex flex-wrap align-items-center gap-1">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#contest-settings-panel" aria-expanded="false" aria-controls="contest-settings-panel" title="Toggle session settings">
                        <i class="fas fa-sliders-h me-1"></i>Setup <i class="fas fa-chevron-right fa-xs ms-1" id="settings-chevron"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openBandmap()" title="Open DX Cluster Bandmap">
                        <i class="fas fa-chart-line me-1"></i><?php echo lang('menu_bandmap'); ?>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#cabrilloExportModal" title="Export contest log as Cabrillo file">
                        <i class="fas fa-download me-1"></i>Cabrillo
                    </button>
                    <button type="button" class="btn btn-sm btn-warning text-dark" onclick="reset_contest_session()" title="<?php echo lang('contesting_button_reset_contest_session'); ?>">
                        <i class="fas fa-sync-alt me-1"></i>New Session
                    </button>
                </div>
            </div>

            <!-- Collapsible Static Session Setup Drawer (Never hides UTC Clock or Band/Mode/Freq) -->
            <div id="contest-settings-panel" class="collapse border-top">
                <div class="card-body py-2 px-3 contesting-setup-drawer">
                    <div class="row g-2 align-items-end">
                        <div class="col-sm-6 col-lg-3">
                            <label class="form-label form-label-sm mb-1" for="contestname"><?php echo lang('contesting_contest_name'); ?></label>
                            <select class="form-select form-select-sm" id="contestname" name="contestname">
                                <?php foreach ($contestnames as $contest) {
                                    echo "<option value='" . htmlspecialchars($contest['adifname'], ENT_QUOTES) . "'>" . htmlspecialchars($contest['name']) . "</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-sm-6 col-lg-2">
                            <label class="form-label form-label-sm mb-1" for="exchangetype"><?php echo lang('contesting_exchange_type'); ?></label>
                            <select class="form-select form-select-sm" id="exchangetype" name="exchangetype">
                                <option value='None'><?php echo lang('contesting_exchange_type_none'); ?></option>
                                <option value='Exchange'><?php echo lang('contesting_exchange_type_exchange'); ?></option>
                                <option value='Gridsquare'><?php echo lang('contesting_exchange_type_gridsquare'); ?></option>
                                <option value='Serial'><?php echo lang('contesting_exchange_type_serial'); ?></option>
                                <option value='Serialexchange'><?php echo lang('contesting_exchange_type_serial_exchange'); ?></option>
                                <option value='Serialgridsquare'><?php echo lang('contesting_exchange_type_serial_gridsquare'); ?></option>
                            </select>
                        </div>
                        <div class="col-sm-4 col-lg-2">
                            <label class="form-label form-label-sm mb-1" for="operator_callsign"><?php echo lang('contesting_operator_callsign'); ?></label>
                            <input type="text" class="form-control form-control-sm font-monospace text-uppercase" id="operator_callsign" name="operator_callsign" value="<?php echo htmlspecialchars($this->session->userdata('operator_callsign')); ?>" required>
                        </div>
                        <div class="col-sm-4 col-lg-2">
                            <label class="form-label form-label-sm mb-1" for="radio"><?php echo lang('gen_hamradio_radio'); ?></label>
                            <select class="form-select form-select-sm radios" id="radio" name="radio">
                                <option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
                                <?php foreach ($radios->result() as $row) { ?>
                                    <option value="<?php echo $row->id; ?>" <?php if ($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo htmlspecialchars($row->radio); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-4 col-lg-3">
                            <label class="form-label form-label-sm mb-1" for="copyexchangeto">Copy exchange to</label>
                            <select class="form-select form-select-sm" id="copyexchangeto" name="copyexchangeto">
                                <option value='None'><?php echo lang('contesting_copy_exch_to_none'); ?></option>
                                <option value='dok'><?php echo lang('contesting_copy_exch_to_dok'); ?></option>
                                <option value='name'><?php echo lang('contesting_copy_exch_to_name'); ?></option>
                                <option value='age'><?php echo lang('contesting_copy_exch_to_age'); ?></option>
                                <option value='state'><?php echo lang('contesting_copy_exch_to_state'); ?></option>
                                <option value='power'><?php echo lang('contesting_copy_exch_to_power'); ?></option>
                                <option value='locator'><?php echo lang('contesting_copy_exch_to_locator'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="radio_status"></div>

        <!-- 2. HERO SINGLE-ROW HIGH-SPEED QSO ENTRY CONSOLE -->
        <div class="card contesting-entry-card mb-3">
            <!-- Always-visible card-header (also anchors offline-qso-queue-badge cleanly!) -->
            <div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex flex-wrap align-items-center gap-2" id="callsign-badge-row">
                    <span id="callsign_ready_hint" class="badge text-bg-secondary bg-opacity-25 text-body-secondary">Ready &mdash; enter callsign</span>
                    <small id="callsign_info" class="badge"></small>
                    <small id="locator_info_contest_dxcc" class="badge text-bg-info" style="display:none"></small>
                    <small id="distance_contest_dxcc" class="badge text-bg-secondary" style="display:none"></small>
                </div>
                <div class="d-none d-md-flex align-items-center gap-2 small text-body-secondary contesting-shortcuts">
                    <span><kbd>Space</kbd> Next</span>
                    <span><kbd>Enter</kbd> Log</span>
                    <span><kbd>Esc</kbd> Wipe</span>
                </div>
            </div>

            <div class="card-body py-3 px-3">
                <div class="contesting-entry-row d-flex flex-wrap flex-lg-nowrap align-items-end gap-2">
                    <!-- Callsign (Hero Input) -->
                    <div class="contesting-field-callsign">
                        <label for="callsign" class="contesting-field-label"><?php echo lang('gen_hamradio_callsign'); ?></label>
                        <input type="text" class="form-control contesting-hero-input font-monospace fw-bold" id="callsign" name="callsign" required pattern="\S+" title="Whitespace is not allowed" autocomplete="off" autocorrect="off" autocapitalize="characters" spellcheck="false" placeholder="CALLSIGN">
                    </div>

                    <!-- Sent RST -->
                    <div class="contesting-field-rst">
                        <label for="rst_sent" class="contesting-field-label text-center"><?php echo lang('gen_hamradio_rsts'); ?></label>
                        <input type="text" class="form-control contesting-mid-input text-center font-monospace text-body-secondary" name="rst_sent" id="rst_sent" value="59">
                    </div>

                    <!-- Sent Serial -->
                    <div style="display:none" class="contesting-field-serial serials">
                        <label for="exch_serial_s" class="contesting-field-label text-center"><?php echo lang('contesting_exchange_serial_s'); ?></label>
                        <input type="number" class="form-control contesting-mid-input text-center font-monospace fw-bold" name="exch_serial_s" id="exch_serial_s" value="">
                    </div>

                    <!-- Sent Exchange -->
                    <div style="display:none" class="contesting-field-exch exchanges">
                        <label for="exch_sent" class="contesting-field-label text-center"><?php echo lang('gen_hamradio_exchange_sent_short'); ?></label>
                        <input type="text" class="form-control contesting-mid-input text-center font-monospace" name="exch_sent" id="exch_sent" value="">
                    </div>

                    <!-- Sent Gridsquare -->
                    <div style="display:none" class="contesting-field-grid gridsquares">
                        <label for="exch_gridsquare_s" class="contesting-field-label text-center"><?php echo lang('contesting_exchange_gridsquare_s'); ?></label>
                        <input disabled type="text" class="form-control contesting-mid-input text-center font-monospace" name="exch_gridsquare_s" id="exch_gridsquare_s" value="<?php echo htmlspecialchars($my_gridsquare); ?>">
                    </div>

                    <!-- Rcvd RST -->
                    <div class="contesting-field-rst">
                        <label for="rst_rcvd" class="contesting-field-label text-center"><?php echo lang('gen_hamradio_rstr'); ?></label>
                        <input type="text" class="form-control contesting-mid-input text-center font-monospace text-body-secondary" name="rst_rcvd" id="rst_rcvd" value="59">
                    </div>

                    <!-- Rcvd Serial (Hot field) -->
                    <div style="display:none" class="contesting-field-rcvd serialr">
                        <label for="exch_serial_r" class="contesting-field-label text-primary"><?php echo lang('contesting_exchange_serial_r'); ?></label>
                        <input type="number" class="form-control contesting-hero-input font-monospace fw-bold" name="exch_serial_r" id="exch_serial_r" value="" placeholder="001">
                    </div>

                    <!-- Rcvd Exchange (Hot field) -->
                    <div style="display:none" class="contesting-field-rcvd exchanger">
                        <label for="exch_rcvd" class="contesting-field-label text-primary"><?php echo lang('gen_hamradio_exchange_rcvd_short'); ?></label>
                        <input type="text" class="form-control contesting-hero-input font-monospace fw-bold" name="exch_rcvd" id="exch_rcvd" value="" placeholder="EXCH">
                    </div>

                    <!-- Rcvd Gridsquare (Hot field) -->
                    <div style="display:none" class="contesting-field-grid-r gridsquarer">
                        <label for="exch_gridsquare_r" class="contesting-field-label text-primary d-flex align-items-center justify-content-between">
                            <span><?php echo lang('contesting_exchange_gridsquare_r'); ?></span>
                            <small id="locator_info_contest" class="badge text-bg-info"></small>
                        </label>
                        <input type="text" class="form-control contesting-hero-input font-monospace fw-bold" name="locator" id="exch_gridsquare_r" value="" maxlength="8" placeholder="IO85">
                    </div>

                    <!-- Distance (Gridsquare mode) -->
                    <div style="display:none" class="contesting-field-dist gridsquarer">
                        <label for="distance_contest" class="contesting-field-label"><?php echo lang('gen_hamradio_distance'); ?></label>
                        <input type="text" class="form-control contesting-mid-input font-monospace" id="distance_contest" value="" disabled>
                    </div>

                    <!-- Inline Wipe & Log QSO Actions -->
                    <div class="contesting-entry-actions d-flex align-items-center gap-2 ms-lg-auto">
                        <button type="button" class="btn btn-outline-secondary contesting-action-btn" id="reset_qso" onclick="reset_log_fields()" title="<?php echo lang('contesting_btn_reset_qso'); ?> (Esc / Alt+W)">
                            <i class="fas fa-eraser me-1"></i><?php echo lang('contesting_btn_reset_qso'); ?>
                        </button>
                        <button type="button" class="btn btn-success contesting-action-btn px-4 fw-semibold" id="save_qso" onclick="logQso();">
                            <i class="fas fa-check me-1"></i><?php echo lang('contesting_btn_save_qso'); ?>
                        </button>
                    </div>
                </div>

                <!-- Collapsible Extra Fields (Name & Comment) -->
                <div class="mt-2 pt-2 border-top d-flex align-items-center justify-content-between">
                    <button type="button" class="btn btn-link btn-sm text-body-secondary p-0 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#extra-fields" aria-expanded="false" aria-controls="extra-fields" title="Name and comment">
                        <i class="fas fa-chevron-right fa-xs me-1" id="extra-chevron"></i><small>Extra fields (<?php echo lang('general_word_name'); ?>, <?php echo lang('general_word_comment'); ?>)</small>
                    </button>
                </div>
                <div id="extra-fields" class="collapse">
                    <div class="row g-2 mt-1">
                        <div class="col-md-6">
                            <label for="name" class="form-label form-label-sm mb-1"><?php echo lang('general_word_name'); ?></label>
                            <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                        </div>
                        <div class="col-md-6">
                            <label for="comment" class="form-label form-label-sm mb-1"><?php echo lang('general_word_comment'); ?></label>
                            <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- 3. TWO-COLUMN WORKSPACE: Contest Logbook (Left) + Telemetry & Zero-Shift Assist Sidebar (Right) -->
    <div class="row g-3 align-items-start">
        <!-- LEFT: Contest Logbook -->
        <div class="col-xl-8">
            <div class="card log contesting-log-card">
                <div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="card-title mb-0 fs-6 fw-semibold"><?php echo lang('contesting_title_contest_logbook'); ?></h5>
                        <span class="badge text-bg-primary rounded-pill" id="logbook-count-badge">0 QSOs</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <label for="logbook-search" class="text-body-secondary small mb-0 me-1">Search:</label>
                        <input type="search" id="logbook-search" class="form-control form-control-sm" style="width: 185px" placeholder="Filter callsign…">
                    </div>
                </div>
                <div class="contesting-table-wrap">
                    <table style="width:100%" class="table-sm table qsotable table-hover table-striped text-center mb-0">
                        <thead>
                            <tr class="log_title titles">
                                <th><?php echo lang('general_word_date'); ?>/<?php echo lang('general_word_time'); ?></th>
                                <th><?php echo lang('gen_hamradio_call'); ?></th>
                                <th><?php echo lang('gen_hamradio_band'); ?></th>
                                <th><?php echo lang('gen_hamradio_mode'); ?></th>
                                <th><?php echo lang('gen_hamradio_rsts'); ?></th>
                                <th><?php echo lang('gen_hamradio_rstr'); ?></th>
                                <th><?php echo lang('gen_hamradio_exchange_sent_short'); ?></th>
                                <th><?php echo lang('gen_hamradio_exchange_rcvd_short'); ?></th>
                                <th><?php echo lang('contesting_exchange_serial_s'); ?></th>
                                <th><?php echo lang('contesting_exchange_serial_r'); ?></th>
                                <th><?php echo lang('contesting_exchange_type_gridsquare'); ?></th>
                                <th><?php echo 'VUCC ' . lang('contesting_exchange_type_gridsquare'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="contest_qso_table_contents">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- RIGHT: Live Telemetry + Zero-Shift Assist Sidebar -->
        <div class="col-xl-4 d-flex flex-column gap-3">
            <!-- Session Telemetry Card -->
            <div class="card contesting-side-card mb-0" id="contest-stats-card">
                <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 small text-uppercase fw-bold text-body-secondary">Session Telemetry</h6>
                    <span class="badge text-bg-primary font-monospace" id="stats-next-serial" style="display:none">Next #001</span>
                </div>
                <div class="card-body py-2 px-3">
                    <div class="row g-2 text-center mb-2">
                        <div class="col-4">
                            <div class="contesting-stat-tile p-2">
                                <div class="contesting-stat-label">QSOs</div>
                                <div class="contesting-stat-value font-monospace" id="stats-total">0</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="contesting-stat-tile p-2">
                                <div class="contesting-stat-label">Rate/hr</div>
                                <div class="contesting-stat-value font-monospace text-info" id="stats-rate">0</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="contesting-stat-tile p-2">
                                <div class="contesting-stat-label">Unique</div>
                                <div class="contesting-stat-value font-monospace text-success" id="stats-unique">0</div>
                            </div>
                        </div>
                    </div>
                    <div class="contesting-stat-label mb-1">By Band <span class="fw-normal opacity-75">(click to QSY)</span></div>
                    <div id="stats-bands" class="d-flex flex-wrap gap-1">
                        <span class="small text-body-secondary">No QSOs logged yet</span>
                    </div>
                </div>
            </div>

            <!-- Super Check Partial (Fixed-slot card — zero layout shift!) -->
            <div class="card contesting-side-card callsign-suggest mb-0">
                <div class="card-header py-2 px-3 d-flex align-items-center justify-content-between">
                    <h6 class="card-title mb-0 small text-uppercase fw-bold text-body-secondary"><?php echo lang('contesting_title_callsign_suggestions'); ?></h6>
                    <span class="small font-monospace text-body-secondary" id="scp-match-count">&mdash;</span>
                </div>
                <div class="card-body py-2 px-3 callsign-suggestions contesting-assist-body">
                    <span class="small text-body-secondary">Type 3+ characters to match known contest callsigns&hellip;</span>
                </div>
            </div>

            <!-- Call History Membership (Fixed-slot card — zero layout shift!) -->
            <div class="card contesting-side-card mb-0" id="callhistory-info-panel">
                <div class="card-header py-2 px-3">
                    <h6 class="card-title mb-0 small text-uppercase fw-bold text-body-secondary">Call History Membership</h6>
                </div>
                <div class="card-body py-2 px-3 contesting-assist-body" id="callhistory-results">
                    <div class="small text-body-secondary">Type a callsign to see membership details from your uploaded call history files.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chevron icons for collapsibles
    document.getElementById('contest-settings-panel').addEventListener('hide.bs.collapse', function () {
        document.getElementById('settings-chevron').classList.replace('fa-chevron-down', 'fa-chevron-right');
    });
    document.getElementById('contest-settings-panel').addEventListener('show.bs.collapse', function () {
        document.getElementById('settings-chevron').classList.replace('fa-chevron-right', 'fa-chevron-down');
    });
    document.getElementById('extra-fields').addEventListener('show.bs.collapse', function () {
        document.getElementById('extra-chevron').classList.replace('fa-chevron-right', 'fa-chevron-down');
    });
    document.getElementById('extra-fields').addEventListener('hide.bs.collapse', function () {
        document.getElementById('extra-chevron').classList.replace('fa-chevron-down', 'fa-chevron-right');
    });

    function openBandmap() {
        // Open bandmap in a new window without URL bar, toolbars, etc.
        const width = 500; 
        const height = 800;
        const left = (screen.width - width) / 2;
        const top = (screen.height - height) / 2;
        
        // Note: Modern browsers may still show address bar due to security restrictions
        // For Chrome, you can use: chrome.exe --app=http://localhost/index.php/dxcluster/bandmap
        const features = `width=${width},height=${height},left=${left},top=${top},` +
                       `toolbar=no,location=no,directories=no,status=no,menubar=no,` +
                       `scrollbars=yes,resizable=yes,copyhistory=no`;
        
        const popup = window.open('<?php echo site_url('dxcluster/bandmap'); ?>', 'bandmap', features);
        
        // Try to make it fullscreen (user will need to allow this)
        if (popup) {
            popup.focus();
        }
    }
</script>

<!-- Cabrillo Export Modal -->
<div class="modal fade" id="cabrilloExportModal" tabindex="-1" aria-labelledby="cabrilloExportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cabrilloExportModalLabel"><i class="fas fa-download"></i> Export Cabrillo Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo site_url('cabrillo/export'); ?>" method="post">
            <div class="modal-body">
                <input type="hidden" name="station_id" value="<?php echo htmlspecialchars($active_station_id); ?>">
                <input type="hidden" name="contestid" value="<?php echo htmlspecialchars($contest_session ? $contest_session->contestid : ''); ?>">

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label fw-semibold">Contest:</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($contest_session ? $contest_session->contestid : 'No active contest session'); ?>">
                        <?php if (!$contest_session): ?>
                        <div class="form-text text-danger">No active contest session. Start a contest first on this page.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_from">Date From:</label>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="cab_from" name="contestdatesfrom" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <label class="col-md-2 col-form-label" for="cab_to">Date To:</label>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="cab_to" name="contestdatesto" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categoryoperator"><?php echo lang('export_cabrillo_cat_operator'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categoryoperator" name="categoryoperator">
                            <option value="SINGLE-OP"><?php echo lang('export_cabrillo_cat_operator_single_op'); ?></option>
                            <option value="MULTI-OP"><?php echo lang('export_cabrillo_cat_operator_multi_op'); ?></option>
                            <option value="CHECKLOG"><?php echo lang('export_cabrillo_cat_operator_checklog'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categoryassisted"><?php echo lang('export_cabrillo_cat_assisted'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categoryassisted" name="categoryassisted">
                            <option value="NON-ASSISTED"><?php echo lang('export_cabrillo_cat_assisted_not_ass'); ?></option>
                            <option value="ASSISTED"><?php echo lang('export_cabrillo_cat_assisted_ass'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categoryband"><?php echo lang('export_cabrillo_cat_band'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categoryband" name="categoryband">
                            <option value="ALL"><?php echo lang('general_word_all'); ?></option>
                            <option value="160M">160 M</option>
                            <option value="80M">80 M</option>
                            <option value="40M">40 M</option>
                            <option value="20M">20 M</option>
                            <option value="15M">15 M</option>
                            <option value="10M">10 M</option>
                            <option value="6M">6 M</option>
                            <option value="4M">4 M</option>
                            <option value="2M">2 M</option>
                            <option value="222">222 MHz (1.25 M)</option>
                            <option value="432">432 MHz (70 CM)</option>
                            <option value="902">902 MHz (33 CM)</option>
                            <option value="1.2G">1.2 GHz</option>
                            <option value="2.3G">2.3 GHz</option>
                            <option value="3.4G">3.4 GHz</option>
                            <option value="5.7G">5.7 GHz</option>
                            <option value="10G">10 GHz</option>
                            <option value="24G">24 GHz</option>
                            <option value="47G">47 GHz</option>
                            <option value="75G">75 GHz</option>
                            <option value="122G">122 GHz</option>
                            <option value="134G">134 GHz</option>
                            <option value="241G">241 GHz</option>
                            <option value="Light"><?php echo lang('general_word_light'); ?></option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categorymode"><?php echo lang('export_cabrillo_cat_mode'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categorymode" name="categorymode">
                            <option value="MIXED">MIXED</option>
                            <option value="CW">CW</option>
                            <option value="DIGI">DIGI</option>
                            <option value="FM">FM</option>
                            <option value="RTTY">RTTY</option>
                            <option value="SSB">SSB</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categorypower"><?php echo lang('export_cabrillo_cat_power'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categorypower" name="categorypower">
                            <option value="LOW">LOW</option>
                            <option value="HIGH">HIGH</option>
                            <option value="QRP">QRP</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categorystation"><?php echo lang('export_cabrillo_cat_station'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categorystation" name="categorystation">
                            <option value="FIXED">FIXED</option>
                            <option value="DISTRIBUTED">DISTRIBUTED</option>
                            <option value="MOBILE">MOBILE</option>
                            <option value="PORTABLE">PORTABLE</option>
                            <option value="ROVER">ROVER</option>
                            <option value="ROVER-LIMITED">ROVER-LIMITED</option>
                            <option value="ROVER-UNLIMITED">ROVER-UNLIMITED</option>
                            <option value="EXPEDITION">EXPEDITION</option>
                            <option value="HQ">HQ</option>
                            <option value="SCHOOL">SCHOOL</option>
                            <option value="EXPLORER">EXPLORER</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categorytransmitter"><?php echo lang('export_cabrillo_cat_transmitter'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categorytransmitter" name="categorytransmitter">
                            <option value="ONE">ONE</option>
                            <option value="TWO">TWO</option>
                            <option value="LIMITED">LIMITED</option>
                            <option value="UNLIMITED">UNLIMITED</option>
                            <option value="SWL">SWL</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categoryoverlay"><?php echo lang('export_cabrillo_cat_overlay'); ?>:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categoryoverlay" name="categoryoverlay">
                            <option value="">None / Not Applicable</option>
                            <option value="CLASSIC">CLASSIC</option>
                            <option value="ROOKIE">ROOKIE</option>
                            <option value="TB-WIRES">TB-WIRES</option>
                            <option value="YOUTH">YOUTH</option>
                            <option value="NOVICE-TECH">NOVICE-TECH</option>
                            <option value="YL">YL</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_categorytime">Category Time:</label>
                    <div class="col-md-8">
                        <select class="form-select" id="cab_categorytime" name="categorytime">
                            <option value="">Not specified</option>
                            <option value="6-HOURS">6-HOURS</option>
                            <option value="8-HOURS">8-HOURS</option>
                            <option value="12-HOURS">12-HOURS</option>
                            <option value="24-HOURS">24-HOURS</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_location">Location:
                        <span tabindex="0" data-bs-toggle="tooltip" title="Required for ARRL, CQ, IARU-HF, RSGB-IOTA and RDXC contests. Use your ARRL/RAC section abbreviation (e.g. CT) for US/Canada, DX for foreign stations, Island Name for RSGB-IOTA, or RDA Number for RDXC."><i class="fas fa-info-circle"></i></span>
                    </label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="cab_location" name="location" placeholder="e.g. CT or DX">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_operators"><?php echo lang('export_cabrillo_operators'); ?>:</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="cab_operators" name="operators" placeholder="Space-separated callsigns">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_club">Club:</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="cab_club" name="club">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-md-4 col-form-label" for="cab_soapbox"><?php echo lang('export_cabrillo_soapbox'); ?>:</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="cab_soapbox" name="soapbox">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-download"></i> Export Cabrillo</button>
            </div>
            </form>
        </div>
    </div>
</div>
