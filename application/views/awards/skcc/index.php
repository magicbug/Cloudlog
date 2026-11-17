<?php
$custom_date_format = $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format');
$format_date = function ($time) use ($custom_date_format) {
    return $time ? date($custom_date_format, strtotime($time)) : '';
};
$awards = array(
    array('key' => 'centurion', 'name' => 'Centurion', 'gate' => '', 'counts' => 'Unique SKCC members'),
    array('key' => 'tribune', 'name' => 'Tribune', 'gate' => 'Unlocks at Centurion', 'counts' => 'Unique C, T, or S members after Centurion'),
    array('key' => 'senator', 'name' => 'Senator', 'gate' => 'Unlocks at Tribune x8 (400)', 'counts' => 'Unique T or S members after Tribune x8'),
);
?>
<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_skcc_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_skcc_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_skcc_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_skcc_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <form class="form mt-3" action="<?php echo site_url('awards/skcc'); ?>" method="post" enctype="multipart/form-data">
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
                    <div class="form-text">Leave all unticked to count every logged SKCC contact.</div>
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
                    <a href="<?php echo site_url('awards/skcc'); ?>" class="btn btn-sm btn-warning">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="row g-3 mb-3">
        <?php foreach ($awards as $award) {
            $s = $skcc[$award['key']];
            $earned = $s['level'] > 0;
            $target = $s['next'];
            $pct = $target ? round($s['count'] / $target * 100) : 100;
        ?>
        <div class="col-md-4">
            <div class="card h-100 <?php echo $earned ? 'border-success' : ''; ?>">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="text-muted small"><?php echo $award['name']; ?></div>
                        <?php if (!$s['unlocked']) { ?>
                            <span class="badge text-bg-secondary"><i class="fas fa-lock"></i> Locked</span>
                        <?php } elseif ($earned) { ?>
                            <span class="badge text-bg-success"><?php echo $s['label']; ?></span>
                        <?php } ?>
                    </div>
                    <div class="fs-2 fw-bold <?php echo $earned ? 'text-success' : ''; ?>">
                        <?php echo $s['count']; ?><?php if ($target) { ?> / <?php echo $target; ?><?php } ?>
                    </div>
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar <?php echo $earned ? 'bg-success' : ''; ?>" role="progressbar" style="width: <?php echo $s['unlocked'] ? $pct : 0; ?>%"></div>
                    </div>
                    <div class="small mt-2">
                        <?php if (!$s['unlocked']) { ?>
                            <?php echo $award['gate']; ?>.
                        <?php } elseif ($target) { ?>
                            <?php echo $s['remaining']; ?> more for <?php echo $s['next_label']; ?>.
                        <?php } else { ?>
                            Highest level reached.
                        <?php } ?>
                    </div>
                    <div class="small text-muted"><?php echo $award['counts']; ?>.</div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if ($skcc['bare_members'] > 0) { ?>
    <div class="alert alert-info small">
        <?php echo $skcc['bare_members']; ?> member<?php echo $skcc['bare_members'] == 1 ? ' is' : 's are'; ?> logged with a number only.
        They count toward Centurion. Tribune and Senator need the C, T, or S that was sent with the number, for example <code>14086T</code>.
    </div>
    <?php } ?>

    <h5>Levels earned</h5>
    <?php if (empty($skcc['earned'])) { ?>
        <p class="text-muted">None yet.</p>
    <?php } else { ?>
    <table class="table table-sm table-bordered w-auto">
        <thead>
            <tr>
                <th>Level</th>
                <th>Award</th>
                <th>Members</th>
                <th>Reached</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skcc['earned'] as $row) { ?>
            <tr>
                <td><span class="badge text-bg-success"><?php echo $row['label']; ?></span></td>
                <td><?php echo $row['award']; ?></td>
                <td><?php echo $row['count']; ?></td>
                <td><?php echo $format_date($row['time']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>

    <h5 class="mt-4">Members worked</h5>
    <?php if (empty($skcc['members'])) { ?>
        <p class="text-muted">No SKCC numbers in this logbook yet. Cloudlog reads the SKCC field from an ADIF import, or SIG <code>SKCC</code> with the number in SIG Info.</p>
    <?php } else { ?>
    <table id="skcctable" class="table table-sm table-bordered table-hover table-striped text-center w-100">
        <thead>
            <tr>
                <th>SKCC #</th>
                <th>Suffix</th>
                <th>Callsign</th>
                <th>Name</th>
                <th>First QSO</th>
                <th>Band</th>
                <th>Mode</th>
                <th>QSOs</th>
                <th>Counted for</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($skcc['members'] as $member) { ?>
            <tr>
                <td><?php echo $member['number']; ?></td>
                <td><?php echo $member['suffix']; ?></td>
                <td><?php echo htmlspecialchars($member['call']); ?></td>
                <td><?php echo htmlspecialchars((string) $member['name']); ?></td>
                <td data-order="<?php echo strtotime($member['time']); ?>"><?php echo $format_date($member['time']); ?></td>
                <td><?php echo htmlspecialchars($member['band']); ?></td>
                <td><?php echo htmlspecialchars($member['mode']); ?></td>
                <td><?php echo $member['qsos']; ?></td>
                <td><?php echo implode(', ', $member['counted']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>
