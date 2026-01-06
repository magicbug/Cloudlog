
<style>
    #dxccmap {
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

.dxcc-link {
  color: #007bff;
  cursor: pointer;
  text-decoration: underline;
}

.dxcc-link:hover {
  color: #0056b3;
}

.continent-card {
  cursor: pointer;
  user-select: none;
  transition: all 0.2s ease;
}

.continent-card:hover {
  background-color: #e9ecef;
  transform: translateY(-2px);
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            var lang_award_info_ln1 = "<?php echo lang('awards_dxcc_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_dxcc_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_dxcc_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_dxcc_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <!-- Statistics Summary Section -->
    <?php if ($dxcc_array): ?>
    <div class="row mt-4 mb-4">
        <!-- Worked vs Confirmed -->
        <div class="col-md-6">
            <div class="stats-card">
                <h5>DXCC Progress</h5>
                <div class="row">
                    <div class="col-6">
                        <div class="progress-label">Total Worked</div>
                        <div class="stats-number"><?php echo $dxcc_statistics['total_worked']; ?></div>
                    </div>
                    <div class="col-6">
                        <div class="progress-label">Total Confirmed</div>
                        <div class="stats-number" style="color: #28a745;"><?php echo $dxcc_statistics['total_confirmed']; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Milestones -->
        <div class="col-md-6">
            <div class="stats-card milestone <?php echo $dxcc_statistics['milestones'][100] ? 'achieved' : ''; ?>">
                <h5>🏆 Milestone Progress</h5>
                <div class="row text-center">
                    <div class="col-4">
                        <div style="font-size: 14px;">100</div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php echo $dxcc_statistics['milestones'][100] ? '✓' : '✗'; ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="font-size: 14px;">200</div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php echo $dxcc_statistics['milestones'][200] ? '✓' : '✗'; ?>
                        </div>
                    </div>
                    <div class="col-4">
                        <div style="font-size: 14px;">300</div>
                        <div style="font-size: 20px; margin-top: 5px;">
                            <?php echo $dxcc_statistics['milestones'][300] ? '✓' : '✗'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Continent Breakdown -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="stats-card">
                <h5>By Continent</h5>
                <div class="row">
                    <?php 
                    $continents = array(
                        'Africa' => 'Africa',
                        'Antarctica' => 'Antarctica',
                        'Asia' => 'Asia',
                        'Europe' => 'Europe',
                        'NorthAmerica' => 'North America',
                        'SouthAmerica' => 'South America',
                        'Oceania' => 'Oceania'
                    );
                    
                    $continentCodes = array(
                        'Africa' => 'AF',
                        'Antarctica' => 'AN',
                        'Asia' => 'AS',
                        'Europe' => 'EU',
                        'NorthAmerica' => 'NA',
                        'SouthAmerica' => 'SA',
                        'Oceania' => 'OC'
                    );
                    
                    foreach ($continents as $key => $label): 
                        $worked = isset($dxcc_statistics['worked_by_continent'][$key]) ? $dxcc_statistics['worked_by_continent'][$key] : 0;
                        $confirmed = isset($dxcc_statistics['confirmed_by_continent'][$key]) ? $dxcc_statistics['confirmed_by_continent'][$key] : 0;
                        $total = isset($continent_breakdown[$key]) ? $continent_breakdown[$key] : 0;
                        $percentage = $total > 0 ? round(($confirmed / $total) * 100) : 0;
                        $continentCode = isset($continentCodes[$key]) ? $continentCodes[$key] : '';
                    ?>
                    <div class="col-md-6 col-lg-3 mb-3 continent-card" onclick="viewContinentQsos('<?php echo $continentCode; ?>', '<?php echo htmlspecialchars($label); ?>')">
                        <div class="progress-label"><?php echo $label; ?></div>
                        <div style="font-size: 16px; margin-bottom: 5px;">
                            <strong><?php echo $confirmed; ?>/<?php echo $total; ?></strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo $percentage; ?>%" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Filters Section -->
    <div class="card mb-3">
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

                <form class="form" action="<?php echo site_url('awards/dxcc'); ?>" method="post" enctype="multipart/form-data" id="dxccForm">

                    <div class="mb-3 row">
                        <div class="col-md-2 control-label" for="checkboxes">Deleted DXCC</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="includedeleted" id="includedeleted" value="1" <?php if ($this->input->post('includedeleted')) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="includedeleted">Include deleted</label>
                            </div>
                        </div>
                    </div>

                    <!-- Multiple Checkboxes (inline) -->
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
                        <div class="col-md-2">Continents</div>
                        <div class="col-md-10">
                            <div class="form-check-inline">
                                <input class="form-check-input" type="checkbox" name="Antarctica" id="Antarctica" value="1" <?php if ($this->input->post('Antarctica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="Antarctica">Antarctica</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input"  type="checkbox" name="Africa" id="Africa" value="1" <?php if ($this->input->post('Africa') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="Africa">Africa</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input"  type="checkbox" name="Asia" id="Asia" value="1" <?php if ($this->input->post('Asia') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="Asia">Asia</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input"  type="checkbox" name="Europe" id="Europe" value="1" <?php if ($this->input->post('Europe') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="Europe">Europe</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input"  type="checkbox" name="NorthAmerica" id="NorthAmerica" value="1" <?php if ($this->input->post('NorthAmerica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="NorthAmerica">North America</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input"  type="checkbox" name="SouthAmerica" id="SouthAmerica" value="1" <?php if ($this->input->post('SouthAmerica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="SouthAmerica">South America</label>
                            </div>
                            <div class="form-check-inline">
                                <input class="form-check-input"  type="checkbox" name="Oceania" id="Oceania" value="1" <?php if ($this->input->post('Oceania') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="Oceania">Oceania</label>
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
                            <?php if ($dxcc_array) {
                                ?><button type="button" onclick="load_dxcc_map();" class="btn btn-info btn-sm"><i class="fas fa-globe-americas"></i> Show DXCC Map</button>
                                <button type="button" onclick="exportTableToCSV('dxcc_table.csv');" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Export CSV</button>
                            <?php }?>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-bs-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true">Table</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="map-tab" onclick="load_dxcc_map();" data-bs-toggle="tab" href="#dxccmaptab" role="tab" aria-controls="home" aria-selected="false">Map</a>
        </li>
    </ul>
    <br />

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="dxccmaptab" role="tabpanel" aria-labelledby="home-tab">
    <br />

    <div id="dxccmap" class="map-leaflet" ></div>

    </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">

    <!-- Table Search Box -->
    <?php if ($dxcc_array): ?>
    <div class="table-search">
        <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search DXCC by name or prefix...">
    </div>
    <?php endif; ?>

    <?php
    $i = 1;
    if ($dxcc_array) {
        echo '
                <table style="width:100%" class="table-sm table tabledxcc table-bordered table-hover table-striped table-condensed text-center" id="dxcc_table">
                    <thead>
                    <tr>
                        <td class="sortable" data-column="0">#</td>
                        <td class="sortable" data-column="1">DXCC Name</td>
                        <td class="sortable" data-column="2">Prefix</td>';
        foreach($bands as $band) {
            echo '<td class="sortable" data-column="' . (3 + array_search($band, $bands)) . '">' . $band . '</td>';
        }
        echo '</tr>
                    </thead>
                    <tbody>';
        foreach ($dxcc_array as $dxcc => $value) {      // Fills the table with the data
            echo '<tr>
                        <td>'. $i++ .'</td>';
            foreach ($value as $name => $key) {
                if (isset($value['Deleted']) && $value['Deleted'] == 1 && $name == "name") {
                   echo '<td><span class="dxcc-link" onclick="viewDxccQsos(\'' . htmlspecialchars($dxcc) . '\');">' . $key . '</span> <span class="badge text-bg-danger">'.lang('gen_hamradio_deleted_dxcc').'</span></td>';
                } else if ($name == "Deleted") {
                   continue;
                } else if ($name == "name") {
                   echo '<td><span class="dxcc-link" onclick="viewDxccQsos(\'' . htmlspecialchars($dxcc) . '\');">' . $key . '</span></td>';
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

        foreach ($dxcc_summary['worked'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo '</tr><tr>
        <td>Total confirmed</td>';
        foreach ($dxcc_summary['confirmed'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
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

<!-- QSO Modal -->
<div class="modal fade" id="dxccQsoModal" tabindex="-1" role="dialog" aria-labelledby="dxccQsoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="dxccQsoModalLabel"><span id="dxccModalTitle">QSOs</span> - <span id="dxccEntityName"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="qsoLoading" style="text-align: center; display: none;">
          <div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
        <table id="qsoTable" class="table table-sm table-striped" style="display: none;">
          <thead id="qsoTableHeader">
            <tr>
              <th>Date/Time</th>
              <th>Call</th>
              <th>Band</th>
              <th>Mode</th>
              <th>RST Sent</th>
              <th>RST Rcvd</th>
              <th>QSL</th>
              <th>LoTW</th>
            </tr>
          </thead>
          <tbody id="qsoTableBody">
          </tbody>
        </table>
        <div id="noQsos" style="text-align: center; display: none;" class="alert alert-info">
          No QSOs found for this entity.
        </div>
      </div>
    </div>
  </div>
</div>

<br>

<script>
let currentSort = { column: null, direction: 'asc' };
let currentModalType = 'qso'; // 'qso' or 'continent'

// Table sorting functionality
document.querySelectorAll('.sortable').forEach(header => {
    header.addEventListener('click', function() {
        const column = parseInt(this.dataset.column);
        const table = document.getElementById('dxcc_table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        // Toggle sort direction
        if (currentSort.column === column) {
            currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort.column = column;
            currentSort.direction = 'asc';
        }

        // Update header indicators
        document.querySelectorAll('.sortable').forEach(h => h.classList.remove('asc', 'desc'));
        this.classList.add(currentSort.direction);

        // Sort rows
        rows.sort((a, b) => {
            const aValue = a.querySelectorAll('td')[column].textContent.trim();
            const bValue = b.querySelectorAll('td')[column].textContent.trim();

            let comparison = 0;
            if (!isNaN(aValue) && !isNaN(bValue)) {
                comparison = parseInt(aValue) - parseInt(bValue);
            } else {
                comparison = aValue.localeCompare(bValue);
            }

            return currentSort.direction === 'asc' ? comparison : -comparison;
        });

        // Reorder rows
        rows.forEach(row => tbody.appendChild(row));
    });
});

// Table search functionality
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('dxcc_table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const name = row.querySelectorAll('td')[1].textContent.toLowerCase();
            const prefix = row.querySelectorAll('td')[2].textContent.toLowerCase();
            
            if (name.includes(searchTerm) || prefix.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

// Preset filter functions
function applyPreset(preset) {
    const form = document.getElementById('dxccForm');
    
    // Reset all checkboxes first
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // All QSL types
    const allQslTypes = ['qsl', 'lotw', 'eqsl'];
    
    // All continents
    const allContinents = ['Antarctica', 'Africa', 'Asia', 'Europe', 'NorthAmerica', 'SouthAmerica', 'Oceania'];
    
    switch(preset) {
        case 'confirmed':
            document.getElementById('confirmed').checked = true;
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            allContinents.forEach(cont => {
                const el = document.getElementById(cont);
                if (el) el.checked = true;
            });
            break;
        case 'worked':
            document.getElementById('worked').checked = true;
            document.getElementById('confirmed').checked = true;
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            allContinents.forEach(cont => {
                const el = document.getElementById(cont);
                if (el) el.checked = true;
            });
            break;
        case 'unworked':
            document.getElementById('notworked').checked = true;
            document.getElementById('qsl').checked = true;
            document.getElementById('lotw').checked = true;
            allContinents.forEach(cont => {
                const el = document.getElementById(cont);
                if (el) el.checked = true;
            });
            break;
        case 'lotwonly':
            document.getElementById('confirmed').checked = true;
            document.getElementById('lotw').checked = true;
            // LoTW only means QSL and eQSL are NOT checked (already unchecked from reset)
            allContinents.forEach(cont => {
                const el = document.getElementById(cont);
                if (el) el.checked = true;
            });
            break;
    }
}

function resetFilters() {
    document.getElementById('dxccForm').reset();
}

// View QSOs for specific DXCC filtered by status
function viewDxccQsosByStatus(dxcc, status) {
    const modal = new bootstrap.Modal(document.getElementById('dxccQsoModal'));
    currentModalType = 'qso';
    
    // Set headers for QSO table
    document.getElementById('qsoTableHeader').innerHTML = `
        <tr>
            <th>Date/Time</th>
            <th>Call</th>
            <th>Band</th>
            <th>Mode</th>
            <th>RST Sent</th>
            <th>RST Rcvd</th>
            <th>QSL</th>
            <th>LoTW</th>
        </tr>
    `;
    document.getElementById('dxccModalTitle').textContent = status === 'C' ? 'Confirmed QSOs' : 'Worked QSOs';
    
    // Show loading state
    document.getElementById('qsoLoading').style.display = 'block';
    document.getElementById('qsoTable').style.display = 'none';
    document.getElementById('noQsos').style.display = 'none';
    const statusLabel = status === 'C' ? 'Confirmed' : (status === 'W' ? 'Worked Only' : '');
    document.getElementById('dxccEntityName').textContent = 'Loading... (' + statusLabel + ')';
    
    modal.show();
    
    // Fetch QSOs via AJAX
    $.ajax({
        url: base_url + 'index.php/awards/get_dxcc_qsos_by_status',
        type: 'post',
        data: {
            dxcc_id: dxcc,
            status: status,
            limit: 100
        },
        dataType: 'json',
        success: function(data) {
            document.getElementById('qsoLoading').style.display = 'none';
            
            if (data.error) {
                document.getElementById('noQsos').style.display = 'block';
                document.getElementById('noQsos').innerHTML = '<div class="alert alert-danger">Error: ' + data.error + '</div>';
                document.getElementById('dxccEntityName').textContent = statusLabel + ' - Error';
                return;
            }
            
            // Get DXCC name from dxcc_id (we'll use a placeholder since we're filtering by status)
            document.getElementById('dxccEntityName').textContent = (status === 'C' ? 'Confirmed' : 'Worked Only') + ' QSOs';
            
            if (data.count > 0) {
                document.getElementById('qsoTable').style.display = 'table';
                const tbody = document.getElementById('qsoTableBody');
                tbody.innerHTML = '';
                
                data.qsos.forEach(qso => {
                    const row = document.createElement('tr');
                    const datetime = new Date(qso.col_time_on).toLocaleString();
                    row.innerHTML = `
                        <td>${datetime}</td>
                        <td>${qso.col_call}</td>
                        <td>${qso.col_band}</td>
                        <td>${qso.col_mode}</td>
                        <td>${qso.col_rst_sent || '-'}</td>
                        <td>${qso.col_rst_rcvd || '-'}</td>
                        <td>${qso.col_qsl_rcvd ? '✓' : '-'}</td>
                        <td>${qso.COL_LOTW_QSL_RCVD === 'Y' ? '✓' : '-'}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                document.getElementById('noQsos').style.display = 'block';
                document.getElementById('noQsos').innerHTML = '<div class="alert alert-info">No ' + (status === 'C' ? 'confirmed' : 'worked only') + ' QSOs found.</div>';
                document.getElementById('qsoTable').style.display = 'none';
            }
        },
        error: function(xhr, status, error) {
            document.getElementById('qsoLoading').style.display = 'none';
            console.error('AJAX Error:', error, xhr.responseText);
            document.getElementById('noQsos').style.display = 'block';
            document.getElementById('noQsos').innerHTML = '<div class="alert alert-danger">Error loading QSOs: ' + error + '</div>';
        }
    });
}

// View QSOs for specific continent
function viewContinentQsos(continentCode, continentName) {
    const modal = new bootstrap.Modal(document.getElementById('dxccQsoModal'));
    currentModalType = 'continent';
    
    // Set headers for continent table (countries)
    document.getElementById('qsoTableHeader').innerHTML = `
        <tr>
            <th>Country</th>
        </tr>
    `;
    document.getElementById('dxccModalTitle').textContent = 'Countries';
    
    // Show loading state
    document.getElementById('qsoLoading').style.display = 'block';
    document.getElementById('qsoTable').style.display = 'none';
    document.getElementById('noQsos').style.display = 'none';
    document.getElementById('dxccEntityName').textContent = 'Loading... (' + continentName + ')';
    
    modal.show();
    
    // Fetch DXCC entities for this continent via AJAX
    $.ajax({
        url: base_url + 'index.php/awards/get_continent_qsos',
        type: 'post',
        data: {
            continent_code: continentCode,
            limit: 100
        },
        dataType: 'json',
        success: function(data) {
            document.getElementById('qsoLoading').style.display = 'none';
            
            if (data.error) {
                document.getElementById('noQsos').style.display = 'block';
                document.getElementById('noQsos').innerHTML = '<div class="alert alert-danger">Error: ' + data.error + '</div>';
                document.getElementById('dxccEntityName').textContent = continentName + ' - Error';
                return;
            }
            
            document.getElementById('dxccEntityName').textContent = continentName + ' Countries';
            
            if (data.count > 0) {
                document.getElementById('qsoTable').style.display = 'table';
                const tbody = document.getElementById('qsoTableBody');
                tbody.innerHTML = '';
                
                data.entities.forEach(entity => {
                    const row = document.createElement('tr');
                    let statusBadge = '';
                    let statusColor = '';
                    
                    if (entity.status === 'confirmed') {
                        statusBadge = '<span class="badge bg-success">✓ Confirmed</span>';
                        statusColor = 'bg-success';
                    } else if (entity.status === 'worked') {
                        statusBadge = '<span class="badge bg-warning">W Worked</span>';
                        statusColor = 'bg-danger';
                    } else {
                        statusBadge = '<span class="badge bg-secondary">- Not Worked</span>';
                        statusColor = '';
                    }
                    
                    row.innerHTML = `
                        <td>
                            <strong><a href="javascript:viewDxccQsos(${entity.adif})" style="color: #007bff; cursor: pointer;">${entity.name}</a></strong>
                            <small style="margin-left: 10px;">${entity.prefix}</small>
                            <span style="float: right;">${statusBadge}</span>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                document.getElementById('noQsos').style.display = 'block';
                document.getElementById('noQsos').innerHTML = '<div class="alert alert-info">No countries found for ' + continentName + '.</div>';
                document.getElementById('qsoTable').style.display = 'none';
            }
        },
        error: function(xhr, status, error) {
            document.getElementById('qsoLoading').style.display = 'none';
            console.error('AJAX Error:', error, xhr.responseText);
            document.getElementById('noQsos').style.display = 'block';
            document.getElementById('noQsos').innerHTML = '<div class="alert alert-danger">Error loading countries: ' + error + '</div>';
        }
    });
}

// View QSOs for specific DXCC
function viewDxccQsos(dxcc) {
    const modal = new bootstrap.Modal(document.getElementById('dxccQsoModal'));
    currentModalType = 'qso';
    
    // Set headers for QSO table
    document.getElementById('qsoTableHeader').innerHTML = `
        <tr>
            <th>Date/Time</th>
            <th>Call</th>
            <th>Band</th>
            <th>Mode</th>
            <th>RST Sent</th>
            <th>RST Rcvd</th>
            <th>QSL</th>
            <th>LoTW</th>
        </tr>
    `;
    document.getElementById('dxccModalTitle').textContent = 'QSOs';
    
    // Show loading state
    document.getElementById('qsoLoading').style.display = 'block';
    document.getElementById('qsoTable').style.display = 'none';
    document.getElementById('noQsos').style.display = 'none';
    document.getElementById('dxccEntityName').textContent = 'Loading...';
    
    modal.show();
    
    // Fetch QSOs via AJAX
    $.ajax({
        url: base_url + 'index.php/awards/get_dxcc_qsos',
        type: 'post',
        data: {
            dxcc_id: dxcc,
            limit: 100
        },
        dataType: 'json',
        success: function(data) {
            document.getElementById('qsoLoading').style.display = 'none';
            
            // Get DXCC name from the table
            const dxccName = document.querySelector('[onclick="viewDxccQsos(\'' + dxcc + '\')"]')?.textContent || 'Entity ' + dxcc;
            document.getElementById('dxccEntityName').textContent = dxccName;
            
            if (data.count > 0) {
                document.getElementById('qsoTable').style.display = 'table';
                const tbody = document.getElementById('qsoTableBody');
                tbody.innerHTML = '';
                
                data.qsos.forEach(qso => {
                    const row = document.createElement('tr');
                    const datetime = new Date(qso.col_time_on).toLocaleString();
                    row.innerHTML = `
                        <td>${datetime}</td>
                        <td>${qso.col_call}</td>
                        <td>${qso.col_band}</td>
                        <td>${qso.col_mode}</td>
                        <td>${qso.col_rst_sent || '-'}</td>
                        <td>${qso.col_rst_rcvd || '-'}</td>
                        <td>${qso.col_qsl_rcvd ? '✓' : '-'}</td>
                        <td>${qso.COL_LOTW_QSL_RCVD === 'Y' ? '✓' : '-'}</td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                document.getElementById('noQsos').style.display = 'block';
                document.getElementById('qsoTable').style.display = 'none';
            }
        },
        error: function(xhr, status, error) {
            document.getElementById('qsoLoading').style.display = 'none';
            console.error('AJAX Error:', error, xhr.responseText);
            document.getElementById('noQsos').style.display = 'block';
            document.getElementById('noQsos').innerHTML = '<div class="alert alert-danger">Error loading QSOs: ' + error + '</div>';
        }
    });
}

// CSV Export functionality
function exportTableToCSV(filename) {
    const table = document.getElementById('dxcc_table');
    let csv = [];
    
    // Add header row
    const headers = Array.from(table.querySelectorAll('thead tr td')).map(h => h.textContent.trim());
    csv.push(headers.join(','));
    
    // Add data rows
    const rows = table.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.style.display !== 'none') {  // Only export visible rows
            const cells = Array.from(row.querySelectorAll('td')).map(cell => {
                let text = cell.textContent.trim();
                // Escape quotes and wrap in quotes if contains comma
                text = text.replace(/"/g, '""');
                return '"' + text + '"';
            });
            csv.push(cells.join(','));
        }
    });
    
    // Create blob and download
    const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    a.click();
    window.URL.revokeObjectURL(url);
}
</script>

