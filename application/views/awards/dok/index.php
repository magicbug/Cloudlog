<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_dok_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_dok_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_dok_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_dok_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary mr-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->
            <form class="form" action="<?php echo site_url('awards/dok'); ?>" method="post" enctype="multipart/form-data">
            <fieldset>

            <div class="form-group row">
                <label class="col-md-2 control-label" for="band">DOK / SDOK</label>
                <div class="col-md-2">
                    <select id="doks" name="doks" class="form-control custom-select-sm">
                        <option value="both" <?php if ($this->input->post('doks') == "both" || $this->input->method() !== 'post') echo ' selected'; ?> >DOK + SDOK</option>
                        <?php echo '<option value="dok"';
                            if ($this->input->post('doks') == 'dok') echo ' selected';
                            echo '>DOK</option>'."\n";
                        ?>
                        <?php echo '<option value="sdok"';
                            if ($this->input->post('doks') == 'sdok') echo ' selected';
                            echo '>SDOK</option>'."\n";
                        ?>
                    </select>
                </div>
            </div>

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

<?php
   $doks = array();
   if ($dok_array) {
      foreach ($dok_array as $dok => $value) {
         if (preg_match('/^[A-Z][0-9]{2}$/', $dok)) {
            $doks[] = $dok;
         }
      }
   }
?>

            <div class="form-group row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button2id" type="reset" name="button2id" class="btn btn-sm btn-warning">Reset</button>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-sm btn-primary">Show</button>
                    <button id="button3id" type="button" name="button3id" class="btn btn-sm btn-info" onclick=" window.open('https://dd3ah.de/dokmap/?lat=51.3035&lng=11.1475&zoom=7<?php print implode(',', $doks); ?>','_blank')"><i class="fas fa-globe-americas"></i> Map</button>
                </div>
            </div>
        </fieldset>
    </form>
    <br />

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="dokmaptab" role="tabpanel" aria-labelledby="home-tab">
    <br />

    </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">

<?php
    if ($dok_array) {
    echo '
    <table style="width:100%" id="doktable" class="table table-sm table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <th>DOK</th>';
        foreach($bands as $band) {
            echo '<th>' . $band . '</th>';
            }
            echo '</tr>
        </thead>
        <tbody>';
        foreach ($dok_array as $dok => $value) {      // Fills the table with the data
        echo '<tr>
            <td>'. $dok .'</td>';
            foreach ($value  as $key) {
            echo '<td style="text-align: center">' . $key . '</td>';
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
        echo '<td>Total</td></tr>
        </thead>
        <tbody>

        <tr><td>Total worked</td>';

        foreach ($dok_summary['worked'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo '</tr><tr>
        <td>Total confirmed</td>';
        foreach ($dok_summary['confirmed'] as $dxcc) {      // Fills the table with the data
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
