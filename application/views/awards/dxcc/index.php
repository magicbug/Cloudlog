
<style>
    #dxccmap {
	height: calc(100vh - 500px) !important;
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
            <button type="button" class="btn btn-sm btn-primary mr-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

    <form class="form" action="<?php echo site_url('awards/dxcc'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>

            <div class="form-group row">
                <div class="col-md-2 control-label" for="checkboxes">Deleted DXCC</div>
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="includedeleted" id="includedeleted" value="1" <?php if ($this->input->post('includedeleted')) echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="includedeleted">Include deleted</label>
                    </div>
                </div>
            </div>

            <!-- Multiple Checkboxes (inline) -->
            <div class="form-group row">
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

            <div class="form-group row">
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

            <div class="form-group row">
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

            <div class="form-group row">
                <label class="col-md-2 control-label" for="band">Band</label>
                <div class="col-md-2">
                    <select id="band2" name="band" class="form-control custom-select-sm">
                        <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >Every band</option>
                        <?php foreach($worked_bands as $band) {
                            echo '<option value="' . $band . '"';
                            if ($this->input->post('band') == $band) echo ' selected';
                            echo '>' . $band . '</option>'."\n";
                        } ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-2 control-label" for="mode">Mode</label>
                <div class="col-md-2">
                <select id="mode" name="mode" class="form-control custom-select-sm">
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

            <div class="form-group row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button2id" type="reset" name="button2id" class="btn btn-sm btn-warning">Reset</button>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                    <?php if ($dxcc_array) {
                        ?><button type="button" onclick="load_dxcc_map();" class="btn btn-info btn-sm"><i class="fas fa-globe-americas"></i> Show DXCC Map</button>
                    <?php }?>
                </div>
            </div>

        </fieldset>
    </form>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true">Table</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="map-tab" onclick="load_dxcc_map();" data-toggle="tab" href="#dxccmaptab" role="tab" aria-controls="home" aria-selected="false">Map</a>
        </li>
    </ul>
    <br />

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="dxccmaptab" role="tabpanel" aria-labelledby="home-tab">
    <br />

    <div id="dxccmap"></div>

    </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">

    <?php
    $i = 1;
    if ($dxcc_array) {
        echo '
                <table style="width:100%" class="table-sm table tabledxcc table-bordered table-hover table-striped table-condensed text-center">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>DXCC Name</td>
                        <td>Prefix</td>';
        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
        }
        echo '</tr>
                    </thead>
                    <tbody>';
        foreach ($dxcc_array as $dxcc => $value) {      // Fills the table with the data
            echo '<tr>
                        <td>'. $i++ .'</td>';
            foreach ($value as $name => $key) {
                if (isset($value['Deleted']) && $value['Deleted'] == 1 && $name == "name") {
                   echo '<td style="text-align: center">' . $key . ' <span class="badge badge-danger">'.lang('gen_hamradio_deleted_dxcc').'</span></td>';
                } else if ($name == "Deleted") {
                   continue;
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
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>
                </div>
        </div>
</div>
