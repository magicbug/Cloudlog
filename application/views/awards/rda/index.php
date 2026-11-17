<?php
$custom_date_format = $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format');
$format_date = function ($time) use ($custom_date_format) {
    return $time ? date($custom_date_format, strtotime($time)) : '';
};

$next_level = null;
foreach ($rda['levels'] as $level) {
    if (!$level['confirmed']) {
        $next_level = $level;
        break;
    }
}
$pct = function ($count, $total) {
    return $total > 0 ? round($count / $total * 100, 1) : 0;
};

$qsl_string = ($filters['qsl'] ? 'Q' : '') . ($filters['lotw'] ? 'L' : '') . ($filters['eqsl'] ? 'E' : '');

$rows = array();
$reference = $this->rda_award->load_reference();
foreach ($reference['districts'] as $district) {
    if (!empty($district['deleted'])) {
        continue;
    }
    $logged = isset($rda['districts'][$district['code']]) ? $rda['districts'][$district['code']] : null;
    $rows[] = array(
        $district['code'],
        $district['name'],
        $district['oblast'],
        $logged ? ($logged['confirmed'] ? 'Confirmed' : 'Worked') : 'Not worked',
        $logged ? $logged['call'] : '',
        $logged ? $logged['band'] : '',
        $logged ? $format_date($logged['time']) : '',
        $logged ? strtotime($logged['time']) : 0,
    );
}
?>
<script>
    var tileUrl = "<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>";
    var rdaDistricts = <?php echo json_encode($rows, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    var rdaFilters = <?php echo json_encode(array('band' => $filters['band'], 'mode' => $filters['mode'], 'qsl' => $filters['qsl'] ? 1 : 0, 'lotw' => $filters['lotw'] ? 1 : 0, 'eqsl' => $filters['eqsl'] ? 1 : 0)); ?>;
</script>
<div class="container">
    <style>
        .rda-levels { display: flex; flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1rem; }
        .rda-level { flex: 1 1 110px; border: 1px solid var(--bs-border-color); border-radius: 0.6rem; padding: 0.5rem 0.75rem; text-align: center; }
        .rda-level.reached { border-color: #198754; background-color: rgba(25, 135, 84, 0.12); }
        .rda-level.worked { border-color: #fd7e14; }
        .rda-level-name { font-weight: 700; }
        .rda-level-sub { font-size: 0.75rem; color: var(--bs-secondary-color); }
        .rda-progress { min-width: 80px; margin-bottom: 0; height: 0.8rem; border-radius: 999px; }
        .rda-progress .progress-bar { font-size: 0.65rem; font-weight: 600; }
        #rdamap { height: 70vh; min-height: 400px; max-height: 750px; }
        #rdamap .legend { padding: 6px 8px; font: 14px Arial, Helvetica, sans-serif; background: rgba(255, 255, 255, 0.8); line-height: 24px; color: #555; border-radius: 5px; }
        #rdamap .legend h4 { text-align: center; font-size: 16px; margin: 2px 12px 8px; color: #555; }
        #rdamap .legend span { position: relative; bottom: 3px; color: #555; }
        #rdamap .legend i { width: 18px; height: 18px; float: left; margin: 0 8px 0 0; opacity: 0.7; }
        #rdamap .rda-info { padding: 6px 8px; background: rgba(255, 255, 255, 0.9); color: #222; border-radius: 5px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.2); }
        #rdamap .rda-info h4 { margin: 0 0 5px; font-size: 0.95rem; }
    </style>

    <br>
    <div id="awardInfoButton">
        <script>
        var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
        var lang_award_info_ln1 = "<?php echo lang('awards_rda_description_ln1'); ?>";
        var lang_award_info_ln2 = "<?php echo lang('awards_rda_description_ln2'); ?>";
        var lang_award_info_ln3 = "<?php echo lang('awards_rda_description_ln3'); ?>";
        var lang_award_info_ln4 = "<?php echo lang('awards_rda_description_ln4'); ?>";
        </script>
        <h2><?php echo $page_title; ?></h2>
        <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
    </div>

    <form class="form mt-3" action="<?php echo site_url('awards/rda'); ?>" method="post" enctype="multipart/form-data">
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
                    <a href="<?php echo site_url('awards/rda'); ?>" class="btn btn-sm btn-warning">Reset</a>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                </div>
            </div>
        </fieldset>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Districts worked</div>
                <div class="fs-3 fw-bold"><?php echo $rda['worked']; ?></div>
                <div class="small text-muted">of <?php echo $rda['total']; ?> current districts</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Districts confirmed</div>
                <div class="fs-3 fw-bold text-success"><?php echo $rda['confirmed']; ?></div>
                <div class="small text-muted"><?php echo $pct($rda['confirmed'], $rda['total']); ?>% of the list</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">Next level</div>
                <div class="fs-3 fw-bold"><?php echo $next_level ? $next_level['name'] : 'All reached'; ?></div>
                <div class="small text-muted"><?php echo $next_level ? $next_level['remaining'] . ' more confirmed' : 'Every current district confirmed'; ?></div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card h-100"><div class="card-body">
                <div class="text-muted small">District list</div>
                <div class="fs-3 fw-bold"><?php echo $rda['updated'] ? $format_date($rda['updated']) : 'Missing'; ?></div>
                <div class="small text-muted">rdaward.org, refreshed from Maintenance</div>
            </div></div>
        </div>
    </div>

    <div class="rda-levels">
        <?php foreach ($rda['levels'] as $level) {
            $class = $level['confirmed'] ? ' reached' : ($level['worked'] ? ' worked' : '');
        ?>
        <div class="rda-level<?php echo $class; ?>">
            <div class="rda-level-name"><?php echo $level['name']; ?></div>
            <div class="rda-level-sub"><?php echo $level['target']; ?> districts</div>
            <div class="rda-level-sub">
                <?php if ($level['confirmed']) { ?>Reached<?php } elseif ($level['worked']) { ?>Worked, <?php echo $level['remaining']; ?> to confirm<?php } else { ?><?php echo $level['remaining']; ?> to go<?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>

    <?php if ($rda['too_early'] > 0) { ?>
    <div class="alert alert-info small">
        <?php echo $rda['too_early']; ?> contact<?php echo $rda['too_early'] == 1 ? ' was' : 's were'; ?> made before the district was added to the list, and do not count.
    </div>
    <?php } ?>

    <ul class="nav nav-tabs" id="rdaTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="oblasts-tab" data-bs-toggle="tab" href="#rdaoblasts" role="tab" aria-controls="rdaoblasts" aria-selected="true">Oblasts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="districts-tab" data-bs-toggle="tab" href="#rdadistricts" role="tab" aria-controls="rdadistricts" aria-selected="false">Districts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="map-tab" data-bs-toggle="tab" href="#rdamaptab" role="tab" aria-controls="rdamaptab" aria-selected="false">Map</a>
        </li>
    </ul>
    <br />

    <div class="tab-content">
        <div class="tab-pane fade show active table-responsive" id="rdaoblasts" role="tabpanel" aria-labelledby="oblasts-tab">
            <table style="width:100%" id="rdaoblasttable" class="table table-sm table-bordered table-hover table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Oblast</th>
                        <th>Prefix</th>
                        <th>Worked</th>
                        <th>Confirmed</th>
                        <th>Districts</th>
                        <th>Left</th>
                        <th>Worked progress</th>
                        <th>Confirmed progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rda['oblasts'] as $oblast) {
                        $worked_pct = $pct($oblast['worked'], $oblast['total']);
                        $confirmed_pct = $pct($oblast['confirmed'], $oblast['total']);
                    ?>
                    <tr>
                        <td><a href="#" class="rda-oblast-link" data-oblast="<?php echo $oblast['code']; ?>"><?php echo $oblast['code']; ?></a></td>
                        <td class="text-start"><?php echo htmlspecialchars($oblast['name']); ?></td>
                        <td><?php echo $oblast['prefix']; ?></td>
                        <td><?php echo $oblast['worked']; ?></td>
                        <td><?php echo $oblast['confirmed']; ?></td>
                        <td><?php echo $oblast['total']; ?></td>
                        <td><?php echo $oblast['total'] - $oblast['worked']; ?></td>
                        <td data-order="<?php echo $worked_pct; ?>">
                            <div class="progress rda-progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $worked_pct; ?>%"><?php echo $worked_pct; ?>%</div>
                            </div>
                        </td>
                        <td data-order="<?php echo $confirmed_pct; ?>">
                            <div class="progress rda-progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $confirmed_pct; ?>%"><?php echo $confirmed_pct; ?>%</div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th class="text-start">Total</th>
                        <th></th>
                        <th><?php echo $rda['worked']; ?></th>
                        <th><?php echo $rda['confirmed']; ?></th>
                        <th><?php echo $rda['total']; ?></th>
                        <th><?php echo $rda['total'] - $rda['worked']; ?></th>
                        <th><?php echo $pct($rda['worked'], $rda['total']); ?>%</th>
                        <th><?php echo $pct($rda['confirmed'], $rda['total']); ?>%</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="tab-pane fade" id="rdadistricts" role="tabpanel" aria-labelledby="districts-tab">
            <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                <select id="rdaStatusFilter" class="form-select form-select-sm w-auto">
                    <option value="">Every district</option>
                    <option value="Worked|Confirmed">Worked or confirmed</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Worked">Worked, not confirmed</option>
                    <option value="Not worked">Not worked</option>
                </select>
                <span id="rdaOblastFilter" class="badge text-bg-primary" style="display:none"></span>
            </div>
            <table style="width:100%" id="rdadistricttable" class="table table-sm table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>District</th>
                        <th>Oblast</th>
                        <th>Status</th>
                        <th>Callsign</th>
                        <th>Band</th>
                        <th>Date</th>
                    </tr>
                </thead>
            </table>

            <?php if (!empty($rda['deleted'])) { ?>
            <h5 class="mt-4">Deleted codes in the log</h5>
            <p class="text-muted small">These districts were removed from the RDA list and do not count. Where the list names a replacement, the area is now that code.</p>
            <table class="table table-sm table-bordered w-auto">
                <thead><tr><th>Code</th><th>Replaced by</th><th>Callsign</th><th>Date</th><th>QSOs</th></tr></thead>
                <tbody>
                <?php foreach ($rda['deleted'] as $row) { ?>
                    <tr>
                        <td><a href="javascript:displayContacts(<?php echo htmlspecialchars(json_encode($row['code'])); ?>, rdaFilters.band, rdaFilters.mode, 'RDA', '')"><?php echo $row['code']; ?></a></td>
                        <td><?php echo $row['replaced_by'] ?: '-'; ?></td>
                        <td><?php echo htmlspecialchars($row['call']); ?></td>
                        <td><?php echo $format_date($row['time']); ?></td>
                        <td><?php echo $row['qsos']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } ?>

            <?php if (!empty($rda['unknown'])) { ?>
            <h5 class="mt-4">Codes not on the list</h5>
            <p class="text-muted small">These look like RDA codes but are not in the current list. Check the county field on the contact.</p>
            <table class="table table-sm table-bordered w-auto">
                <thead><tr><th>Code</th><th>Callsign</th><th>Date</th><th>QSOs</th></tr></thead>
                <tbody>
                <?php foreach ($rda['unknown'] as $row) { ?>
                    <tr>
                        <td><a href="javascript:displayContacts(<?php echo htmlspecialchars(json_encode($row['code'])); ?>, rdaFilters.band, rdaFilters.mode, 'RDA', '')"><?php echo $row['code']; ?></a></td>
                        <td><?php echo htmlspecialchars($row['call']); ?></td>
                        <td><?php echo $format_date($row['time']); ?></td>
                        <td><?php echo $row['qsos']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } ?>
        </div>

        <div class="tab-pane fade" id="rdamaptab" role="tabpanel" aria-labelledby="map-tab">
            <div id="rdamap" class="map-leaflet"></div>
            <p class="text-muted small mt-2">Each oblast is shaded by its share of current districts worked. Green means every district in it is confirmed. Regions without a boundary in the map data are in the Oblasts table only.</p>
        </div>
    </div>
</div>
