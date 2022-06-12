<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <form class="form" action="<?php echo site_url('activators'); ?>" method="post" enctype="multipart/form-data">
        <!-- Select Basic -->
                <div class="form-group row">
                    <label class="col-md-1 control-label" for="band">Band</label>
                    <div class="col-md-3">
                        <select id="band" name="band" class="form-control custom-select">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >All</option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '"';
                                if ($this->input->post('band') == $band) echo ' selected';
                                echo '>' . $band . '</option>'."\n";
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="leogeo">
                    <label class="col-md-1 control-label" for="leogeo">LEO/GEO</label>
                    <div class="col-md-3">
                        <select id="leogeo" name="leogeo" class="form-control custom-select">
                            <option value="both" <?php if ($this->input->post('leogeo') == "both" || $this->input->method() !== 'post') echo ' selected'; ?> >Both</option>
                            <option value="leo" <?php if ($this->input->post('leogeo') == "leo") echo ' selected'; ?>>LEO</option>
                            <option value="geo" <?php if ($this->input->post('leogeo') == "geo") echo ' selected'; ?>>GEO</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label" for="mincount">Minimum Count</label>
                    <div class="col-md-3">
                        <select id="mincount" name="mincount" class="form-control custom-select">
                            <?php
                                $i = 1;
                                do {
                                   echo '<option value="'.$i.'"';
                                   if ($this->input->post('mincount') == $i || ($this->input->method() !== 'post' && $i == 2)) echo ' selected';
                                   echo '>'.$i.'</option>'."\n";
                                   $i++;
                                } while ($i <= $maxactivatedgrids);
                            ?>
                        </select>
                    </div>

                </div>

            <div class="form-group row">
                <label class="col-md-1 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button1id" type="submit" name="button1id" class="btn btn-primary">Show</button>
                </div>
            </div>

    </form>

    <?php 
    // Get Date format
    if($this->session->userdata('user_date_format')) {
        // If Logged in and session exists
        $custom_date_format = $this->session->userdata('user_date_format');
    } else {
        // Get Default date format from /config/cloudlog.php
        $custom_date_format = $this->config->item('qso_date_format');
    }
    ?>
    <?php 
    $vucc_grids = array();
    if ($activators_vucc_array) {
       foreach ($activators_vucc_array as $line) {
          $vucc_grids[$line->call] = $line->vucc_grids;
       }
    }
    if( $this->input->post('band') != NULL) {
        if ($activators_array) {

            $result = write_activators($activators_array, $vucc_grids, $custom_date_format, $this->input->post('band'), $this->input->post('leogeo'));
        }
        else {
            echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
        }
    }
    ?>

</div>


<?php

function write_activators($activators_array, $vucc_grids, $custom_date_format, $band, $leogeo) {
    if ($band == '') {
       $band = 'All';
    }
    if ($leogeo == '') {
       $leogeo = 'both';
    }
    $i = 1;
    echo '<table style="width:100%" class="table table-sm activatorstable table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>Callsign</td>
                        <td>Count</td>
                        <td>Gridsquares</td>
                        <td>Show QSOs</td>
                        <td>Show Map</td>
                    </tr>
                </thead>
                <tbody>';

    $activators = array();
    foreach ($activators_array as $line) {
        $call = $line->call;
        $grids = $line->grids;
        $count = $line->count;
        if (array_key_exists($line->call, $vucc_grids)) {
           foreach(explode(',', $vucc_grids[$line->call]) as $vgrid) {
              if(!strpos($grids, $vgrid)) {
                 $grids .= ','.$vgrid;
              }
           }
           $grids = str_replace(' ', '', $grids);
           $grid_array = explode(',', $grids);
           sort($grid_array);
           $count = count($grid_array);
           $grids = implode(', ', $grid_array);
        }
        array_push($activators, array($count, $call, $grids));
    }
    arsort($activators);
    foreach ($activators as $line) {
        echo '<tr>
                <td>' . $i++ . '</td>
                <td>'.$line[1].'</td>
                <td>'.$line[0].'</td>
                <td style="text-align: left; font-family: monospace;">'.$line[2].'</td>
                <td><a href=javascript:displayActivatorsContacts("'.$line[1].'","'.$band.'","'.$leogeo.'")><i class="fas fa-list"></i></a></td>
                <td><a href=javascript:spawnActivatorsMap("'.$line[1].'","'.$line[0].'","'.str_replace(' ', '', $line[2]).'")><i class="fas fa-globe"></i></a></td>
               </tr>';
    }
    echo '</tfoot></table></div>';
}
