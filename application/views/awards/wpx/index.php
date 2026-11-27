<?php
$e = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$hr = $wpx['honor_roll'];
?>
<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'CQ Worked All Prefixes &middot; Prefix certificates, endorsements, and world coverage',
        'icon' => 'fas fa-hashtag',
        'info_lines' => array(
            lang('awards_wpx_description_ln1'),
            lang('awards_wpx_description_ln2'),
            lang('awards_wpx_description_ln3'),
            lang('awards_wpx_description_ln4'),
        ),
    )); ?>

    <!-- Compact Horizontal Filter Card -->
    <form class="card mb-3 wae-filter-card" action="<?php echo site_url('awards/wpx'); ?>" method="post" enctype="multipart/form-data">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-semibold text-muted">Confirmed:</span>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if ($filters['qsl']) echo 'checked="checked"'; ?>>
                            <label class="form-check-label small" for="qsl">QSL</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if ($filters['lotw']) echo 'checked="checked"'; ?>>
                            <label class="form-check-label small" for="lotw">LoTW</label>
                        </div>
                        <div class="form-check form-check-inline mb-0">
                            <input class="form-check-input" type="checkbox" name="eqsl" value="1" id="eqsl" <?php if ($filters['eqsl']) echo 'checked="checked"'; ?>>
                            <label class="form-check-label small" for="eqsl">eQSL</label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <label class="small fw-semibold text-muted mb-0" for="band2">Band:</label>
                        <select id="band2" name="band" class="form-select form-select-sm" style="width: auto; min-width: 115px;">
                            <option value="All" <?php if ($filters['band'] == 'All') echo 'selected'; ?>>Every band</option>
                            <?php foreach ($worked_bands as $band) {
                                echo '<option value="' . $e($band) . '"' . ($filters['band'] == $band ? ' selected' : '') . '>' . $e($band) . '</option>';
                            } ?>
                        </select>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <label class="small fw-semibold text-muted mb-0" for="mode">Mode:</label>
                        <select id="mode" name="mode" class="form-select form-select-sm" style="width: auto; min-width: 110px;">
                            <option value="All" <?php if ($filters['mode'] == 'All') echo 'selected'; ?>>All</option>
                            <?php foreach ($modes->result() as $mode) {
                                $value = ($mode->submode == null) ? $mode->mode : $mode->submode;
                                echo '<option value="' . $e($value) . '"' . ($filters['mode'] == $value ? ' selected' : '') . '>' . $e($value) . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo site_url('awards/wpx'); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary"><i class="fas fa-filter me-1"></i>Show</button>
                </div>
            </div>
        </div>
    </form>

    <!-- 4 Certificate Cards -->
    <div class="row g-2 mb-3">
        <?php foreach ($wpx['certificates'] as $cert) {
            $earned = !empty($cert['earned']);
        ?>
        <div class="col-6 col-lg-3">
            <div class="card h-100 wae-cert-card <?php echo $earned ? 'border-success' : ''; ?>">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="fw-bold small"><?php echo $e($cert['mode']); ?></div>
                        <?php if ($earned) { ?>
                            <span class="badge text-bg-success">Basic<?php echo $cert['endorsement'] ? ' +' . (int) $cert['endorsement'] : ''; ?></span>
                        <?php } else { ?>
                            <span class="small text-muted fw-semibold"><?php echo (int) $cert['pct']; ?>%</span>
                        <?php } ?>
                    </div>
                    <div class="text-muted" style="font-size: 0.68rem; text-transform: uppercase; font-weight: 700;">Confirmed Prefixes</div>
                    <div class="fs-3 fw-bold <?php echo $earned ? 'text-success' : ''; ?>" style="font-variant-numeric: tabular-nums; line-height: 1.15;">
                        <?php echo (int) $cert['confirmed']; ?><span class="text-muted fw-normal fs-6"> / <?php echo (int) $cert['basic']; ?></span>
                    </div>
                    <div class="small text-muted">Worked: <strong><?php echo (int) $cert['worked']; ?></strong></div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar <?php echo $earned ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo (int) $cert['pct']; ?>%"></div>
                    </div>
                    <div class="text-muted mt-1" style="font-size: 0.72rem;">
                        <?php if ($earned) { ?>
                            Next endorsement at <?php echo (int) $cert['next']; ?>
                        <?php } else { ?>
                            Need <?php echo max(0, (int) $cert['basic'] - (int) $cert['confirmed']); ?> more confirmed
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Interactive World Prefix Density Map Card -->
    <div class="card rac-map-card mb-3">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
            <span class="fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-globe-americas text-primary"></i>
                <span>World Prefix Density Map</span>
                <span class="small text-muted fw-normal d-none d-md-inline">· Click any bubble to filter by continent</span>
            </span>
            <div class="d-flex flex-wrap align-items-center gap-1" id="wpxMapContPills" role="group" aria-label="Zoom map to continent">
                <button type="button" class="rac-mode-pill is-active" data-wpx-cont="ALL">World</button>
                <button type="button" class="rac-mode-pill" data-wpx-cont="EU">EU</button>
                <button type="button" class="rac-mode-pill" data-wpx-cont="NA">NA</button>
                <button type="button" class="rac-mode-pill" data-wpx-cont="SA">SA</button>
                <button type="button" class="rac-mode-pill" data-wpx-cont="AS">AS</button>
                <button type="button" class="rac-mode-pill" data-wpx-cont="AF">AF</button>
                <button type="button" class="rac-mode-pill" data-wpx-cont="OC">OC</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="wpxmap" style="width: 100%; height: 420px;"></div>
        </div>
    </div>

    <!-- Band & Continent Endorsements -->
    <div class="row g-3 mb-3">
        <div class="col-lg-6">
            <div class="card h-100 rac-map-card">
                <div class="card-header py-2 px-3 fw-semibold">
                    <i class="fas fa-wave-square text-primary me-1"></i>Band Endorsements
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Band</th>
                                <th>Progress</th>
                                <th class="text-end">Confirmed / Target</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wpx['band_endorsements'] as $row) {
                                $pct = $row['target'] > 0 ? min(100, (int) round(($row['confirmed'] / $row['target']) * 100)) : 0;
                            ?>
                            <tr>
                                <td class="fw-bold font-monospace"><?php echo $e($row['band']); ?></td>
                                <td style="min-width: 110px;">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar <?php echo $row['earned'] ? 'bg-success' : ''; ?>" style="width: <?php echo $pct; ?>%"></div>
                                    </div>
                                </td>
                                <td class="text-end text-nowrap small">
                                    <strong><?php echo (int) $row['confirmed']; ?></strong> / <?php echo (int) $row['target']; ?>
                                    <span class="text-muted">(<?php echo (int) $row['worked']; ?> wkd)</span>
                                </td>
                                <td class="text-end"><?php echo $row['earned'] ? '<span class="badge text-bg-success">Earned</span>' : '<span class="badge text-bg-secondary">Open</span>'; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card h-100 rac-map-card">
                <div class="card-header py-2 px-3 fw-semibold">
                    <i class="fas fa-globe text-success me-1"></i>Continent Endorsements
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Continent</th>
                                <th>Progress</th>
                                <th class="text-end">Confirmed / Target</th>
                                <th class="text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wpx['continent_endorsements'] as $row) {
                                $pct = $row['target'] > 0 ? min(100, (int) round(($row['confirmed'] / $row['target']) * 100)) : 0;
                            ?>
                            <tr>
                                <td class="fw-semibold"><?php echo $e($row['name']); ?> <span class="text-muted small">(<?php echo $e($row['code']); ?>)</span></td>
                                <td style="min-width: 100px;">
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar <?php echo $row['earned'] ? 'bg-success' : ''; ?>" style="width: <?php echo $pct; ?>%"></div>
                                    </div>
                                </td>
                                <td class="text-end text-nowrap small">
                                    <strong><?php echo (int) $row['confirmed']; ?></strong> / <?php echo (int) $row['target']; ?>
                                    <span class="text-muted">(<?php echo (int) $row['worked']; ?> wkd)</span>
                                </td>
                                <td class="text-end"><?php echo $row['earned'] ? '<span class="badge text-bg-success">Earned</span>' : '<span class="badge text-bg-secondary">Open</span>'; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Honor Roll Checklist Card -->
    <div class="card mb-3 rac-map-card <?php echo $hr['earned'] ? 'border-success' : ''; ?>">
        <div class="card-header py-2 px-3 d-flex justify-content-between align-items-center">
            <span class="fw-semibold"><i class="fas fa-crown text-warning me-1"></i>WPX Honor Roll</span>
            <?php echo $hr['earned'] ? '<span class="badge text-bg-success">Qualified</span>' : '<span class="badge text-bg-secondary">In Progress</span>'; ?>
        </div>
        <div class="card-body py-2 px-3">
            <div class="row g-3 small">
                <div class="col-md-4">
                    <div class="fw-semibold mb-1">
                        Prefix Total
                        <?php echo $hr['prefix_ok'] ? '<span class="badge text-bg-success ms-1">Met</span>' : '<span class="badge text-bg-secondary ms-1">Open</span>'; ?>
                    </div>
                    <div class="text-muted">
                        Mixed: <strong><?php echo (int) $hr['mixed']; ?></strong>/<?php echo (int) $hr['mixed_target']; ?> ·
                        SSB: <strong><?php echo (int) $hr['ssb']; ?></strong>/<?php echo (int) $hr['mode_target']; ?> ·
                        CW: <strong><?php echo (int) $hr['cw']; ?></strong>/<?php echo (int) $hr['mode_target']; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1">Required Bands</div>
                    <div class="d-flex flex-wrap gap-1">
                        <?php foreach ($hr['bands'] as $band => $ok) { ?>
                            <span class="badge <?php echo $ok ? 'text-bg-success' : 'text-bg-secondary'; ?>"><?php echo $e($band); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="fw-semibold mb-1">Required Continents</div>
                    <div class="d-flex flex-wrap gap-1">
                        <?php foreach ($hr['continents'] as $code => $ok) { ?>
                            <span class="badge <?php echo $ok ? 'text-bg-success' : 'text-bg-secondary'; ?>"><?php echo $e($code); ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Searchable & Paginated Prefixes Table Card -->
    <div class="card rac-map-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="fw-semibold"><i class="fas fa-hashtag text-primary me-1"></i>Prefixes (<?php echo count($wpx['prefixes']); ?>)</span>
                <div class="d-flex flex-wrap gap-1" id="wpxStatusPills" role="group" aria-label="Filter prefixes by status">
                    <button type="button" class="rac-mode-pill is-active" data-wpx-status="all">All</button>
                    <button type="button" class="rac-mode-pill" data-wpx-status="confirmed">Confirmed</button>
                    <button type="button" class="rac-mode-pill" data-wpx-status="worked">Worked Only</button>
                </div>
                <select id="wpxContSelect" class="form-select form-select-sm" style="width: auto;" aria-label="Filter by continent">
                    <option value="ALL">All Continents</option>
                    <option value="EU">EU</option>
                    <option value="NA">NA</option>
                    <option value="SA">SA</option>
                    <option value="AS">AS</option>
                    <option value="AF">AF</option>
                    <option value="OC">OC</option>
                </select>
            </div>
            <div class="d-flex align-items-center gap-2">
                <small class="text-muted" id="wpxShownCount"></small>
                <div class="input-group input-group-sm" style="width: 210px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" id="wpxFilter" class="form-control" placeholder="Prefix, band, mode…" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="wpxtable" class="table table-sm table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 110px;">Prefix</th>
                        <th class="text-center" style="width: 90px;">Continent</th>
                        <th class="text-center" style="width: 80px;">QSOs</th>
                        <th>Bands</th>
                        <th>Modes</th>
                        <th class="text-end" style="width: 110px;">Status</th>
                    </tr>
                </thead>
                <tbody id="wpxTableBody"></tbody>
            </table>
        </div>
        <div class="card-footer d-flex align-items-center justify-content-between py-2 px-3">
            <small class="text-muted">50 prefixes per page</small>
            <div class="btn-group btn-group-sm" role="group" aria-label="Prefix pagination">
                <button type="button" class="btn btn-outline-secondary" id="wpxPrevPage" disabled>Prev</button>
                <button type="button" class="btn btn-outline-secondary" id="wpxPageIndicator" disabled>1 / 1</button>
                <button type="button" class="btn btn-outline-secondary" id="wpxNextPage" disabled>Next</button>
            </div>
        </div>
    </div>
</div>

<script>
    var wpxMapEntities = <?php echo json_encode(isset($wpx_map_entities) ? $wpx_map_entities : array()); ?>;
    var wpxPrefixesData = <?php echo json_encode(array_values($wpx['prefixes'])); ?>;
    var wpxTileUrl = <?php echo json_encode(!empty($tile_url) ? $tile_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); ?>;
</script>
