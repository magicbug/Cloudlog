<?php
$band_esc = htmlspecialchars((string)$band, ENT_QUOTES, 'UTF-8');
$band_url = site_url('awards/vucc_band') . '?Band=' . rawurlencode((string)$band);
$worked_url = $band_url . '&Type=worked';
$confirmed_url = $band_url . '&Type=confirmed';
$grid_count = is_array($vucc_array) ? count($vucc_array) : 0;

$active_type = $type ?: 'all';
$type_label = 'All Grids';
if ($active_type === 'worked') {
    $type_label = 'Unconfirmed Worked';
} elseif ($active_type === 'confirmed') {
    $type_label = 'Confirmed Only';
}

$map_grids = array();
$confirmed_count = 0;
if (!empty($vucc_array)) {
    foreach ($vucc_array as $vucc => $value) {
        $is_qsl = isset($value['qsl']) && strtoupper(trim((string)$value['qsl'])) === 'Y';
        $is_lotw = isset($value['lotw']) && strtoupper(trim((string)$value['lotw'])) === 'Y';
        $is_confirmed = ($active_type === 'confirmed') || $is_qsl || $is_lotw;
        if ($is_confirmed) {
            $confirmed_count++;
        }
        $map_grids[] = array(
            'grid'      => strtoupper(trim((string)$vucc)),
            'confirmed' => $is_confirmed,
            'qsl'       => $is_qsl,
            'lotw'      => $is_lotw,
        );
    }
}
?>

<main class="container vucc-page">
    <!-- Unified Topbar -->
    <div class="card vucc-topbar mb-3">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="vucc-brand-icon d-inline-flex align-items-center justify-content-center">
                    <i class="fas fa-th-large"></i>
                </span>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h1 class="h5 mb-0 fw-semibold"><?php echo htmlspecialchars((string)$page_title, ENT_QUOTES, 'UTF-8'); ?></h1>
                        <span class="badge rounded-pill vucc-rate-badge"><?php echo number_format($grid_count); ?> <?php echo $grid_count === 1 ? 'grid' : 'grids'; ?></span>
                    </div>
                    <div class="small text-muted">
                        <?php echo lang('general_word_filtering_on'); ?> <?php echo htmlspecialchars((string)$filter, ENT_QUOTES, 'UTF-8'); ?> &middot; <?php echo $type_label; ?>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="btn-group btn-group-sm" role="group" aria-label="Filter gridsquare type">
                    <a href="<?php echo $band_url; ?>" class="btn <?php echo $active_type === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All</a>
                    <a href="<?php echo $worked_url; ?>" class="btn <?php echo $active_type === 'worked' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Worked</a>
                    <a href="<?php echo $confirmed_url; ?>" class="btn <?php echo $active_type === 'confirmed' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Confirmed</a>
                </div>
                <a href="<?php echo site_url('awards/vucc'); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Back to VUCC
                </a>
            </div>
        </div>
    </div>

    <?php if (!empty($vucc_array)) { ?>
        <script>
            window.vuccBandMapData = <?php echo json_encode(array(
                'band'  => (string)$band,
                'type'  => $active_type,
                'grids' => $map_grids,
            )); ?>;
        </script>

        <div class="row g-3 align-items-stretch">
            <!-- Interactive Band Grid Map -->
            <div class="col-lg-7">
                <div class="card vucc-map-card h-100">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
                        <div class="fw-semibold small text-uppercase vucc-section-title">
                            <i class="fas fa-map-marked-alt me-1 text-primary"></i>Grid Map &middot; <?php echo $band_esc; ?>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-3 small text-muted">
                            <?php if ($active_type !== 'worked') { ?>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <span class="vucc-legend-dot vucc-legend-confirmed"></span>Confirmed (<?php echo number_format($confirmed_count); ?>)
                                </span>
                            <?php } ?>
                            <?php if ($active_type !== 'confirmed') { ?>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <span class="vucc-legend-dot vucc-legend-worked"></span>Worked (<?php echo number_format($grid_count - $confirmed_count); ?>)
                                </span>
                            <?php } ?>
                            <button type="button" class="btn btn-sm btn-outline-secondary vucc-fit-btn" id="vuccFitMapBtn" title="Fit map to all grids">
                                <i class="fas fa-compress-arrows-alt me-1"></i>Fit
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="vucc_band_map" class="vucc-band-map"></div>
                    </div>
                    <div class="card-footer py-2 px-3 small text-muted vucc-map-footer">
                        Click any gridsquare on the map or in the list to view matching QSOs.
                    </div>
                </div>
            </div>

            <!-- Gridsquares List -->
            <div class="col-lg-5">
                <div class="card vucc-table-card h-100">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
                        <div class="fw-semibold small text-uppercase vucc-section-title">
                            <i class="fas fa-th me-1 text-primary"></i>Gridsquares on <?php echo $band_esc; ?>
                        </div>
                        <div class="small text-muted">
                            <?php echo number_format($grid_count); ?> listed
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <table style="width:100%" class="table table-sm tablevucc table-hover align-middle mb-0 vucc-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 48px;">#</th>
                                    <th>Gridsquare</th>
                                    <?php if ($type != 'worked') { ?>
                                        <th class="text-center">QSL</th>
                                        <th class="text-center">LoTW</th>
                                    <?php } else { ?>
                                        <th>Callsign</th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($vucc_array as $vucc => $value) {
                                    $grid_esc = htmlspecialchars((string)$vucc, ENT_QUOTES, 'UTF-8');
                                    $js_grid = htmlspecialchars(json_encode((string)$vucc), ENT_QUOTES, 'UTF-8');
                                    $js_band = htmlspecialchars(json_encode((string)$band), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr>
                                    <td class="text-center text-muted small"><?php echo $i++; ?></td>
                                    <td>
                                        <div class="d-inline-flex align-items-center gap-1">
                                            <a href='javascript:displayContacts(<?php echo $js_grid; ?>,<?php echo $js_band; ?>,"All","VUCC")' class="vucc-grid-chip text-decoration-none">
                                                <?php echo $grid_esc; ?>
                                            </a>
                                            <button type="button" class="btn btn-link btn-sm p-1 text-muted vucc-locate-btn" onclick='vuccFocusGrid(<?php echo $js_grid; ?>)' title="Locate <?php echo $grid_esc; ?> on map" aria-label="Locate <?php echo $grid_esc; ?> on map">
                                                <i class="fas fa-crosshairs"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <?php if ($type != 'worked') { ?>
                                        <td class="text-center"><?php echo $value['qsl']; ?></td>
                                        <td class="text-center"><?php echo $value['lotw']; ?></td>
                                    <?php } else { ?>
                                        <td class="fw-semibold"><?php echo $value['call']; ?></td>
                                    <?php } ?>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="card vucc-empty-card">
            <div class="card-body text-center py-5 px-3">
                <div class="vucc-empty-icon d-inline-flex align-items-center justify-content-center mb-3">
                    <i class="fas fa-search"></i>
                </div>
                <h2 class="h5 fw-semibold mb-2">No gridsquares found for this filter</h2>
                <p class="text-muted mb-4">
                    There are no matching <?php echo strtolower($type_label); ?> gridsquares logged on <?php echo $band_esc; ?> yet.
                </p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <?php if ($active_type !== 'all') { ?>
                        <a href="<?php echo $band_url; ?>" class="btn btn-primary btn-sm">Show All on <?php echo $band_esc; ?></a>
                    <?php } ?>
                    <a href="<?php echo site_url('awards/vucc'); ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back to VUCC
                    </a>
                </div>
            </div>
        </div>
    <?php } ?>
</main>
