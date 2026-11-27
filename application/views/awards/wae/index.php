<?php
$e = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$classes = $wae['classes']['confirmed'];

$wae_counts = array('all' => 0, 'confirmed' => 0, 'worked' => 0, 'none' => 0, 'five' => 0);
$short_missing = array();
foreach ($wae['rows'] as $r) {
    if (empty($r['deleted'])) {
        $wae_counts['all']++;
        if ($r['confirmed_points'] > 0) {
            $wae_counts['confirmed']++;
            if ($r['confirmed_points'] >= 5) {
                $wae_counts['five']++;
            }
        } elseif ($r['worked_points'] > 0) {
            $wae_counts['worked']++;
        } else {
            $wae_counts['none']++;
        }
    }
}
foreach ($wae['missing'] as $m) {
    if ($m['confirmed_bands'] === 0) {
        $short_missing[] = $m['code'];
    }
}
?>
<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'DARC Worked All Europe &middot; European countries and band points',
        'icon' => 'fas fa-globe-europe',
        'info_lines' => array(
            lang('awards_wae_description_ln1'),
            lang('awards_wae_description_ln2'),
            lang('awards_wae_description_ln3'),
            lang('awards_wae_description_ln4'),
        ),
    )); ?>

    <!-- Compact Unified Filter Card -->
    <form class="card mb-3 wae-filter-card" action="<?php echo site_url('awards/wae'); ?>" method="post" enctype="multipart/form-data">
        <div class="card-body py-2 px-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <!-- QSL Type -->
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

                    <!-- Band -->
                    <div class="d-flex align-items-center gap-2">
                        <label class="small fw-semibold text-muted mb-0" for="band2">Band:</label>
                        <select id="band2" name="band" class="form-select form-select-sm" style="width: auto; min-width: 115px;">
                            <option value="All" <?php if ($filters['band'] == 'All') echo 'selected'; ?>>Every band</option>
                            <?php foreach ($worked_bands as $band) {
                                echo '<option value="' . $e($band) . '"' . ($filters['band'] == $band ? ' selected' : '') . '>' . $e($band) . '</option>';
                            } ?>
                        </select>
                    </div>

                    <!-- Mode -->
                    <div class="d-flex align-items-center gap-2">
                        <label class="small fw-semibold text-muted mb-0" for="mode">Mode:</label>
                        <select id="mode" name="mode" class="form-select form-select-sm" style="width: auto; min-width: 105px;">
                            <?php foreach ($wae_modes as $mode) {
                                echo '<option value="' . $e($mode) . '"' . ($filters['mode'] == $mode ? ' selected' : '') . '>' . $e($mode) . '</option>';
                            } ?>
                        </select>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="<?php echo site_url('awards/wae'); ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary"><i class="fas fa-filter me-1"></i>Show</button>
                </div>
            </div>
        </div>
    </form>

    <!-- 5 Certificate Class Cards -->
    <div class="row g-2 mb-3">
        <?php foreach ($classes as $cert) {
            $earned = !empty($cert['earned']);
            $c_need = max(0, (int) $cert['countries_target'] - (int) $cert['countries']);
            $p_need = max(0, (int) $cert['points_target'] - (int) $cert['points']);
        ?>
        <div class="col-6 col-md-4 col-xl">
            <div class="card h-100 wae-cert-card <?php echo $earned ? 'border-success' : ''; ?>">
                <div class="card-body py-2 px-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="fw-bold small"><?php echo $e($cert['name']); ?></div>
                        <?php if ($earned) { ?>
                            <span class="badge text-bg-success"><i class="fas fa-check me-1"></i>Earned</span>
                        <?php } else { ?>
                            <span class="small text-muted fw-semibold"><?php echo (int) $cert['pct']; ?>%</span>
                        <?php } ?>
                    </div>
                    <div class="d-flex align-items-baseline justify-content-between mt-1">
                        <div>
                            <div class="text-muted" style="font-size: 0.68rem; text-transform: uppercase; font-weight: 700;">Countries</div>
                            <div class="fs-5 fw-bold <?php echo $earned ? 'text-success' : ''; ?>" style="font-variant-numeric: tabular-nums;">
                                <?php echo (int) $cert['countries']; ?><span class="text-muted fw-normal fs-6"> / <?php echo (int) $cert['countries_target']; ?></span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-muted" style="font-size: 0.68rem; text-transform: uppercase; font-weight: 700;">Points</div>
                            <div class="fs-5 fw-bold" style="font-variant-numeric: tabular-nums;">
                                <?php echo (int) $cert['points']; ?><span class="text-muted fw-normal fs-6"> / <?php echo (int) $cert['points_target']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar <?php echo $earned ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo (int) $cert['pct']; ?>%"></div>
                    </div>
                    <div class="text-muted mt-1" style="font-size: 0.71rem;">
                        <?php if ($earned) { ?>
                            Ready to apply
                        <?php } else {
                            $needs = array();
                            if ($c_need > 0) $needs[] = $c_need . ' countries';
                            if ($p_need > 0) $needs[] = $p_need . ' pts';
                            echo 'Need ' . implode(', ', $needs);
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <!-- Interactive Worked All Europe Map Card -->
    <div class="card rac-map-card mb-3">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
            <span class="fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-map-marked-alt text-primary"></i>
                <span>Europe Entities Map</span>
                <span class="small text-muted fw-normal d-none d-md-inline">· Click any marker to filter table</span>
            </span>
            <div class="d-flex flex-wrap align-items-center gap-1" id="waeMapFilterPills" role="group" aria-label="Filter map markers">
                <button type="button" class="rac-mode-pill is-active" data-wae-map="all">All (<?php echo $wae_counts['all']; ?>)</button>
                <button type="button" class="rac-mode-pill" data-wae-map="confirmed">Confirmed (<?php echo $wae_counts['confirmed']; ?>)</button>
                <button type="button" class="rac-mode-pill" data-wae-map="worked">Worked (<?php echo $wae_counts['worked']; ?>)</button>
                <button type="button" class="rac-mode-pill" data-wae-map="none">Needed (<?php echo $wae_counts['none']; ?>)</button>
                <button type="button" class="rac-mode-pill" data-wae-map="five">5-Band (<?php echo $wae_counts['five']; ?>)</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="waemap" style="width: 100%; height: 440px;"></div>
        </div>
    </div>
    <script>
        var waeMapRows = <?php echo json_encode($wae['rows']); ?>;
        var waeTileUrl = <?php echo json_encode(!empty($tile_url) ? $tile_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); ?>;
    </script>

    <!-- Not Yet Confirmed Prefix Chips -->
    <?php if ($short_missing) { ?>
    <div class="card mb-3 wae-missing-card">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center gap-1">
            <span class="small fw-semibold text-muted me-2"><i class="fas fa-bullseye me-1 text-danger"></i>Not yet confirmed (<?php echo count($short_missing); ?>):</span>
            <?php foreach ($short_missing as $code) { ?>
                <button type="button" class="wae-missing-chip" onclick="var el=document.getElementById('waeSearchInput');if(el){el.value='<?php echo $e($code); ?>';el.dispatchEvent(new Event('input'));}"><?php echo $e($code); ?></button>
            <?php } ?>
        </div>
    </div>
    <?php } ?>

    <!-- Searchable & Filterable Entities Table Card -->
    <div class="card rac-map-card">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="fw-semibold"><i class="fas fa-list-ul text-primary me-1"></i>WAE Entities</span>
                <div class="d-flex flex-wrap gap-1" id="waeTableFilterPills" role="group" aria-label="Filter table by status">
                    <button type="button" class="rac-mode-pill is-active" data-wae-filter="all">All</button>
                    <button type="button" class="rac-mode-pill" data-wae-filter="confirmed">Confirmed</button>
                    <button type="button" class="rac-mode-pill" data-wae-filter="worked">Worked</button>
                    <button type="button" class="rac-mode-pill" data-wae-filter="none">Not Worked</button>
                </div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <small class="text-muted" id="waeShownCount"><?php echo count($wae['rows']); ?> shown</small>
                <div class="input-group input-group-sm" style="width: 210px;">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" id="waeSearchInput" class="form-control" placeholder="Country, code, band…" autocomplete="off">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="waetable" class="table table-sm table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Country</th>
                        <th style="width: 90px;">Code</th>
                        <th>Confirmed Bands</th>
                        <th>Worked Only</th>
                        <th class="text-center" style="width: 140px;">Band Points</th>
                        <th class="text-end" style="width: 110px;">Status</th>
                    </tr>
                </thead>
                <tbody id="waeTableBody">
                    <?php foreach ($wae['rows'] as $row) {
                        if ($row['confirmed_points'] > 0) {
                            $st_key = 'confirmed';
                            $status = '<span class="badge text-bg-success">Confirmed</span>';
                        } elseif ($row['worked_points'] > 0) {
                            $st_key = 'worked';
                            $status = '<span class="badge text-bg-warning">Worked</span>';
                        } else {
                            $st_key = 'none';
                            $status = '<span class="badge text-bg-danger">Not worked</span>';
                        }
                        $worked_only = array_values(array_diff($row['worked_bands'], $row['confirmed_bands']));
                        $search_blob = strtolower($row['name'] . ' ' . $row['code'] . ' ' . implode(' ', $row['worked_bands']) . ' ' . implode(' ', $row['confirmed_bands']));
                    ?>
                    <tr data-wae-status="<?php echo $st_key; ?>" data-wae-search="<?php echo $e($search_blob); ?>">
                        <td class="fw-semibold">
                            <?php echo $e($row['name']); ?>
                            <?php if (!empty($row['deleted'])) { ?><span class="text-muted small fw-normal ms-1">(III/II/I only)</span><?php } ?>
                        </td>
                        <td><span class="wae-code-chip"><?php echo $e($row['code']); ?></span></td>
                        <td>
                            <?php if (!empty($row['confirmed_bands'])) { ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($row['confirmed_bands'] as $cb) { ?>
                                        <span class="wae-band-pill is-conf"><?php echo $e($cb); ?></span>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <span class="text-muted small">—</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (!empty($worked_only)) { ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($worked_only as $wb) { ?>
                                        <span class="wae-band-pill is-work"><?php echo $e($wb); ?></span>
                                    <?php } ?>
                                </div>
                            <?php } else { ?>
                                <span class="text-muted small">—</span>
                            <?php } ?>
                        </td>
                        <td class="text-center text-nowrap">
                            <span class="wae-dots" title="<?php echo (int) $row['confirmed_points']; ?> confirmed / <?php echo (int) $row['worked_points']; ?> worked (cap 5)">
                                <?php for ($d = 1; $d <= 5; $d++) {
                                    $dot_cls = $d <= $row['confirmed_points'] ? 'is-conf' : ($d <= $row['worked_points'] ? 'is-work' : '');
                                ?>
                                    <span class="wae-dot <?php echo $dot_cls; ?>"></span>
                                <?php } ?>
                            </span>
                            <span class="small fw-semibold ms-1"><?php echo (int) $row['confirmed_points']; ?>/5</span>
                        </td>
                        <td class="text-end"><?php echo $status; ?></td>
                    </tr>
                    <?php } ?>
                    <tr id="waeEmptyRow" style="display:none;">
                        <td colspan="6" class="text-center text-muted py-4">No WAE entities match your filter.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
