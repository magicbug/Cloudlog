<script>
	var tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server');?>"
</script>

<style>
    #wajamap {
	height: calc(100vh) !important;
	max-height: 900px !important;
}
/* Shared visual styles to align with DXCC */
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
/*Legend specific*/
.legend {
  padding: 6px 8px;
  font: 14px Arial, Helvetica, sans-serif;
  background: white;
  background: rgba(255, 255, 255, 0.8);
  line-height: 24px;
  color: #555;
}
.legend h4 {
  text-align: center;
  font-size: 16px;
  margin: 2px 12px 8px;
  color: #555;
}
.legend span {
  position: relative;
  bottom: 3px;
  color: #555;
}
.legend i {
  width: 18px;
  height: 18px;
  float: left;
  margin: 0 8px 0 0;
  opacity: 0.7;
  color: #555;
}

.info {
    padding: 6px 8px;
    font: 14px/16px Arial, Helvetica, sans-serif;
    background: white;
    background: rgba(255,255,255,0.8);
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    border-radius: 5px;
	color: #555;
}
.info h4 {
    margin: 0 0 5px;
    color: #555;
}
</style>
<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_waja_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_waja_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_waja_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_waja_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <!-- Filters Section (collapsible card like DXCC) -->
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
                    <button type="button" class="btn preset-btn btn-outline-primary btn-sm" onclick="applyPreset('worked')">Show Worked Only</button>
                    <button type="button" class="btn preset-btn btn-outline-primary btn-sm" onclick="applyPreset('unworked')">Show Unworked</button>
                    <button type="button" class="btn preset-btn btn-outline-primary btn-sm" onclick="applyPreset('lotwonly')">LoTW Only</button>
                    <button type="button" class="btn preset-btn btn-outline-secondary btn-sm" onclick="resetFilters()">Reset All</button>
                </div>

                <form class="form" action="<?php echo site_url('awards/waja'); ?>" method="post" enctype="multipart/form-data" id="wajaForm">

                    <div class="mb-3 row">
                        <div class="col-md-2" for="checkboxes">Worked / Confirmed</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="worked" id="worked" value="1" <?php if ($this->input->post('worked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="worked">Show worked</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="confirmed" id="confirmed" value="1" <?php if ($this->input->post('confirmed') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="confirmed">Show confirmed</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="notworked" id="notworked" value="1" <?php if ($this->input->post('notworked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="notworked">Show not worked</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-2">QSL Type</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if ($this->input->post('qsl') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="qsl">QSL</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if ($this->input->post('lotw') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="lotw">LoTW</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="eqsl" value="1" id="eqsl" <?php if ($this->input->post('eqsl')) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="eqsl">eQSL</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 control-label" for="band">Band</label>
                        <div class="col-md-2">
                            <select id="band2" name="band" class="form-select form-select-sm">
                                <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >Every band</option>
                                <?php foreach($worked_bands as $band) {
                                    echo '<option value="' . $band . '"';
                                    if ($this->input->post('band') == $band) echo ' selected';
                                    echo '>' . $band . '</option>'."\n";
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 control-label" for="mode">Mode</label>
                        <div class="col-md-2">
                        <select id="mode" name="mode" class="form-select form-select-sm">
                            <option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'mode') echo ' selected'; ?>>All</option>
                            <?php
                            foreach($modes->result() as $mode){
                                if ($mode->submode == null) {
                                    echo '<option value="' . $mode->mode . '"';
                                    if ($this->input->post('mode') == $mode->mode) echo ' selected';
                                    echo '>'. $mode->mode . '</option>'."\n";
                                } else {
                                    echo '<option value="' . $mode->submode . '"';
                                    if ($this->input->post('mode') == $mode->submode) echo ' selected';
                                    echo '>' . $mode->submode . '</option>'."\n";
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
                            <?php if ($waja_array) {
                                ?><button type="button" onclick="load_waja_map();" class="btn btn-info btn-sm"><i class="fas fa-globe-americas"></i> Show WAJA Map</button>
                            <?php }?>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Summary Section (WAJA specific) -->
    <?php if (isset($waja_summary) && is_array($waja_summary) && isset($waja_summary['worked']) && isset($waja_summary['confirmed'])): ?>
    <?php 
        $waja_total_worked = isset($waja_summary['worked']['Total']) ? (int)$waja_summary['worked']['Total'] : 0;
        $waja_total_confirmed = isset($waja_summary['confirmed']['Total']) ? (int)$waja_summary['confirmed']['Total'] : 0;
        $waja_goal = 47; // WAJA total prefectures
        $milestones = [10, 25, 47];
    ?>
    <div class="row mt-4 mb-4">
        <!-- Worked vs Confirmed -->
        <div class="col-md-6">
            <div class="stats-card">
                <h5>WAJA Progress</h5>
                <div class="row">
                    <div class="col-6">
                        <div class="progress-label">Total Worked</div>
                        <div class="stats-number"><?php echo $waja_total_worked; ?></div>
                        <div class="progress">
                            <?php $pct_worked = $waja_goal > 0 ? round(($waja_total_worked / $waja_goal) * 100) : 0; ?>
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $pct_worked; ?>%" aria-valuenow="<?php echo $pct_worked; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="progress-label">Total Confirmed</div>
                        <div class="stats-number" style="color: #28a745;">
                            <?php echo $waja_total_confirmed; ?></div>
                        <div class="progress">
                            <?php $pct_conf = $waja_goal > 0 ? round(($waja_total_confirmed / $waja_goal) * 100) : 0; ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $pct_conf; ?>%" aria-valuenow="<?php echo $pct_conf; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Milestones -->
        <div class="col-md-6">
            <div class="stats-card milestone <?php echo ($waja_total_confirmed >= 47) ? 'achieved' : ''; ?>">
                <h5>🏆 Milestone Progress</h5>
                <div class="row text-center">
                    <?php foreach ($milestones as $m): ?>
                    <div class="col-4">
                        <div style="font-size: 14px;"><?php echo $m; ?></div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php echo ($waja_total_confirmed >= $m) ? '✓' : '✗'; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-bs-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true">Table</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="map-tab" onclick="load_waja_map();" data-bs-toggle="tab" href="#wajamaptab" role="tab" aria-controls="home" aria-selected="false">Map</a>
        </li>
    </ul>
    <br />

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="wajamaptab" role="tabpanel" aria-labelledby="home-tab">
    <br />

    <div id="wajamap" class="map-leaflet" ></div>

    </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">

    <?php
    $i = 1;
    if ($waja_array) {
        echo '
                <table style="width:100%" class="table-sm table tablewaja table-bordered table-hover table-striped table-condensed text-center" id="waja_table">
                    <thead>
                    <tr>
						<td>Number</td>
						<td>Prefecture</td>';

        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
        }
        echo '</tr>
                    </thead>
                    <tbody>';
        foreach ($waja_array as $waja => $value) {      // Fills the table with the data
            echo '<tr>';
            foreach ($value as $name => $key) {
                if ($name === 'Prefecture') {
                    // Make prefecture clickable to view QSOs via existing displayContacts()
                    $stateCode = isset($value['Number']) ? $value['Number'] : '';
                    echo '<td style="text-align: left">'
                        . '<a href=\'javascript:displayContacts("' . $stateCode . '", document.getElementById("band2").value, document.getElementById("mode").value, "WAJA", "")\'>'
                        . htmlspecialchars($key)
                        . '</a>'
                        . '</td>';
                } else {
				echo '<td style="text-align: center">' . $key . '</td>';
                }
            }
            echo '</tr>';
        }
        echo '</table>
        <h2>Summary</h2>

        <table class="table-sm tablesummary table table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr><td></td>';

        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
        }
        echo '<td>Total</td>
        </tr>
        </thead>
        <tbody>

        <tr><td>Total worked</td>';

        foreach ($waja_summary['worked'] as $waja) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $waja . '</td>';
        }

        echo '</tr><tr>
        <td>Total confirmed</td>';
        foreach ($waja_summary['confirmed'] as $waja) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $waja . '</td>';
        }

        echo '</tr>
        </table>
        </div>';

    }
    else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    }
    ?>
                </div>
        </div>
</div>

<script>
// Preset filter functions for WAJA (mirroring DXCC behavior)
function applyPreset(preset) {
    const form = document.getElementById('wajaForm');
    if (!form) return;

    // Reset all checkboxes first
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);

    switch(preset) {
        case 'confirmed':
            document.getElementById('confirmed').checked = true;
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            break;
        case 'worked':
            document.getElementById('worked').checked = true;
            document.getElementById('confirmed').checked = true;
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            break;
        case 'unworked':
            document.getElementById('notworked').checked = true;
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            break;
        case 'lotwonly':
            document.getElementById('confirmed').checked = true;
            document.getElementById('lotw').checked = true;
            // LoTW only means QSL and eQSL are NOT checked (already unchecked from reset)
            break;
    }
}

function resetFilters() {
    document.getElementById('wajaForm').reset();
}
</script>
