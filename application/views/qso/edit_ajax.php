<?php
$qso_call = htmlspecialchars((string) $qso->COL_CALL, ENT_QUOTES, 'UTF-8');
$qso_band = htmlspecialchars((string) $qso->COL_BAND, ENT_QUOTES, 'UTF-8');
$qso_mode_disp = !empty($qso->COL_SUBMODE) ? $qso->COL_SUBMODE : $qso->COL_MODE;
$qso_mode_esc = htmlspecialchars((string) $qso_mode_disp, ENT_QUOTES, 'UTF-8');
$qso_freq_mhz = (is_numeric($qso->COL_FREQ) && (float) $qso->COL_FREQ > 0)
    ? number_format(((float) $qso->COL_FREQ) / 1000000, 3) . ' MHz'
    : '';
$qso_country = htmlspecialchars(ucwords(strtolower((string) $qso->COL_COUNTRY)), ENT_QUOTES, 'UTF-8');
$qso_distance_str = trim(str_replace('&nbsp;', ' ', (string) qso_format_distance($qso)));

$has_sats = !empty($qso->COL_SAT_NAME) || !empty($qso->COL_SAT_MODE);
$has_awards = !empty($qso->COL_IOTA) || !empty($qso->COL_SOTA_REF) || !empty($qso->COL_WWFF_REF)
    || !empty($qso->COL_POTA_REF) || !empty($qso->COL_DARC_DOK) || !empty($qso->COL_SIG)
    || !empty($qso->COL_STATE) || !empty($qso->COL_CNTY);
$has_qsl = in_array($qso->COL_QSL_SENT, array('Y', 'R', 'Q'), true)
    || in_array($qso->COL_QSL_RCVD, array('Y', 'V'), true)
    || in_array($qso->COL_LOTW_QSL_SENT, array('Y', 'Q'), true)
    || in_array($qso->COL_LOTW_QSL_RCVD, array('Y', 'V'), true)
    || in_array($qso->COL_EQSL_QSL_SENT, array('Y', 'Q'), true)
    || in_array($qso->COL_EQSL_QSL_RCVD, array('Y', 'V'), true);
$has_notes = trim((string) $qso->COL_NOTES) !== '';
$has_contest = !empty($qso->COL_CONTEST_ID) || !empty($qso->COL_SRX) || !empty($qso->COL_STX)
    || !empty($qso->COL_SRX_STRING) || !empty($qso->COL_STX_STRING);

$lotw_confirmed = in_array($qso->COL_LOTW_QSL_RCVD, array('Y', 'V'), true);
$eqsl_confirmed = in_array($qso->COL_EQSL_QSL_RCVD, array('Y', 'V'), true);
$paper_confirmed = in_array($qso->COL_QSL_RCVD, array('Y', 'V'), true);
$paper_requested = ($qso->COL_QSL_SENT === 'R' || $qso->COL_QSL_SENT === 'Q');

$qslmsg_val = (string) $qso->COL_QSLMSG;
$qslmsg_remaining = max(0, 240 - strlen($qslmsg_val));
?>

<div class="qso-edit-wrap" data-qso-callsign="<?php echo $qso_call; ?>">
    <?php echo validation_errors(); ?>
    <form name="qsos" id="qsoform">
        <!-- Context Hero -->
        <div class="qso-edit-hero">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <span class="qso-edit-call-chip"><?php echo $qso_call; ?></span>
                <div>
                    <div class="d-flex align-items-center flex-wrap gap-1 mb-1">
                        <span class="fw-bold small me-1">QSO #<?php echo (int) $qso->COL_PRIMARY_KEY; ?></span>
                        <?php if ($qso_band !== '' || $qso_mode_esc !== '' || $qso_freq_mhz !== '') { ?>
                            <span class="qso-edit-pill">
                                <?php echo implode(' · ', array_filter(array($qso_band, $qso_mode_esc, $qso_freq_mhz))); ?>
                            </span>
                        <?php } ?>
                        <?php if ($lotw_confirmed) { ?>
                            <span class="qso-edit-pill is-ok"><i class="fas fa-check"></i> LoTW</span>
                        <?php } ?>
                        <?php if ($eqsl_confirmed) { ?>
                            <span class="qso-edit-pill is-ok"><i class="fas fa-check"></i> eQSL</span>
                        <?php } ?>
                        <?php if ($paper_confirmed) { ?>
                            <span class="qso-edit-pill is-ok"><i class="fas fa-check"></i> QSL</span>
                        <?php } elseif ($paper_requested) { ?>
                            <span class="qso-edit-pill is-pending"><i class="fas fa-clock"></i> QSL Req</span>
                        <?php } ?>
                    </div>
                    <div class="qso-edit-hero-sub">
                        <span><i class="far fa-clock me-1"></i><?php echo htmlspecialchars((string) $qso->COL_TIME_ON, ENT_QUOTES, 'UTF-8'); ?> UTC</span>
                        <?php if ($qso_country !== '') { ?>
                            <span>·</span>
                            <span><i class="fas fa-globe me-1"></i><?php echo $qso_country; ?></span>
                        <?php } ?>
                        <?php if ($qso_distance_str !== '') { ?>
                            <span>·</span>
                            <span><i class="fas fa-ruler-horizontal me-1"></i><?php echo htmlspecialchars($qso_distance_str, ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Icon Pill Tab Bar -->
        <div class="qso-edit-tabbar">
            <ul class="nav nav-pills qso-edit-pills" id="nav-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="nav-qso-tab" data-bs-toggle="tab" data-bs-target="#nav-qso" type="button" role="tab" aria-controls="nav-qso" aria-selected="true">
                        <i class="fas fa-broadcast-tower"></i> QSO
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-satellites-tab" data-bs-toggle="tab" data-bs-target="#nav-satellites" type="button" role="tab" aria-controls="nav-satellites" aria-selected="false">
                        <i class="fas fa-satellite"></i> Sats<?php if ($has_sats) { ?><span class="qso-edit-dot"></span><?php } ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-awards-tab" data-bs-toggle="tab" data-bs-target="#nav-awards" type="button" role="tab" aria-controls="nav-awards" aria-selected="false">
                        <i class="fas fa-award"></i> Awards<?php if ($has_awards) { ?><span class="qso-edit-dot"></span><?php } ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-qsl-tab" data-bs-toggle="tab" data-bs-target="#nav-qsl" type="button" role="tab" aria-controls="nav-qsl" aria-selected="false">
                        <i class="fas fa-envelope-open-text"></i> QSL<?php if ($has_qsl) { ?><span class="qso-edit-dot"></span><?php } ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-qso-notes-tab" data-bs-toggle="tab" data-bs-target="#nav-qso-notes" type="button" role="tab" aria-controls="nav-qso-notes" aria-selected="false">
                        <i class="fas fa-sticky-note"></i> Notes<?php if ($has_notes) { ?><span class="qso-edit-dot"></span><?php } ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-station-tab" data-bs-toggle="tab" data-bs-target="#nav-station" type="button" role="tab" aria-controls="nav-station" aria-selected="false">
                        <i class="fas fa-home"></i> Station
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="nav-contest-tab" data-bs-toggle="tab" data-bs-target="#nav-contest" type="button" role="tab" aria-controls="nav-contest" aria-selected="false">
                        <i class="fas fa-trophy"></i> Contest<?php if ($has_contest) { ?><span class="qso-edit-dot"></span><?php } ?>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Body -->
        <div class="qso-edit-body">
            <div class="tab-content" id="nav-tabContent">
                <!-- 1. QSO TAB -->
                <div class="tab-pane fade show active" id="nav-qso" role="tabpanel" aria-labelledby="nav-qso-tab">
                    <!-- Contact & Timing -->
                    <div class="qso-edit-section">
                        <div class="qso-edit-section-title">Contact &amp; Timing</div>
                        <div class="row g-2">
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="callsign">Callsign</label>
                                <input type="text" class="form-control form-control-sm font-monospace fw-semibold" id="callsign" name="callsign" value="<?php echo $qso_call; ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="time_on">Start Date/Time (UTC)</label>
                                <input type="text" class="form-control form-control-sm font-monospace" name="time_on" id="time_on" value="<?php echo htmlspecialchars((string) $qso->COL_TIME_ON, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="time_off">End Date/Time (UTC)</label>
                                <input type="text" class="form-control form-control-sm font-monospace" name="time_off" id="time_off" value="<?php echo htmlspecialchars((string) $qso->COL_TIME_OFF, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- RF, Band & Signal Report -->
                    <div class="qso-edit-section">
                        <div class="qso-edit-section-title">RF, Band &amp; Signal Report</div>
                        <div class="row g-2 mb-2">
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="freq">TX Frequency</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="freq" name="freq" value="<?php echo htmlspecialchars((string) $qso->COL_FREQ, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="freqrx">RX Frequency</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="freqrx" name="freq_display_rx" value="<?php if ($qso->COL_FREQ_RX != "0") { echo htmlspecialchars((string) $qso->COL_FREQ_RX, ENT_QUOTES, 'UTF-8'); } ?>" placeholder="Optional">
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="band">Band</label>
                                <select id="band" class="form-select form-select-sm" name="band">
                                    <?php foreach ($bands as $key => $bandgroup) {
                                        echo '<optgroup label="' . strtoupper($key) . '">';
                                        foreach ($bandgroup as $band) {
                                            echo '<option value="' . $band . '"';
                                            if (strtolower((string) $qso->COL_BAND) == $band) echo ' selected';
                                            echo '>' . $band . '</option>' . "\n";
                                        }
                                        echo '</optgroup>';
                                    } ?>
                                </select>
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="band_rx">RX Band</label>
                                <select id="band_rx" class="form-select form-select-sm" name="band_rx">
                                    <option value="" <?php if (strtolower((string) $qso->COL_BAND_RX) == "") { echo 'selected="selected"'; } ?>></option>
                                    <?php foreach ($bands as $key => $bandgroup) {
                                        echo '<optgroup label="' . strtoupper($key) . '">';
                                        foreach ($bandgroup as $band) {
                                            echo '<option value="' . $band . '"';
                                            if (strtolower((string) $qso->COL_BAND_RX) == $band) echo ' selected';
                                            echo '>' . $band . '</option>' . "\n";
                                        }
                                        echo '</optgroup>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="mode">Mode</label>
                                <select id="mode" class="form-select mode form-select-sm" name="mode">
                                    <?php
                                    foreach ($modes->result() as $mode) {
                                        if ($mode->submode == null) {
                                            printf('<option value="%s" %s>%s</option>', htmlspecialchars($mode->mode, ENT_QUOTES, 'UTF-8'), ($qso->COL_MODE == $mode->mode ? 'selected="selected"' : ''), htmlspecialchars($mode->mode, ENT_QUOTES, 'UTF-8'));
                                        } else {
                                            printf('<option value="%s" %s>&rArr; %s</option>', htmlspecialchars($mode->submode, ENT_QUOTES, 'UTF-8'), ($qso->COL_SUBMODE == $mode->submode ? 'selected="selected"' : ''), htmlspecialchars($mode->submode, ENT_QUOTES, 'UTF-8'));
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="transmit_power">TX Power (W)</label>
                                <input type="number" step="0.001" class="form-control form-control-sm" id="transmit_power" name="transmit_power" value="<?php echo htmlspecialchars((string) $qso->COL_TX_PWR, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Watts">
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="rst_sent">RST Sent</label>
                                <input type="text" class="form-control form-control-sm font-monospace" name="rst_sent" id="rst_sent" value="<?php echo htmlspecialchars((string) $qso->COL_RST_SENT, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="rst_rcvd">RST Rcvd</label>
                                <input type="text" class="form-control form-control-sm font-monospace" name="rst_rcvd" id="rst_rcvd" value="<?php echo htmlspecialchars((string) $qso->COL_RST_RCVD, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Operator, Location & Propagation -->
                    <div class="qso-edit-section mb-0">
                        <div class="qso-edit-section-title">Operator, Location &amp; Propagation</div>
                        <div class="row g-2 mb-2">
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="name">Name</label>
                                <input type="text" class="form-control form-control-sm" id="name" name="name" value="<?php echo htmlspecialchars((string) $qso->COL_NAME, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="qth">QTH</label>
                                <input type="text" class="form-control form-control-sm" id="qth" name="qth" value="<?php echo htmlspecialchars((string) $qso->COL_QTH, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="locator">Gridsquare</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="locator" name="locator" value="<?php echo htmlspecialchars((string) $qso->COL_GRIDSQUARE, ENT_QUOTES, 'UTF-8'); ?>">
                                <small id="locator_info" class="form-text text-muted"><?php echo $qso_distance_str; ?></small>
                                <input type="hidden" name="distance" id="distance" value="<?php print ($qso->COL_DISTANCE != "") ? htmlspecialchars((string) $qso->COL_DISTANCE, ENT_QUOTES, 'UTF-8') : "0"; ?>">
                            </div>
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="vucc_grids">VUCC MultiGrids</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="vucc_grids" name="vucc_grids" value="<?php echo htmlspecialchars((string) $qso->COL_VUCC_GRIDS, ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. IO75,IO85">
                            </div>
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="dxcc_id">DXCC Entity</label>
                                <select class="form-select form-select-sm" id="dxcc_id" name="dxcc_id" required>
                                    <option value="0">- NONE -</option>
                                    <?php
                                    foreach ($dxcc as $d) {
                                        echo '<option value="' . $d->adif . '"';
                                        if ($qso->COL_DXCC == $d->adif) {
                                            echo ' selected="selected"';
                                        }
                                        echo '>' . htmlspecialchars($d->prefix . ' - ' . ucwords(strtolower($d->name)), ENT_QUOTES, 'UTF-8');
                                        if ($d->Enddate != null) {
                                            echo ' (' . lang('gen_hamradio_deleted_dxcc') . ')';
                                        }
                                        echo '</option>';
                                    }
                                    ?>
                                </select>
                                <input type="hidden" id="country" name="country" value="<?php echo htmlspecialchars((string) $qso->COL_COUNTRY, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="continent"><?php echo lang('gen_hamradio_continent'); ?></label>
                                <select class="form-select form-select-sm" id="continent" name="continent">
                                    <option value=""></option>
                                    <option value="AF" <?php if ($qso->COL_CONT == "AF") { echo 'selected="selected"'; } ?>><?php echo lang('africa'); ?></option>
                                    <option value="AN" <?php if ($qso->COL_CONT == "AN") { echo 'selected="selected"'; } ?>><?php echo lang('antarctica'); ?></option>
                                    <option value="AS" <?php if ($qso->COL_CONT == "AS") { echo 'selected="selected"'; } ?>><?php echo lang('asia'); ?></option>
                                    <option value="EU" <?php if ($qso->COL_CONT == "EU") { echo 'selected="selected"'; } ?>><?php echo lang('europe'); ?></option>
                                    <option value="NA" <?php if ($qso->COL_CONT == "NA") { echo 'selected="selected"'; } ?>><?php echo lang('northamerica'); ?></option>
                                    <option value="OC" <?php if ($qso->COL_CONT == "OC") { echo 'selected="selected"'; } ?>><?php echo lang('oceania'); ?></option>
                                    <option value="SA" <?php if ($qso->COL_CONT == "SA") { echo 'selected="selected"'; } ?>><?php echo lang('southamerica'); ?></option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="prop_mode">Propagation Mode</label>
                                <select class="form-select form-select-sm" id="prop_mode" name="prop_mode">
                                    <option value="" <?php if ($qso->COL_PROP_MODE == "") { echo 'selected="selected"'; } ?>></option>
                                    <option value="AS" <?php if ($qso->COL_PROP_MODE == "AS") { echo 'selected="selected"'; } ?>>Aircraft Scatter</option>
                                    <option value="AUR" <?php if ($qso->COL_PROP_MODE == "AUR") { echo 'selected="selected"'; } ?>>Aurora</option>
                                    <option value="AUE" <?php if ($qso->COL_PROP_MODE == "AUE") { echo 'selected="selected"'; } ?>>Aurora-E</option>
                                    <option value="BS" <?php if ($qso->COL_PROP_MODE == "BS") { echo 'selected="selected"'; } ?>>Back scatter</option>
                                    <option value="ECH" <?php if ($qso->COL_PROP_MODE == "ECH") { echo 'selected="selected"'; } ?>>EchoLink</option>
                                    <option value="EME" <?php if ($qso->COL_PROP_MODE == "EME") { echo 'selected="selected"'; } ?>>Earth-Moon-Earth</option>
                                    <option value="ES" <?php if ($qso->COL_PROP_MODE == "ES") { echo 'selected="selected"'; } ?>>Sporadic E</option>
                                    <option value="FAI" <?php if ($qso->COL_PROP_MODE == "FAI") { echo 'selected="selected"'; } ?>>Field Aligned Irregularities</option>
                                    <option value="F2" <?php if ($qso->COL_PROP_MODE == "F2") { echo 'selected="selected"'; } ?>>F2 Reflection</option>
                                    <option value="INTERNET" <?php if ($qso->COL_PROP_MODE == "INTERNET") { echo 'selected="selected"'; } ?>>Internet-assisted</option>
                                    <option value="ION" <?php if ($qso->COL_PROP_MODE == "ION") { echo 'selected="selected"'; } ?>>Ionoscatter</option>
                                    <option value="IRL" <?php if ($qso->COL_PROP_MODE == "IRL") { echo 'selected="selected"'; } ?>>IRLP</option>
                                    <option value="MS" <?php if ($qso->COL_PROP_MODE == "MS") { echo 'selected="selected"'; } ?>>Meteor scatter</option>
                                    <option value="RPT" <?php if ($qso->COL_PROP_MODE == "RPT") { echo 'selected="selected"'; } ?>>Repeater / Transponder</option>
                                    <option value="RS" <?php if ($qso->COL_PROP_MODE == "RS") { echo 'selected="selected"'; } ?>>Rain scatter</option>
                                    <option value="SAT" <?php if ($qso->COL_PROP_MODE == "SAT") { echo 'selected="selected"'; } ?>>Satellite</option>
                                    <option value="TEP" <?php if ($qso->COL_PROP_MODE == "TEP") { echo 'selected="selected"'; } ?>>Trans-equatorial</option>
                                    <option value="TR" <?php if ($qso->COL_PROP_MODE == "TR") { echo 'selected="selected"'; } ?>>Tropospheric ducting</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="qso-edit-label" for="comment">Comment</label>
                            <input type="text" class="form-control form-control-sm" id="comment" name="comment" value="<?php echo htmlspecialchars($qso->COL_COMMENT ? $qso->COL_COMMENT : '', ENT_QUOTES, 'UTF-8'); ?>">
                        </div>
                    </div>
                </div>

                <!-- 2. SATELLITES TAB -->
                <div class="tab-pane fade" id="nav-satellites" role="tabpanel" aria-labelledby="nav-satellites-tab">
                    <div class="qso-edit-section mb-0">
                        <div class="qso-edit-section-title">Satellite Details</div>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="sat_name_edit">Sat Name</label>
                                <input list="satellite_names_edit" type="text" class="form-control form-control-sm" name="sat_name" id="sat_name_edit" value="<?php echo htmlspecialchars((string) $qso->COL_SAT_NAME, ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. RS-44, QO-100">
                                <datalist id="satellite_names_edit" class="satellite_names_list_edit"></datalist>
                            </div>
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="sat_mode_edit">Sat Mode</label>
                                <input list="satellite_modes_edit" type="text" class="form-control form-control-sm" name="sat_mode" id="sat_mode_edit" value="<?php echo htmlspecialchars((string) $qso->COL_SAT_MODE, ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. V/U, U/V, SX">
                                <datalist id="satellite_modes_edit" class="satellite_modes_list_edit"></datalist>
                            </div>
                        </div>
                        <div class="small text-muted mt-2">
                            <i class="fas fa-info-circle me-1"></i>Selecting a satellite mode automatically sets Propagation Mode to <strong>SAT</strong>.
                        </div>
                    </div>
                </div>

                <!-- 3. AWARDS TAB -->
                <div class="tab-pane fade" id="nav-awards" role="tabpanel" aria-labelledby="nav-awards-tab">
                    <?php
                    $ca_provinces = array(
                        'AB' => 'Alberta', 'BC' => 'British Columbia', 'MB' => 'Manitoba', 'NB' => 'New Brunswick',
                        'NL' => 'Newfoundland and Labrador', 'NS' => 'Nova Scotia', 'NT' => 'Northwest Territories', 'NU' => 'Nunavut',
                        'ON' => 'Ontario', 'PE' => 'Prince Edward Island', 'QC' => 'Quebec', 'SK' => 'Saskatchewan', 'YT' => 'Yukon',
                    );
                    $rda_value = preg_match('/^[A-Z]{2}[\s-]?\d{1,3}$/i', trim((string) $qso->COL_CNTY)) ? strtoupper(trim((string) $qso->COL_CNTY)) : '';
                    ?>
                    <div class="qso-edit-section">
                        <div class="qso-edit-section-title">Zones &amp; Subdivisions</div>
                        <div class="row g-2">
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="cqz">CQ Zone</label>
                                <select class="form-select form-select-sm" id="cqz" name="cqz" required>
                                    <?php
                                    for ($i = 1; $i <= 40; $i++) {
                                        echo '<option value="' . $i . '"';
                                        if ($qso->COL_CQZ == $i) {
                                            echo ' selected="selected"';
                                        }
                                        echo '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="input_usa_state_edit">USA State</label>
                                <select class="form-select form-select-sm" id="input_usa_state_edit" name="usa_state">
                                    <option value=""></option>
                                    <?php
                                    $us_states = array(
                                        'AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado',
                                        'CT'=>'Connecticut','DE'=>'Delaware','DC'=>'District Of Columbia','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii',
                                        'ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana',
                                        'ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi',
                                        'MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey',
                                        'NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma',
                                        'OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota',
                                        'TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington',
                                        'WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'
                                    );
                                    foreach ($us_states as $st_code => $st_name) {
                                        echo '<option value="' . $st_code . '"' . ($qso->COL_STATE == $st_code ? ' selected="selected"' : '') . '>' . $st_name . ' (' . $st_code . ')</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="stationCntyInputEdit">USA County</label>
                                <input disabled="disabled" class="form-control form-control-sm" id="stationCntyInputEdit" type="text" name="usa_county" value="<?php echo htmlspecialchars($rda_value === '' ? (string) $qso->COL_CNTY : '', ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <?php if ((string) $qso->COL_DXCC === '1' || isset($ca_provinces[$qso->COL_STATE])) { ?>
                                <div class="col-sm-6">
                                    <label class="qso-edit-label" for="input_ca_province_edit">Province or Territory</label>
                                    <select class="form-select form-select-sm" id="input_ca_province_edit" name="ca_province">
                                        <option value=""></option>
                                        <?php foreach ($ca_provinces as $code => $name) { ?>
                                            <option value="<?php echo $code; ?>" <?php if ($qso->COL_STATE == $code) { echo 'selected="selected"'; } ?>><?php echo $name . ' (' . $code . ')'; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            <?php } ?>
                            <?php if ($rda_value !== '' || in_array((string) $qso->COL_DXCC, array('54', '15', '126'), true)) { ?>
                                <div class="col-sm-6">
                                    <label class="qso-edit-label" for="rda_district_edit">RDA District</label>
                                    <input type="text" class="form-control form-control-sm font-monospace" id="rda_district_edit" name="rda" value="<?php echo htmlspecialchars($rda_value, ENT_QUOTES, 'UTF-8'); ?>" placeholder="TB-02">
                                </div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="qso-edit-section mb-0">
                        <div class="qso-edit-section-title">Award &amp; Portable References</div>
                        <div class="row g-2">
                            <div class="col-sm-4">
                                <label class="qso-edit-label" for="iota_ref">IOTA</label>
                                <select class="form-select form-select-sm" id="iota_ref" name="iota_ref">
                                    <option value=""></option>
                                    <?php
                                    foreach ($iota as $i) {
                                        echo '<option value="' . htmlspecialchars($i->tag, ENT_QUOTES, 'UTF-8') . '"';
                                        if ($qso->COL_IOTA == $i->tag) {
                                            echo ' selected="selected"';
                                        }
                                        echo '>' . htmlspecialchars($i->tag . ' - ' . $i->name, ENT_QUOTES, 'UTF-8') . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-6 col-sm-4">
                                <label class="qso-edit-label" for="pota_ref_edit">POTA</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="pota_ref_edit" name="pota_ref" value="<?php echo htmlspecialchars((string) $qso->COL_POTA_REF, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-6 col-sm-4">
                                <label class="qso-edit-label" for="sota_ref_edit">SOTA</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="sota_ref_edit" name="sota_ref" value="<?php echo htmlspecialchars((string) $qso->COL_SOTA_REF, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-6 col-sm-4">
                                <label class="qso-edit-label" for="wwff_ref_edit">WWFF</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="wwff_ref_edit" name="wwff_ref" value="<?php echo htmlspecialchars((string) $qso->COL_WWFF_REF, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-6 col-sm-4">
                                <label class="qso-edit-label" for="darc_dok_edit">DARC DOK</label>
                                <input type="text" class="form-control form-control-sm font-monospace" id="darc_dok_edit" name="darc_dok" value="<?php echo htmlspecialchars((string) $qso->COL_DARC_DOK, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-6 col-sm-4">
                                <label class="qso-edit-label" for="sig">SIG</label>
                                <input type="text" class="form-control form-control-sm" id="sig" name="sig" value="<?php echo htmlspecialchars((string) $qso->COL_SIG, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="col-sm-12">
                                <label class="qso-edit-label" for="sig_info">SIG Info</label>
                                <input type="text" class="form-control form-control-sm" id="sig_info" name="sig_info" value="<?php echo htmlspecialchars((string) $qso->COL_SIG_INFO, ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. QSL TAB (Unified 3-Channel View) -->
                <div class="tab-pane fade" id="nav-qsl" role="tabpanel" aria-labelledby="nav-qsl-tab">
                    <div class="row g-2 mb-2">
                        <!-- Paper QSL -->
                        <div class="col-md-4">
                            <div class="qso-edit-qsl-card h-100">
                                <div class="qso-edit-qsl-head">
                                    <span><i class="fas fa-mail-bulk me-1"></i>Paper QSL</span>
                                    <?php if ($paper_confirmed) { ?>
                                        <span class="qso-edit-pill is-ok">Confirmed</span>
                                    <?php } elseif ($paper_requested) { ?>
                                        <span class="qso-edit-pill is-pending">Requested</span>
                                    <?php } ?>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Sent</label>
                                    <select class="form-select form-select-sm" name="qsl_sent">
                                        <option value="N" <?php if ($qso->COL_QSL_SENT == "N") { echo 'selected="selected"'; } ?>>No</option>
                                        <option value="Y" <?php if ($qso->COL_QSL_SENT == "Y") { echo 'selected="selected"'; } ?>>Yes</option>
                                        <option value="R" <?php if ($qso->COL_QSL_SENT == "R") { echo 'selected="selected"'; } ?>>Requested</option>
                                        <option value="Q" <?php if ($qso->COL_QSL_SENT == "Q") { echo 'selected="selected"'; } ?>>Queued</option>
                                        <option value="I" <?php if ($qso->COL_QSL_SENT == "I") { echo 'selected="selected"'; } ?>>Invalid (Ignore)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Sent Method</label>
                                    <select class="form-select form-select-sm" name="qsl_sent_method">
                                        <option value="" <?php if ($qso->COL_QSL_SENT_VIA == "") { echo 'selected="selected"'; } ?>>Method</option>
                                        <option value="D" <?php if ($qso->COL_QSL_SENT_VIA == "D") { echo 'selected="selected"'; } ?>>Direct</option>
                                        <option value="B" <?php if ($qso->COL_QSL_SENT_VIA == "B") { echo 'selected="selected"'; } ?>>Bureau</option>
                                        <option value="E" <?php if ($qso->COL_QSL_SENT_VIA == "E") { echo 'selected="selected"'; } ?>>Electronic</option>
                                        <option value="M" <?php if ($qso->COL_QSL_SENT_VIA == "M") { echo 'selected="selected"'; } ?>>Manager</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label" for="qsl-via">Sent Via (Manager)</label>
                                    <input type="text" id="qsl-via" class="form-control form-control-sm font-monospace" name="qsl_via_callsign" value="<?php echo htmlspecialchars((string) $qso->COL_QSL_VIA, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Manager callsign">
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Received</label>
                                    <select class="form-select form-select-sm" name="qsl_rcvd">
                                        <option value="N" <?php if ($qso->COL_QSL_RCVD == "N") { echo 'selected="selected"'; } ?>>No</option>
                                        <option value="Y" <?php if ($qso->COL_QSL_RCVD == "Y") { echo 'selected="selected"'; } ?>>Yes</option>
                                        <option value="R" <?php if ($qso->COL_QSL_RCVD == "R") { echo 'selected="selected"'; } ?>>Requested</option>
                                        <option value="I" <?php if ($qso->COL_QSL_RCVD == "I") { echo 'selected="selected"'; } ?>>Invalid (Ignore)</option>
                                        <option value="V" <?php if ($qso->COL_QSL_RCVD == "V") { echo 'selected="selected"'; } ?>>Verified (Match)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="qso-edit-label">Received Method</label>
                                    <select class="form-select form-select-sm" name="qsl_rcvd_method">
                                        <option value="" <?php if ($qso->COL_QSL_RCVD_VIA == "") { echo 'selected="selected"'; } ?>>Method</option>
                                        <option value="D" <?php if ($qso->COL_QSL_RCVD_VIA == "D") { echo 'selected="selected"'; } ?>>Direct</option>
                                        <option value="B" <?php if ($qso->COL_QSL_RCVD_VIA == "B") { echo 'selected="selected"'; } ?>>Bureau</option>
                                        <option value="E" <?php if ($qso->COL_QSL_RCVD_VIA == "E") { echo 'selected="selected"'; } ?>>Electronic</option>
                                        <option value="M" <?php if ($qso->COL_QSL_RCVD_VIA == "M") { echo 'selected="selected"'; } ?>>Manager</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- LoTW -->
                        <div class="col-md-4">
                            <div class="qso-edit-qsl-card h-100 d-flex flex-column">
                                <div class="qso-edit-qsl-head">
                                    <span><i class="fas fa-certificate me-1"></i>LoTW</span>
                                    <?php if ($lotw_confirmed) { ?>
                                        <span class="qso-edit-pill is-ok">Verified</span>
                                    <?php } ?>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Sent</label>
                                    <select class="form-select form-select-sm" name="lotw_sent">
                                        <option value="N" <?php if ($qso->COL_LOTW_QSL_SENT == "N") { echo 'selected="selected"'; } ?>>No</option>
                                        <option value="Y" <?php if ($qso->COL_LOTW_QSL_SENT == "Y") { echo 'selected="selected"'; } ?>>Yes</option>
                                        <option value="R" <?php if ($qso->COL_LOTW_QSL_SENT == "R") { echo 'selected="selected"'; } ?>>Requested</option>
                                        <option value="Q" <?php if ($qso->COL_LOTW_QSL_SENT == "Q") { echo 'selected="selected"'; } ?>>Queued</option>
                                        <option value="I" <?php if ($qso->COL_LOTW_QSL_SENT == "I") { echo 'selected="selected"'; } ?>>Invalid (Ignore)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Received</label>
                                    <select class="form-select form-select-sm" name="lotw_rcvd">
                                        <option value="N" <?php if ($qso->COL_LOTW_QSL_RCVD == "N") { echo 'selected="selected"'; } ?>>No</option>
                                        <option value="Y" <?php if ($qso->COL_LOTW_QSL_RCVD == "Y") { echo 'selected="selected"'; } ?>>Yes</option>
                                        <option value="R" <?php if ($qso->COL_LOTW_QSL_RCVD == "R") { echo 'selected="selected"'; } ?>>Requested</option>
                                        <option value="I" <?php if ($qso->COL_LOTW_QSL_RCVD == "I") { echo 'selected="selected"'; } ?>>Invalid (Ignore)</option>
                                        <option value="V" <?php if ($qso->COL_LOTW_QSL_RCVD == "V") { echo 'selected="selected"'; } ?>>Verified (Match)</option>
                                    </select>
                                </div>
                                <div class="small text-muted mt-auto pt-2">
                                    Logbook of the World status updates automatically on sync.
                                </div>
                            </div>
                        </div>

                        <!-- eQSL -->
                        <div class="col-md-4">
                            <div class="qso-edit-qsl-card h-100 d-flex flex-column">
                                <div class="qso-edit-qsl-head">
                                    <span><i class="fas fa-bolt me-1"></i>eQSL</span>
                                    <?php if ($eqsl_confirmed) { ?>
                                        <span class="qso-edit-pill is-ok">Confirmed</span>
                                    <?php } ?>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Sent</label>
                                    <select class="form-select form-select-sm" name="eqsl_sent">
                                        <option value="N" <?php if ($qso->COL_EQSL_QSL_SENT == "N") { echo 'selected="selected"'; } ?>>No</option>
                                        <option value="Y" <?php if ($qso->COL_EQSL_QSL_SENT == "Y") { echo 'selected="selected"'; } ?>>Yes</option>
                                        <option value="R" <?php if ($qso->COL_EQSL_QSL_SENT == "R") { echo 'selected="selected"'; } ?>>Requested</option>
                                        <option value="Q" <?php if ($qso->COL_EQSL_QSL_SENT == "Q") { echo 'selected="selected"'; } ?>>Queued</option>
                                        <option value="I" <?php if ($qso->COL_EQSL_QSL_SENT == "I") { echo 'selected="selected"'; } ?>>Invalid (Ignore)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="qso-edit-label">Received</label>
                                    <select class="form-select form-select-sm" name="eqsl_rcvd">
                                        <option value="N" <?php if ($qso->COL_EQSL_QSL_RCVD == "N") { echo 'selected="selected"'; } ?>>No</option>
                                        <option value="Y" <?php if ($qso->COL_EQSL_QSL_RCVD == "Y") { echo 'selected="selected"'; } ?>>Yes</option>
                                        <option value="R" <?php if ($qso->COL_EQSL_QSL_RCVD == "R") { echo 'selected="selected"'; } ?>>Requested</option>
                                        <option value="I" <?php if ($qso->COL_EQSL_QSL_RCVD == "I") { echo 'selected="selected"'; } ?>>Invalid (Ignore)</option>
                                        <option value="V" <?php if ($qso->COL_EQSL_QSL_RCVD == "V") { echo 'selected="selected"'; } ?>>Verified (Match)</option>
                                    </select>
                                </div>
                                <div class="small text-muted mt-auto pt-2">
                                    <?php echo lang('qsl_notes_helptext'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- eQSL Message -->
                    <div class="qso-edit-section mb-0">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="qso-edit-section-title mb-0">
                                <?php echo lang('general_word_notes'); ?> (eQSL QSLMSG)
                                <span class="qso_eqsl_qslmsg_update ms-2" role="button" title="<?php echo lang('qso_eqsl_qslmsg_helptext'); ?>"><i class="fas fa-redo-alt"></i></span>
                            </div>
                            <small class="text-muted" id="charsLeft"><?php echo $qslmsg_remaining; ?>/240</small>
                        </div>
                        <textarea class="form-control form-control-sm" id="qslmsg" name="qslmsg" rows="3" maxlength="240"><?php echo htmlspecialchars($qslmsg_val, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <div id="qslmsg_hide" style="display:none;"><?php echo htmlspecialchars($qslmsg_val, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                </div>

                <!-- 5. NOTES TAB -->
                <div class="tab-pane fade" id="nav-qso-notes" role="tabpanel" aria-labelledby="nav-qso-notes-tab">
                    <div class="qso-edit-section mb-0">
                        <div class="qso-edit-section-title">Internal QSO Notes (not exported to eQSL)</div>
                        <textarea class="form-control form-control-sm" id="notes" name="notes" rows="8" placeholder="Add private operating notes, antenna details, propagation remarks…"><?php echo htmlspecialchars((string) $qso->COL_NOTES, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                </div>

                <!-- 6. STATION TAB -->
                <div class="tab-pane fade" id="nav-station" role="tabpanel" aria-labelledby="nav-station-tab">
                    <?php
                    $CI =& get_instance();
                    $CI->load->model('stations');
                    $my_stations = $CI->stations->all_of_user();
                    ?>
                    <div class="qso-edit-section mb-0">
                        <div class="qso-edit-section-title">Station Profile &amp; Operator</div>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="stationProfile">Station Profile</label>
                                <select id="stationProfile" class="form-select form-select-sm" name="station_profile">
                                    <?php foreach ($my_stations->result() as $stationrow) { ?>
                                        <option value="<?php echo $stationrow->station_id; ?>" <?php if ($qso->station_id == $stationrow->station_id) { echo 'selected="selected"'; } ?>><?php echo htmlspecialchars($stationrow->station_profile_name, ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label class="qso-edit-label" for="operatorCallsign">Operator Callsign</label>
                                <input type="text" id="operatorCallsign" class="form-control form-control-sm font-monospace" name="operator_callsign" value="<?php echo htmlspecialchars((string) $qso->COL_OPERATOR, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7. CONTEST TAB -->
                <div class="tab-pane fade" id="nav-contest" role="tabpanel" aria-labelledby="nav-contest-tab">
                    <div class="qso-edit-section mb-0">
                        <div class="qso-edit-section-title">Contest Exchange</div>
                        <div class="mb-2">
                            <label class="qso-edit-label" for="contest_name">Contest Name</label>
                            <select class="form-select form-select-sm" id="contest_name" name="contest_name">
                                <option value=""></option>
                                <?php
                                foreach ($contest as $c) {
                                    echo '<option value="' . htmlspecialchars($c['adifname'], ENT_QUOTES, 'UTF-8') . '"';
                                    if ($qso->COL_CONTEST_ID == $c['adifname']) {
                                        echo ' selected="selected"';
                                    }
                                    echo '>' . htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="srx">Serial (R)</label>
                                <input type="text" id="srx" class="form-control form-control-sm font-monospace" name="srx" value="<?php echo htmlspecialchars((string) $qso->COL_SRX, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="stx">Serial (S)</label>
                                <input type="text" id="stx" class="form-control form-control-sm font-monospace" name="stx" value="<?php echo htmlspecialchars((string) $qso->COL_STX, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="srx_string">Exchange (R)</label>
                                <input type="text" id="srx_string" class="form-control form-control-sm font-monospace" name="srx_string" value="<?php echo htmlspecialchars((string) $qso->COL_SRX_STRING, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                            <div class="col-6 col-sm-3">
                                <label class="qso-edit-label" for="stx_string">Exchange (S)</label>
                                <input type="text" id="stx_string" class="form-control form-control-sm font-monospace" name="stx_string" value="<?php echo htmlspecialchars((string) $qso->COL_STX_STRING, ENT_QUOTES, 'UTF-8'); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="id" value="<?php echo (int) $qso->COL_PRIMARY_KEY; ?>" />

        <!-- Sticky Action Footer -->
        <div class="qso-edit-footer">
            <a class="btn btn-outline-danger btn-sm" href="javascript:qso_delete(<?php echo (int) $qso->COL_PRIMARY_KEY; ?>, '<?php echo $qso_call; ?>')">
                <i class="fas fa-trash-alt me-1"></i>Delete QSO
            </a>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button id="show" type="button" name="download" class="btn btn-primary btn-sm" onclick="qso_save();">
                    <i class="fas fa-save me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
