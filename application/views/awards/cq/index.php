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
            <form class="form" action="<?php echo site_url('awards/cq'); ?>" method="post" enctype="multipart/form-data">
            <fieldset>

            <!-- Multiple Checkboxes (inline) -->
            <div class="mb-3 row">
                <div class="col-md-2" for="checkboxes"><?php echo lang('general_word_worked') . ' / ' . lang('general_word_confirmed')?></div>
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
                <div class="col-md-2"><?php echo lang('gen_hamradio_qsltype'); ?></div>
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
                <label class="col-md-2 control-label" for="band"><?php echo lang('gen_hamradio_band'); ?></label>
                <div class="col-md-2">
                    <select id="band2" name="band" class="form-select form-select-sm">
                        <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> ><?php echo lang('general_word_all'); ?></option>
                        <?php foreach($worked_bands as $band) {
                            echo '<option value="' . $band . '"';
                            if ($this->input->post('band') == $band) echo ' selected';
                            echo '>' . $band . '</option>'."\n";
                        } ?>
                    </select>
                </div>
            </div>

			<div class="mb-3 row">
				<label class="col-md-2 control-label" for="mode"><?php echo lang('gen_hamradio_mode'); ?></label>
				<div class="col-md-2">
					<select id="mode" name="mode" class="form-select form-select-sm">
						<option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'mode') echo ' selected'; ?>><?php echo lang('general_word_all'); ?></option>
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
                    <button id="button2id" type="reset" name="button2id" class="btn btn-sm btn-warning"><?php echo lang('filter_reset'); ?></button>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary"><?php echo lang('filter_options_show'); ?></button>
                    <?php if ($cq_array) {
                        ?><button type="button" onclick="load_cq_map();" class="btn btn-info btn-sm"><i class="fas fa-globe-americas"></i> <?php echo lang('awards_show_cq_map'); ?></button>
                    <?php }?>
                </div>
            </div>
        </fieldset>
    </form>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-bs-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true"><?php echo lang('general_word_table'); ?></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" onclick="load_cq_map();" id="map-tab" data-bs-toggle="tab" href="#cqmaptab" role="tab" aria-controls="home" aria-selected="false"><?php echo lang('filter_map'); ?></a>
        </li>
    </ul>
    <br />

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="cqmaptab" role="tabpanel" aria-labelledby="home-tab">
    <br />

    <div id="cqmap" class="map-leaflet" ></div>

    </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab" style="margin-bottom: 30px;">
        
<?php
    $i = 1;
    if ($cq_array) {
    echo "
    <table style='width:100%' class='table tablecq table-sm table-bordered table-hover table-striped table-condensed text-center'>
        <thead>
        <tr>
            <td>#</td>
            <td>" . lang('gen_hamradio_cq_zone') . "</td>";
        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
            }
            echo '</tr>
        </thead>
        <tbody>';
        foreach ($cq_array as $cq => $value) {      // Fills the table with the data
        echo '<tr>
            <td>' . $i++ . '</td>
            <td>'. $cq .'</td>';
            foreach ($value  as $key) {
            echo '<td style="text-align: center">' . $key . '</td>';
            }
            echo '</tr>';
        }
        echo "</table>
        <h2>" . lang('awards_summary') . "</h2>

        <table class='table-sm tablesummary table table-bordered table-hover table-striped table-condensed text-center'>
        <thead>
        <tr><td></td>";

        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
        }
        echo "<td>" . lang('awards_total') . "</td></tr>
        </thead>
        <tbody>

        <tr><td>" . lang('awards_total_worked') . "</td>";

        foreach ($cq_summary['worked'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo "</tr><tr>
        <td>" . lang('awards_total_confirmed') . "</td>";
        foreach ($cq_summary['confirmed'] as $dxcc) {      // Fills the table with the data
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
