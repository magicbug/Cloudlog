<style>
    #cqmap {
	height: calc(100vh) !important;
	max-height: 900px !important;
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
  color: #777;
}
.legend span {
  position: relative;
  bottom: 3px;
}
.legend i {
  width: 18px;
  height: 18px;
  float: left;
  margin: 0 8px 0 0;
  opacity: 0.7;
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

.cq-link {
  color: #007bff;
  cursor: pointer;
  text-decoration: underline;
}

.cq-link:hover {
  color: #0056b3;
}

.tab-pane {
  animation: fadeIn 0.3s;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>

<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_cq_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_cq_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_cq_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_cq_description_ln4'); ?>";
            </script>
            <h2><?php echo lang('awards_cq_page_title'); ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <!-- Filters Section (moved to top) -->
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
                    <button type="button" class="btn preset-btn btn-outline-secondary btn-sm" onclick="resetFilters()">Reset All</button>
                </div>

                <form class="form" action="<?php echo site_url('awards/cq'); ?>" method="post" enctype="multipart/form-data" id="cqForm">

                    <!-- Multiple Checkboxes (inline) -->
                    <div class="mb-3 row">
                        <div class="col-md-2" for="checkboxes">Worked / Confirmed</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="worked" id="worked" value="1" <?php if ($this->input->post('worked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="worked"><?php echo lang('awards_show_worked'); ?></label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="confirmed" id="confirmed" value="1" <?php if ($this->input->post('confirmed') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="confirmed"><?php echo lang('awards_show_confirmed'); ?></label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="notworked" id="notworked" value="1" <?php if ($this->input->post('notworked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="notworked"><?php echo lang('awards_show_not_worked'); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-md-2">QSL Type</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if ($this->input->post('qsl') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="qsl"><?php echo lang('general_word_qslcard'); ?></label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if ($this->input->post('lotw') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="lotw"><?php echo lang('lotw_short'); ?></label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="eqsl" value="1" id="eqsl" <?php if ($this->input->post('eqsl')) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="eqsl"><?php echo lang('eqsl_short'); ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-md-2 control-label" for="band">Band</label>
                        <div class="col-md-2">
                            <select id="band2" name="band" class="form-select form-select-sm">
                                <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >All Bands</option>
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
                            <?php if ($cq_array) {
                                ?><button type="button" onclick="load_cq_map();" class="btn btn-info btn-sm"><i class="fas fa-globe-americas"></i> Show CQ Zone Map</button>
                                <button type="button" onclick="exportTableToCSV('cq_table.csv');" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Export CSV</button>
                            <?php }?>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Summary Section -->
    <?php if ($cq_array): ?>
    <div class="row mt-4 mb-4">
        <!-- Worked vs Confirmed -->
        <div class="col-md-6">
            <div class="stats-card">
                <h5>CQ Zone Progress</h5>
                <div class="row">
                    <div class="col-6">
                        <div class="progress-label">Total Worked</div>
                        <div class="stats-number"><?php echo count(array_filter($cq_summary['worked'], function($v) { return $v > 0; })); ?></div>
                    </div>
                    <div class="col-6">
                        <div class="progress-label">Total Confirmed</div>
                        <div class="stats-number" style="color: #28a745;"><?php echo count(array_filter($cq_summary['confirmed'], function($v) { return $v > 0; })); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Milestones -->
        <div class="col-md-6">
            <div class="stats-card milestone">
                <h5>🏆 WAZ Milestone Progress</h5>
                <div class="row text-center">
                    <div class="col-6">
                        <div style="font-size: 14px;">All Worked</div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php 
                            $all_zones_worked = count(array_filter($cq_summary['worked'], function($v) { return $v > 0; })) == 40;
                            echo $all_zones_worked ? '✓' : '✗'; 
                            ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="font-size: 14px;">All Confirmed</div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php 
                            $all_zones_confirmed = count(array_filter($cq_summary['confirmed'], function($v) { return $v > 0; })) == 40;
                            echo $all_zones_confirmed ? '✓' : '✗'; 
                            ?>
                        </div>
                    </div>
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
            <a class="nav-link" id="map-tab" onclick="load_cq_map();" data-bs-toggle="tab" href="#cqmaptab" role="tab" aria-controls="home" aria-selected="false">Map</a>
        </li>
    </ul>
    <br />

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="cqmaptab" role="tabpanel" aria-labelledby="home-tab">
            <br />
            <div id="cqmap" class="map-leaflet" ></div>
        </div>

        <!-- Table Search Box -->
        <?php if ($cq_array): ?>
        <div class="table-search">
            <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search CQ Zone by number...">
        </div>
        <?php endif; ?>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab" style="margin-bottom: 30px;">
        
<?php
    $i = 1;
    if ($cq_array) {
    echo '
                <table style="width:100%" class="table-sm table tablecq table-bordered table-hover table-striped table-condensed text-center" id="cq_table">
                    <thead>
                    <tr>
                        <td class="sortable" data-column="0">CQ Zone</td>';
        foreach($bands as $band) {
            echo '<td class="sortable" data-column="' . (1 + array_search($band, $bands)) . '">' . $band . '</td>';
        }
        echo '</tr>
                    </thead>
                    <tbody>';
        foreach ($cq_array as $cq => $value) {      // Fills the table with the data
            echo '<tr>
                        <td>'. $cq .'</td>';
            foreach ($value as $name => $key) {
                echo '<td style="text-align: center">' . $key . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
        echo "<h2 class=\"mt-5\">" . lang('awards_summary') . "</h2>";

        echo '
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

        <tr><td>' . lang('awards_total_worked') . '</td>';

        foreach ($cq_summary['worked'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo '</tr><tr>
        <td>' . lang('awards_total_confirmed') . '</td>';
        foreach ($cq_summary['confirmed'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo '</tr>
        </table>';

    }
    else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    }
    ?>

            </div>
        </div>
</div>
