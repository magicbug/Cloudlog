<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_wac_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_wac_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_wac_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_wac_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <form class="form mt-3" action="<?php echo site_url('awards/wac'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>

            <div class="mb-3 row">
                <div class="col-md-2">QSL Type</div>
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
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-md-2 control-label" for="band2">Band</label>
                <div class="col-md-2">
                    <select id="band2" name="band" class="form-select form-select-sm">
                        <option value="All" <?php if ($filters['band'] == 'All') echo ' selected'; ?>>Every band</option>
                        <?php foreach ($worked_bands as $band) {
                            echo '<option value="' . htmlspecialchars($band) . '"';
                            if ($filters['band'] == $band) echo ' selected';
                            echo '>' . htmlspecialchars($band) . '</option>' . "\n";
                        } ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-md-2 control-label" for="mode">Mode</label>
                <div class="col-md-2">
                    <select id="mode" name="mode" class="form-select form-select-sm">
                        <option value="All" <?php if ($filters['mode'] == 'All') echo ' selected'; ?>>All</option>
                        <?php foreach ($modes->result() as $mode) {
                            $value = ($mode->submode == null) ? $mode->mode : $mode->submode;
                            echo '<option value="' . htmlspecialchars($value) . '"';
                            if ($filters['mode'] == $value) echo ' selected';
                            echo '>' . htmlspecialchars($value) . '</option>' . "\n";
                        } ?>
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <a href="<?php echo site_url('awards/wac'); ?>" class="btn btn-sm btn-warning">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100 <?php echo $wac_worked >= $wac_required ? 'border-success' : ''; ?>">
                <div class="card-body">
                    <div class="text-muted small">Worked</div>
                    <div class="fs-2 fw-bold <?php echo $wac_worked >= $wac_required ? 'text-success' : ''; ?>"><?php echo $wac_worked; ?> / <?php echo $wac_required; ?></div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar <?php echo $wac_worked >= $wac_required ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo round($wac_worked / $wac_required * 100); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 <?php echo $wac_confirmed >= $wac_required ? 'border-success' : ''; ?>">
                <div class="card-body">
                    <div class="text-muted small">Confirmed</div>
                    <div class="fs-2 fw-bold <?php echo $wac_confirmed >= $wac_required ? 'text-success' : ''; ?>"><?php echo $wac_confirmed; ?> / <?php echo $wac_required; ?></div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo round($wac_confirmed / $wac_required * 100); ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <table id="wactable" class="table table-sm table-bordered table-hover table-striped text-center">
        <thead>
            <tr>
                <th class="text-start">Continent</th>
                <th>Code</th>
                <th>QSOs</th>
                <th>Confirmed</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($wac_rows as $row) {
                if ($row['confirmed'] > 0) {
                    $status = '<span class="badge text-bg-success">Confirmed</span>';
                } elseif ($row['qsos'] > 0) {
                    $status = '<span class="badge text-bg-warning">Worked</span>';
                } else {
                    $status = '<span class="badge text-bg-danger">Not worked</span>';
                }
            ?>
            <tr>
                <td class="text-start">
                    <?php echo htmlspecialchars($row['name']); ?>
                    <?php if (!$row['required']) { ?><span class="text-muted small">(not required)</span><?php } ?>
                </td>
                <td><?php echo $row['code']; ?></td>
                <td><?php echo $row['qsos']; ?></td>
                <td><?php echo $row['confirmed']; ?></td>
                <td><?php echo $status; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
