
<style>
    #iotamap {
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

.filter-section {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 20px;
  border: 1px solid #dee2e6;
}

.filter-section .form-label {
  font-weight: 600;
  color: #495057;
}

.table-iota {
  font-size: 0.9rem;
}

.table-iota thead th {
  background-color: #e9ecef;
  font-weight: 600;
  position: sticky;
  top: 0;
  z-index: 10;
}
</style>


<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <!-- Award Info Box -->
            <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
                <h2><?php echo $page_title; ?></h2>
                <button type="button" class="btn btn-primary" id="displayAwardInfo">
                    <i class="fas fa-info-circle"></i> <?php echo lang('awards_info_button'); ?>
                </button>
            </div>
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_iota_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_iota_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_iota_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_iota_description_ln4'); ?>";
            </script>
            <!-- End of Award Info Box -->

    <form class="form filter-section" action="<?php echo site_url('awards/iota'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Deleted IOTA</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="includedeleted" id="includedeleted" value="1" <?php if ($this->input->post('includedeleted')) echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="includedeleted">Include deleted</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Worked / Confirmed</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="worked" id="worked" value="1" <?php if ($this->input->post('worked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="worked">Show worked</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="confirmed" id="confirmed" value="1" <?php if ($this->input->post('confirmed') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="confirmed">Show confirmed</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="notworked" id="notworked" value="1" <?php if ($this->input->post('notworked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                <label class="form-check-label" for="notworked">Show not worked</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Continents</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Antarctica" id="Antarctica" value="1" <?php if ($this->input->post('Antarctica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="Antarctica">Antarctica</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Africa" id="Africa" value="1" <?php if ($this->input->post('Africa') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="Africa">Africa</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Asia" id="Asia" value="1" <?php if ($this->input->post('Asia') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="Asia">Asia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Europe" id="Europe" value="1" <?php if ($this->input->post('Europe') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="Europe">Europe</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="NorthAmerica" id="NorthAmerica" value="1" <?php if ($this->input->post('NorthAmerica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="NorthAmerica">North America</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="SouthAmerica" id="SouthAmerica" value="1" <?php if ($this->input->post('SouthAmerica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="SouthAmerica">South America</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="Oceania" id="Oceania" value="1" <?php if ($this->input->post('Oceania') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                                    <label class="form-check-label" for="Oceania">Oceania</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold" for="band">Band</label>
                        <select id="band2" name="band" class="form-select">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >Every band</option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '"';
                                if ($this->input->post('band') == $band) echo ' selected';
                                echo '>' . $band . '</option>'."\n";
                            } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="mode">Mode</label>
                        <select id="mode" name="mode" class="form-select">
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

                    <div class="mb-3">
                        <label class="form-label fw-bold">Actions</label>
                        <div class="d-flex gap-2">
                            <button id="button1id" type="submit" name="button1id" class="btn btn-primary">
                                <i class="fas fa-search"></i> Show Results
                            </button>
                            <button id="button2id" type="reset" name="button2id" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </button>
                            <?php if ($iota_array) { ?>
                                <button type="button" onclick="load_iota_map();" class="btn btn-info">
                                    <i class="fas fa-globe-americas"></i> Show Map
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </form>

    <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button" role="tab" aria-controls="table" aria-selected="true">
                <i class="fas fa-table"></i> Table View
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="map-tab" onclick="load_iota_map();" data-bs-toggle="tab" data-bs-target="#iotamaptab" type="button" role="tab" aria-controls="iotamaptab" aria-selected="false">
                <i class="fas fa-map-marked-alt"></i> Map View
            </button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="iotamaptab" role="tabpanel" aria-labelledby="map-tab">
            <div id="iotamap" class="map-leaflet"></div>
        </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">
    
    <!-- Table Search Box -->
    <?php if ($iota_array): ?>
    <div class="table-search mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Search IOTA by island group name or IOTA number...">
    </div>
    <?php endif; ?>

    <?php
    if ($iota_array) {
        echo '
        <div class="table-responsive">
            <table class="table table-iota table-bordered table-hover table-striped text-center" id="iota_table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">IOTA</th>
                        <th scope="col">Prefix</th>
                        <th scope="col">Name</th>';
        if ($this->input->post('includedeleted'))
            echo '      <th scope="col">Deleted</th>';

        foreach($bands as $band) {
            echo '<th scope="col">' . $band . '</th>';
        }
        echo '</tr>
                </thead>
                <tbody>';
        foreach ($iota_array as $iota => $value) {
            echo '<tr>
                        <td class="fw-bold">'. $iota .'</td>';
            foreach ($value  as $key) {
                echo '<td>' . $key . '</td>';
            }
            echo '</tr>';
        }

        echo '</tbody>
            </table>
        </div>

        <div class="mt-4">
            <h3 class="mb-3">
                <i class="fas fa-chart-bar"></i> Summary
            </h3>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Category</th>';

        foreach($bands as $band) {
            echo '<th scope="col">' . $band . '</th>';
        }
        echo '<th scope="col" class="table-primary fw-bold">Total</th></tr>
                    </thead>
                    <tbody>
                        <tr class="table-success">
                            <th scope="row" class="text-start">
                                <i class="fas fa-check-circle text-success"></i> Total worked
                            </th>';

        foreach ($iota_summary['worked'] as $dxcc) {
            echo '<td>' . $dxcc . '</td>';
        }

        echo '</tr>
                        <tr class="table-info">
                            <th scope="row" class="text-start">
                                <i class="fas fa-certificate text-info"></i> Total confirmed
                            </th>';
        foreach ($iota_summary['confirmed'] as $dxcc) {
            echo '<td>' . $dxcc . '</td>';
        }

        echo '</tr>
                    </tbody>
                </table>
            </div>
        </div>';

    }
    else {
        echo '<div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <div>No IOTA data found! Please adjust your filters and try again.</div>
              </div>';
    }
    ?>
        </div>
    </div>
        </div>
    </div>
</div>

<script>
// Table search functionality
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('iota_table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const iotaNumber = row.querySelectorAll('td')[0].textContent.toLowerCase();
            const prefix = row.querySelectorAll('td')[1].textContent.toLowerCase();
            const name = row.querySelectorAll('td')[2].textContent.toLowerCase();
            
            if (iotaNumber.includes(searchTerm) || prefix.includes(searchTerm) || name.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}
</script>
