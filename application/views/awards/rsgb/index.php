<?php
$e = function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
};
$group = $rsgb_groups[$rsgb_award];
$cards = $group['cards'];
$csv_query = http_build_query(array(
    'award' => $rsgb_award,
    'filtered' => 1,
    'qsl' => $filters['qsl'] ? 1 : 0,
    'lotw' => $filters['lotw'] ? 1 : 0,
    'eqsl' => $filters['eqsl'] ? 1 : 0,
));
?>
<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_rsgb_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_rsgb_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_rsgb_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_rsgb_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <ul class="nav nav-pills mt-3" id="rsgbAwards">
        <?php foreach ($rsgb_groups as $key => $g) { ?>
        <li class="nav-item">
            <a class="nav-link<?php echo $key === $rsgb_award ? ' active' : ''; ?>" href="<?php echo site_url('awards/rsgb') . '?award=' . $e($key); ?>"><?php echo $e($g['name']); ?></a>
        </li>
        <?php } ?>
    </ul>
    <?php if (!isset($rsgb_groups['foundation']) && !isset($rsgb_groups['intermediate'])) { ?>
    <div class="form-text">Foundation and Intermediate 100 appear when a station location in this logbook has a Foundation (M3, M6, M7) or Intermediate (2E0, 2E1, M8, M9) callsign.</div>
    <?php } ?>

    <form class="form mt-3" action="<?php echo site_url('awards/rsgb'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="award" value="<?php echo $e($rsgb_award); ?>">
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
                        <?php if ($cards) { ?>
                            The RSGB accepts QSL cards, LoTW, and Club Log. Leave all unticked to count every logged contact as confirmed.
                        <?php } else { ?>
                            This award does not need QSLs, so every logged contact counts. Tick a type to count only contacts confirmed that way.
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <a href="<?php echo site_url('awards/rsgb') . '?award=' . $e($rsgb_award); ?>" class="btn btn-sm btn-warning">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                    <a href="<?php echo site_url('awards/rsgb_csv') . '?' . $e($csv_query); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-file-csv"></i> Contacts that count (CSV)</a>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="row g-3 mb-3">
        <?php foreach ($rsgb['variants'] as $v) {
            $earned = $v['level'] !== null;
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 <?php echo $earned ? 'border-success' : ''; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="fw-bold"><?php echo $e($v['name']); ?></div>
                        <?php if ($v['locked']) { ?>
                            <span class="badge text-bg-secondary"><i class="fas fa-lock"></i> Locked</span>
                        <?php } elseif ($earned) { ?>
                            <span class="badge text-bg-success"><?php echo $e($v['level']); ?></span>
                        <?php } ?>
                    </div>
                    <div class="text-muted small"><?php echo $cards ? 'Confirmed' : 'Counted'; ?></div>
                    <div class="fs-2 fw-bold <?php echo $earned ? 'text-success' : ''; ?>">
                        <?php echo $e($v['confirmed']); ?><?php if ($v['target'] !== null) { ?> / <?php echo $e($v['target']); ?><?php } ?>
                    </div>
                    <?php if ($cards || $v['worked'] != $v['confirmed']) { ?>
                    <div class="small text-muted"><?php echo $cards ? 'Worked' : 'Logged'; ?>: <?php echo $e($v['worked']); ?></div>
                    <?php } ?>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar <?php echo $earned ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo $v['locked'] ? 0 : (int) $v['pct']; ?>%"></div>
                    </div>
                    <div class="small mt-2">
                        <?php if ($v['locked']) { ?>
                            <?php echo $e($v['gate']); ?>.
                        <?php } elseif ($v['next'] !== null) { ?>
                            Next is <?php echo $e($v['next']); ?>: <?php echo $e(implode(', ', $v['missing'])); ?>.
                        <?php } else { ?>
                            Highest level reached.
                        <?php } ?>
                    </div>
                    <div class="small text-muted mt-1"><?php echo $e($v['counts']); ?>.</div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php foreach ($rsgb['notes'] as $note) { ?>
    <div class="alert alert-info small mb-2"><?php echo $e($note); ?></div>
    <?php } ?>

    <?php foreach ($rsgb['tables'] as $table) { ?>
    <h5 class="mt-4"><?php echo $e($table['title']); ?></h5>
    <div class="table-responsive">
    <table class="table table-sm table-bordered table-hover <?php echo empty($table['status']) ? 'table-striped' : 'text-center'; ?>">
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
                        $class = $cell === 'C' ? 'table-success' : ($cell === 'W' ? 'table-warning' : '');
                    }
                ?>
                <td class="<?php echo $class; ?>"><?php echo $e($cell); ?></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
    <?php } ?>
</div>
