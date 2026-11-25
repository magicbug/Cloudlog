<?php
$vucc_thresholds = array(
    '6m'     => 100,
    '4m'     => 100,
    '2m'     => 100,
    'SAT'    => 100,
    '1.25m'  => 50,
    '70cm'   => 50,
    '33cm'   => 25,
    '23cm'   => 25,
    '13cm'   => 10,
    '9cm'    => 10,
    '6cm'    => 5,
    '3cm'    => 5,
    '1.25cm' => 5,
    '6mm'    => 5,
    '4mm'    => 5,
    '2.5mm'  => 5,
    '2mm'    => 5,
    '1mm'    => 5,
);

$band_rows = array();
$all_row = null;

if (!empty($vucc_array)) {
    foreach ($vucc_array as $band => $vucc) {
        if ($band === 'All') {
            $all_row = $vucc;
        } else {
            $band_rows[$band] = $vucc;
        }
    }
}

$total_bands = count($band_rows);
$sum_band_worked = 0;
$sum_band_confirmed = 0;
$qualified_bands = 0;

foreach ($band_rows as $band => $vucc) {
    $worked = (int)($vucc['worked'] ?? 0);
    $confirmed = (int)($vucc['confirmed'] ?? 0);
    $target = $vucc_thresholds[$band] ?? ($vucc_thresholds[strtoupper($band)] ?? 100);
    $sum_band_worked += $worked;
    $sum_band_confirmed += $confirmed;
    if ($confirmed >= $target) {
        $qualified_bands++;
    }
}

$unique_worked = $all_row !== null ? (int)($all_row['worked'] ?? 0) : $sum_band_worked;
$unique_confirmed = $all_row !== null ? (int)($all_row['confirmed'] ?? 0) : $sum_band_confirmed;
$overall_confirm_pct = $unique_worked > 0 ? round(($unique_confirmed / $unique_worked) * 100) : 0;
?>

<main class="container vucc-page">
    <!-- Award Info JS Hooks -->
    <div id="awardInfoButton">
        <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_vucc_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_vucc_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_vucc_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_vucc_description_ln4'); ?>";
        </script>

        <!-- Unified Topbar -->
        <div class="card vucc-topbar mb-3">
            <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="vucc-brand-icon d-inline-flex align-items-center justify-content-center">
                        <i class="fas fa-th-large"></i>
                    </span>
                    <div>
                        <h1 class="h5 mb-0 fw-semibold"><?php echo $page_title; ?></h1>
                        <div class="small text-muted">VHF/UHF Century Club &middot; Maidenhead gridsquare progress by band</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <a href="<?php echo site_url('gridmap'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-map-marked-alt me-1"></i>Gridmap
                    </a>
                    <button type="button" class="btn btn-sm btn-primary" id="displayAwardInfo">
                        <i class="fas fa-info-circle me-1"></i><?php echo lang('awards_info_button'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($band_rows)) { ?>
        <!-- KPI Summary Strip -->
        <div class="row g-3 mb-3">
            <div class="col-6 col-lg-3">
                <div class="card vucc-kpi-card h-100">
                    <div class="card-body py-3 px-3">
                        <div class="vucc-kpi-label">Active Bands</div>
                        <div class="vucc-kpi-value mt-1"><?php echo number_format($total_bands); ?></div>
                        <div class="small text-muted mt-1">Bands with grids logged</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card vucc-kpi-card h-100">
                    <div class="card-body py-3 px-3">
                        <div class="vucc-kpi-label">Unique Grids Worked</div>
                        <div class="vucc-kpi-value mt-1"><?php echo number_format($unique_worked); ?></div>
                        <div class="small text-muted mt-1"><?php echo number_format($sum_band_worked); ?> total band-grids</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card vucc-kpi-card h-100">
                    <div class="card-body py-3 px-3">
                        <div class="vucc-kpi-label">Unique Grids Confirmed</div>
                        <div class="d-flex align-items-baseline gap-2 mt-1">
                            <span class="vucc-kpi-value text-success"><?php echo number_format($unique_confirmed); ?></span>
                            <span class="badge rounded-pill vucc-rate-badge"><?php echo $overall_confirm_pct; ?>%</span>
                        </div>
                        <div class="small text-muted mt-1">QSL / LoTW confirmed</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card vucc-kpi-card h-100">
                    <div class="card-body py-3 px-3">
                        <div class="vucc-kpi-label">Bands Qualified</div>
                        <div class="d-flex align-items-baseline gap-2 mt-1">
                            <span class="vucc-kpi-value"><?php echo number_format($qualified_bands); ?></span>
                            <span class="small text-muted">of <?php echo number_format($total_bands); ?></span>
                        </div>
                        <div class="small text-muted mt-1">Meeting ARRL VUCC target</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Band Progress Table Card -->
        <div class="card vucc-table-card">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
                <div class="fw-semibold small text-uppercase vucc-section-title">
                    <i class="fas fa-broadcast-tower me-1 text-primary"></i>Band Breakdown
                </div>
                <div class="d-flex align-items-center gap-3 small text-muted">
                    <span class="d-inline-flex align-items-center gap-1">
                        <span class="vucc-legend-dot vucc-legend-confirmed"></span>Confirmed
                    </span>
                    <span class="d-inline-flex align-items-center gap-1">
                        <span class="vucc-legend-dot vucc-legend-worked"></span>Worked
                    </span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 vucc-table">
                    <thead>
                        <tr>
                            <th class="ps-3">Band</th>
                            <th class="text-center">Grids Worked</th>
                            <th class="text-center">Grids Confirmed</th>
                            <th class="vucc-progress-col">VUCC Award Progress</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-3">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($band_rows as $band => $vucc) {
                            $worked = (int)($vucc['worked'] ?? 0);
                            $confirmed = (int)($vucc['confirmed'] ?? 0);
                            $target = $vucc_thresholds[$band] ?? ($vucc_thresholds[strtoupper($band)] ?? 100);
                            $worked_pct = $target > 0 ? min(100, round(($worked / $target) * 100)) : 0;
                            $confirmed_pct = $target > 0 ? min(100, round(($confirmed / $target) * 100)) : 0;
                            $remaining = max(0, $target - $confirmed);
                            $band_esc = htmlspecialchars($band, ENT_QUOTES, 'UTF-8');
                            $band_url = site_url('awards/vucc_band') . '?Band=' . rawurlencode($band);
                            $worked_url = $band_url . '&Type=worked';
                            $confirmed_url = $band_url . '&Type=confirmed';
                        ?>
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="<?php echo $band_url; ?>" class="vucc-band-badge text-decoration-none">
                                        <?php echo $band_esc; ?>
                                    </a>
                                    <span class="small text-muted d-none d-sm-inline">Target <?php echo $target; ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo $worked_url; ?>" class="vucc-num-link">
                                    <?php echo number_format($worked); ?>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo $confirmed_url; ?>" class="vucc-num-link <?php echo $confirmed > 0 ? 'is-confirmed' : ''; ?>">
                                    <?php echo number_format($confirmed); ?>
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-between small mb-1">
                                    <span class="fw-semibold"><?php echo number_format($confirmed); ?> <span class="text-muted fw-normal">/ <?php echo $target; ?></span></span>
                                    <span class="text-muted"><?php echo $confirmed_pct; ?>%</span>
                                </div>
                                <div class="vucc-progress-track" title="Confirmed: <?php echo $confirmed; ?> / <?php echo $target; ?> | Worked: <?php echo $worked; ?> / <?php echo $target; ?>">
                                    <div class="vucc-progress-worked" style="width: <?php echo $worked_pct; ?>%;"></div>
                                    <div class="vucc-progress-confirmed" style="width: <?php echo $confirmed_pct; ?>%;"></div>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($confirmed >= $target) { ?>
                                    <span class="badge rounded-pill text-bg-success">
                                        <i class="fas fa-check me-1"></i>Qualified
                                    </span>
                                <?php } elseif ($worked >= $target) { ?>
                                    <span class="badge rounded-pill text-bg-primary">
                                        <i class="fas fa-clock me-1"></i>Awaiting QSL
                                    </span>
                                <?php } else { ?>
                                    <span class="badge rounded-pill vucc-remaining-badge">
                                        <?php echo number_format($remaining); ?> to go
                                    </span>
                                <?php } ?>
                            </td>
                            <td class="text-end pe-3">
                                <a href="<?php echo $band_url; ?>" class="btn btn-sm btn-outline-secondary vucc-detail-btn">
                                    View <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <?php
                            $all_url = site_url('awards/vucc_band') . '?Band=All';
                            $all_worked_url = $all_url . '&Type=worked';
                            $all_confirmed_url = $all_url . '&Type=confirmed';
                        ?>
                        <tr class="vucc-totals-row">
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <a href="<?php echo $all_url; ?>" class="vucc-band-badge text-decoration-none">All</a>
                                    <span class="small text-muted d-none d-sm-inline">Unique across <?php echo $total_bands; ?> <?php echo $total_bands === 1 ? 'band' : 'bands'; ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo $all_worked_url; ?>" class="vucc-num-link">
                                    <?php echo number_format($unique_worked); ?>
                                </a>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo $all_confirmed_url; ?>" class="vucc-num-link <?php echo $unique_confirmed > 0 ? 'is-confirmed' : ''; ?>">
                                    <?php echo number_format($unique_confirmed); ?>
                                </a>
                            </td>
                            <td colspan="2" class="small text-muted">
                                Overall confirmation rate: <strong><?php echo $overall_confirm_pct; ?>%</strong>
                            </td>
                            <td class="text-end pe-3">
                                <a href="<?php echo $all_url; ?>" class="btn btn-sm btn-outline-secondary vucc-detail-btn">
                                    View All <i class="fas fa-chevron-right ms-1"></i>
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    <?php } else { ?>
        <div class="card vucc-empty-card">
            <div class="card-body text-center py-5 px-3">
                <div class="vucc-empty-icon d-inline-flex align-items-center justify-content-center mb-3">
                    <i class="fas fa-th-large"></i>
                </div>
                <h2 class="h5 fw-semibold mb-2">No VUCC gridsquares logged yet</h2>
                <p class="text-muted mb-4 mx-auto" style="max-width: 460px;">
                    Log VHF, UHF, microwave, or satellite QSOs with 4-character Maidenhead gridsquares to track your VUCC progress by band.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="<?php echo site_url('qso?manual=0'); ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Log a QSO
                    </a>
                    <a href="<?php echo site_url('gridmap'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-map-marked-alt me-1"></i>Open Gridmap
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</main>
