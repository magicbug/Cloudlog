<?php
$e = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$csv_query = http_build_query(array(
    'award' => $rac_award,
    'filtered' => 1,
    'qsl' => $filters['qsl'] ? 1 : 0,
    'lotw' => $filters['lotw'] ? 1 : 0,
    'eqsl' => $filters['eqsl'] ? 1 : 0,
));
?>
<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'Radio Amateurs of Canada &middot; Canadian provinces and territories awards',
        'icon' => 'fas fa-maple-leaf',
        'info_lines' => array(
            lang('awards_rac_description_ln1'),
            lang('awards_rac_description_ln2'),
            lang('awards_rac_description_ln3'),
            lang('awards_rac_description_ln4'),
        ),
    )); ?>

    <ul class="nav nav-pills mb-3" id="racAwards">
        <?php foreach ($rac_groups as $key => $g) { ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $key === $rac_award ? ' active' : ''; ?>" href="<?php echo site_url('awards/rac') . '?award=' . $e($key); ?>"><?php echo $e($g['name']); ?></a>
        </li>
        <?php } ?>
    </ul>

    <form class="form mt-3" action="<?php echo site_url('awards/rac'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="award" value="<?php echo $e($rac_award); ?>">
        <input type="hidden" name="filtered" value="1">
        <fieldset>
            <div class="mb-3 row">
                <div class="col-md-2">Confirmed by</div>
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if ($filters['qsl']) echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="qsl">QSL</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if ($filters['lotw']) echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="lotw">LoTW</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="eqsl" value="1" id="eqsl" <?php if ($filters['eqsl']) echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="eqsl">eQSL</label>
                    </div>
                    <div class="form-text">
                        RAC accepts paper QSLs, LoTW, eQSL from Authenticity Guaranteed members, and QRZ.com. Cloudlog does not record QRZ.com confirmations, so they cannot be ticked here. Leave all unticked to count every logged contact as confirmed.
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <a href="<?php echo site_url('awards/rac') . '?award=' . $e($rac_award); ?>" class="btn btn-sm btn-warning">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                    <a href="<?php echo site_url('awards/rac_csv') . '?' . $e($csv_query); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-file-csv"></i> Contacts that count (CSV)</a>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="row g-3 mb-3">
        <?php foreach ($rac['variants'] as $v) {
            $earned = $v['level'] !== null;
        ?>
        <div class="col-md-6 col-lg-3">
            <div class="card h-100 <?php echo $earned ? 'border-success' : ''; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="fw-bold"><?php echo $e($v['name']); ?></div>
                        <?php if ($earned) { ?>
                            <span class="badge text-bg-success"><?php echo $e($v['level']); ?></span>
                        <?php } ?>
                    </div>
                    <div class="text-muted small">Confirmed</div>
                    <div class="fs-2 fw-bold <?php echo $earned ? 'text-success' : ''; ?>">
                        <?php echo $e($v['confirmed']); ?> / <?php echo $e($v['target']); ?>
                    </div>
                    <div class="small text-muted">Worked: <?php echo $e($v['worked']); ?></div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar <?php echo $earned ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo (int) $v['pct']; ?>%"></div>
                    </div>
                    <div class="small mt-2">
                        <?php if (!$earned) { ?>
                            Still needed: <?php echo $e(implode(', ', $v['missing'])); ?>.
                        <?php } else { ?>
                            Ready to apply.
                        <?php } ?>
                    </div>
                    <div class="small text-muted mt-1"><?php echo $e($v['counts']); ?>.</div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php foreach ($rac['notes'] as $note) { ?>
    <div class="alert alert-info small mb-2"><?php echo $e($note); ?></div>
    <?php } ?>

    <!-- Interactive Canada Provinces & Territories Map -->
    <div class="card rac-map-card mb-3">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 px-3">
            <span class="fw-semibold d-inline-flex align-items-center gap-2">
                <i class="fas fa-map-marked-alt text-primary"></i>
                <span>Canada Provinces &amp; Territories Map</span>
            </span>
            <div class="d-flex flex-wrap align-items-center gap-1" id="racMapModePills" role="group" aria-label="Filter map by mode">
                <button type="button" class="rac-mode-pill is-active" data-rac-mode="Mixed">Mixed</button>
                <button type="button" class="rac-mode-pill" data-rac-mode="Phone">Phone</button>
                <button type="button" class="rac-mode-pill" data-rac-mode="CW">CW</button>
                <button type="button" class="rac-mode-pill" data-rac-mode="Digital">Digital</button>
            </div>
        </div>
        <div class="card-body p-0">
            <div id="racmap" style="width: 100%; height: 430px;"></div>
        </div>
    </div>
    <script>
        var racMapData = <?php echo json_encode(isset($rac_map_data) ? $rac_map_data : array()); ?>;
        var racTileUrl = <?php echo json_encode(!empty($tile_url) ? $tile_url : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'); ?>;
    </script>

    <?php foreach ($rac['tables'] as $table) { ?>
    <h5 class="mt-4"><?php echo $e($table['title']); ?></h5>
    <div class="table-responsive">
    <table class="table table-sm table-bordered table-hover <?php echo empty($table['status']) ? 'table-striped' : ''; ?>">
        <thead>
            <tr>
                <?php foreach ($table['columns'] as $column) { ?>
                <th><?php echo $e($column); ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($table['rows'] as $row) { ?>
            <tr>
                <?php foreach ($row as $cell) {
                    $class = '';
                    if (!empty($table['status'])) {
                        $class = $cell === 'C' ? 'table-success text-center' : ($cell === 'W' ? 'table-warning text-center' : '');
                    }
                ?>
                <td class="<?php echo $class; ?>"><?php echo $e($cell); ?></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
    <?php if (!empty($table['status'])) { ?>
    <div class="form-text mb-2">C is confirmed, W is worked but not confirmed.</div>
    <?php } ?>
    <?php } ?>
</div>
