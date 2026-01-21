<style>
    #wwffmap {
	height: calc(100vh) !important;
	max-height: 900px !important;
}

.stats-card {
  border-left: 4px solid #007bff;
  padding: 20px;
  margin-bottom: 15px;
  background-color: #f8f9fa;
  border-radius: 4px;
}

.stats-card.milestone {
  border-left-color: #28a745;
}

.stats-card.milestone.achieved {
  background-color: #d4edda;
}

.stats-card h5 {
  margin: 0 0 10px 0;
  color: #333;
  font-weight: 600;
}

.stats-number {
  font-size: 28px;
  font-weight: bold;
  color: #007bff;
  margin: 10px 0;
}

.milestone.achieved .stats-number {
  color: #28a745;
}

.progress {
  height: 24px;
  margin-top: 10px;
}

.progress-label {
  font-size: 12px;
  font-weight: 500;
}

.filter-collapse {
  cursor: pointer;
}

.preset-filters {
  margin-bottom: 15px;
}

.preset-btn {
  margin-right: 5px;
  margin-bottom: 5px;
  font-size: 12px;
}

.table-search {
  margin-bottom: 15px;
}

.sortable {
  cursor: pointer;
  user-select: none;
}

.sortable::after {
  content: ' ↕';
  font-size: 12px;
  color: #999;
}

.sortable.asc::after {
  content: ' ↑';
  color: #007bff;
}

.sortable.desc::after {
  content: ' ↓';
  color: #007bff;
}

.wwff-link {
  color: #007bff;
  cursor: pointer;
  text-decoration: underline;
}

.wwff-link:hover {
  color: #0056b3;
}
</style>

<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_wwff_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_wwff_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_wwff_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_wwff_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <?php if (!$wwff_all || $wwff_all->num_rows() == 0): ?>
    <div class="alert alert-info alert-dismissible fade show" role="alert" style="margin-top: 20px;">
        <i class="fas fa-leaf me-2"></i>
        <strong>No WWFF QSOs logged yet</strong>
        <p class="mb-0 mt-2">Start logging QSOs from World Wide Flora Fauna (WWFF) activations to track your progress. WWFF references identify national parks and nature reserves worldwide (e.g., <code>K-4500</code>, <code>G/LD-002</code>).</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php else: ?>

    <!-- Filters Section (collapsible card) -->
    <div class="card mb-4" style="margin-top: 20px;">
        <div class="card-header filter-collapse" data-bs-toggle="collapse" data-bs-target="#filterPanel" style="cursor: pointer;">
            <i class="fas fa-filter me-2"></i><strong>Filters</strong>
            <span class="float-end"><i class="fas fa-chevron-down"></i></span>
        </div>
        <div id="filterPanel" class="collapse show">
            <div class="card-body">
                <!-- Preset Filters -->
                <div class="preset-filters mb-3">
                    <label class="form-label">Quick Presets:</label>
                    <button type="button" class="btn preset-btn btn-outline-primary btn-sm" onclick="applyPreset('confirmed')">Show Confirmed Only</button>
                    <button type="button" class="btn preset-btn btn-outline-primary btn-sm" onclick="applyPreset('worked')">Show All Worked</button>
                    <button type="button" class="btn preset-btn btn-outline-primary btn-sm" onclick="applyPreset('lotwonly')">LoTW Only</button>
                    <button type="button" class="btn preset-btn btn-outline-secondary btn-sm" onclick="resetFilters()">Reset All</button>
                </div>

                <form class="form" action="<?php echo site_url('awards/wwff'); ?>" method="post" enctype="multipart/form-data" id="wwffForm">

                    <div class="mb-3 row">
                        <div class="col-md-2">QSL Type</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if (isset($postdata['qsl']) && $postdata['qsl']) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="qsl">QSL</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if (isset($postdata['lotw']) && $postdata['lotw']) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="lotw">LoTW</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="eqsl" value="1" id="eqsl" <?php if (isset($postdata['eqsl']) && $postdata['eqsl']) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="eqsl">eQSL</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 control-label" for="band">Band</label>
                        <div class="col-md-2">
                            <select id="band" name="band" class="form-select form-select-sm">
                                <option value="All" <?php if (!isset($postdata['band']) || $postdata['band'] == 'All') echo ' selected'; ?> >Every band</option>
                                <?php if (isset($worked_bands)) {
                                    foreach($worked_bands as $band) {
                                        echo '<option value="' . $band . '"';
                                        if (isset($postdata['band']) && $postdata['band'] == $band) echo ' selected';
                                        echo '>' . $band . '</option>'."\n";
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 control-label" for="mode">Mode</label>
                        <div class="col-md-2">
                        <select id="mode" name="mode" class="form-select form-select-sm">
                            <option value="All" <?php if (!isset($postdata['mode']) || $postdata['mode'] == 'All') echo ' selected'; ?>>All</option>
                            <?php
                            if (isset($modes)) {
                                foreach($modes->result() as $mode){
                                    if ($mode->submode == null) {
                                        echo '<option value="' . $mode->mode . '"';
                                        if (isset($postdata['mode']) && $postdata['mode'] == $mode->mode) echo ' selected';
                                        echo '>'. $mode->mode . '</option>'."\n";
                                    } else {
                                        echo '<option value="' . $mode->submode . '"';
                                        if (isset($postdata['mode']) && $postdata['mode'] == $mode->submode) echo ' selected';
                                        echo '>' . $mode->submode . '</option>'."\n";
                                    }
                                }
                            }
                            ?>
                        </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 control-label" for="button1id"></label>
                        <div class="col-md-10">
                            <button id="button2id" type="reset" name="button2id" class="btn btn-sm btn-warning">Reset</button>
                            <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Summary Section (WWFF specific) -->
    <?php if (isset($wwff_summary) && is_array($wwff_summary)): ?>
    <?php 
        $wwff_parks_worked = isset($wwff_summary['total_parks']) ? (int)$wwff_summary['total_parks'] : 0;
        $wwff_parks_confirmed = isset($wwff_summary['confirmed_parks']) ? (int)$wwff_summary['confirmed_parks'] : 0;
        $milestones = [10, 25, 50, 100];
    ?>
    <div class="row mt-4 mb-4">
        <!-- Worked vs Confirmed Parks -->
        <div class="col-md-6">
            <div class="stats-card">
                <h5>WWFF Progress</h5>
                <div class="row">
                    <div class="col-6">
                        <div class="progress-label">Parks Worked</div>
                        <div class="stats-number"><?php echo $wwff_parks_worked; ?></div>
                        <div class="progress">
                            <?php $pct_worked = $wwff_parks_worked > 0 ? round(($wwff_parks_worked / 100) * 100) : 0; if($pct_worked > 100) $pct_worked = 100; ?>
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $pct_worked; ?>%" aria-valuenow="<?php echo $pct_worked; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="progress-label">Parks Confirmed</div>
                        <div class="stats-number" style="color: #28a745;">
                            <?php echo $wwff_parks_confirmed; ?></div>
                        <div class="progress">
                            <?php $pct_conf = $wwff_parks_confirmed > 0 ? round(($wwff_parks_confirmed / 100) * 100) : 0; if($pct_conf > 100) $pct_conf = 100; ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $pct_conf; ?>%" aria-valuenow="<?php echo $pct_conf; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Milestones -->
        <div class="col-md-6">
            <div class="stats-card milestone <?php echo ($wwff_parks_confirmed >= 100) ? 'achieved' : ''; ?>">
                <h5>🏆 Milestone Progress</h5>
                <div class="row text-center">
                    <?php foreach ($milestones as $m): ?>
                    <div class="col-3">
                        <div style="font-size: 14px;"><?php echo $m; ?></div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php echo ($wwff_parks_confirmed >= $m) ? '✓' : '✗'; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

	<?php
		if ($wwff_all) {
   if($this->session->userdata('user_date_format')) {
      // If Logged in and session exists
      $custom_date_format = $this->session->userdata('user_date_format');
   } else {
      // Get Default date format from /config/cloudlog.php
      $custom_date_format = $this->config->item('qso_date_format');
   }
	?>
	
	<table style="width: 100%" id="wwfftable" class="wwfftable table table-sm table-striped table-hover">
	<thead>
		
	<tr>
		<th style="text-align: center"><?php echo lang('gen_hamradio_wwff_reference') ?></th>
		<th style="text-align: center"><?php echo lang('general_word_date') ?></th>
		<th style="text-align: center"><?php echo lang('general_word_time') ?></th>
		<th style="text-align: center"><?php echo lang('gen_hamradio_callsign') ?></th>
		<th style="text-align: center"><?php echo lang('gen_hamradio_band') ?></th>
		<th style="text-align: center"><?php echo lang('gen_hamradio_rsts') ?></th>
		<th style="text-align: center"><?php echo lang('gen_hamradio_rstr') ?></th>
	</tr>
	</thead>
	
	<tbody>
	<?php
		if ($wwff_all->num_rows() > 0) {
			foreach ($wwff_all->result() as $row) {
	?>
	
	<tr>
		<td style="text-align: center"><a href="javascript:displayContacts('" . htmlspecialchars($row->COL_WWFF_REF) . "', 'All', 'All', 'WWFF')" class="wwff-link"><?php echo $row->COL_WWFF_REF; ?></a></td>
		<td style="text-align: center"><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
		<td style="text-align: center"><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
		<td style="text-align: center"><?php echo $row->COL_CALL; ?></td>
		<td style="text-align: center"><?php if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo $row->COL_BAND; } ?></td>
		<td style="text-align: center"><?php echo $row->COL_RST_SENT; ?></td>
		<td style="text-align: center"><?php echo $row->COL_RST_RCVD; ?></td>
	</tr>
	<?php
		  }
		}
	?>
	
	</tbody>
	</table>
	<?php } else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    }?>
</div>

<?php endif; ?>

<script>
// Preset filter functions for WWFF
function applyPreset(preset) {
    const form = document.getElementById('wwffForm');
    if (!form) return;

    // Reset all checkboxes first
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

    switch(preset) {
        case 'confirmed':
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            break;
        case 'worked':
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            document.getElementById('eqsl').checked = true;
            break;
        case 'lotwonly':
            document.getElementById('lotw').checked = true;
            // LoTW only means QSL and eQSL are NOT checked
            break;
    }
}

function resetFilters() {
    document.getElementById('wwffForm').reset();
}
</script>
