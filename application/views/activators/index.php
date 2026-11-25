<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => lang('gridsquares_gridsquare_activators'),
        'subtitle' => 'View activators and their activated gridsquares by band and satellite type.',
        'icon' => 'fas fa-th',
    )); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Filters</h2>
        </div>
        <div class="card-body">
            <form class="form" hx-post="<?php echo site_url('activators/component_activators'); ?>" hx-target="#activatorsResults" hx-swap="innerHTML">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label" for="band"><?php echo lang('gen_hamradio_band'); ?></label>
                        <select id="band" name="band" class="form-select" hx-trigger="change" hx-post="<?php echo site_url('activators/component_activators'); ?>" hx-target="#activatorsResults" hx-swap="innerHTML">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> ><?php echo lang('general_word_all'); ?></option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '"';
                                if ($this->input->post('band') == $band) echo ' selected';
                                echo '>' . $band . '</option>'."\n";
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-3" id="leogeoContainer" style="<?php if ($this->input->post('band') != 'SAT' && $this->input->post('band') != NULL) echo 'display:none;'; ?>">
                        <label class="form-label" for="leogeo">LEO/GEO</label>
                        <select id="leogeo" name="leogeo" class="form-select" hx-trigger="change" hx-post="<?php echo site_url('activators/component_activators'); ?>" hx-target="#activatorsResults" hx-swap="innerHTML">
                            <option value="both" <?php if ($this->input->post('leogeo') == "both" || $this->input->method() !== 'post') echo ' selected'; ?> >Both</option>
                            <option value="leo" <?php if ($this->input->post('leogeo') == "leo") echo ' selected'; ?>>LEO</option>
                            <option value="geo" <?php if ($this->input->post('leogeo') == "geo") echo ' selected'; ?>>GEO</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="mincount"><?php echo lang('gridsquares_minimum_count'); ?></label>
                        <select id="mincount" name="mincount" class="form-select" hx-trigger="change" hx-post="<?php echo site_url('activators/component_activators'); ?>" hx-target="#activatorsResults" hx-swap="innerHTML">
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

                    <div class="col-md-3 d-flex justify-content-end gap-2">
                        <a href="<?php echo site_url('activators'); ?>" class="btn btn-outline-secondary">Reset</a>
                        <button id="button1id" type="submit" class="btn btn-primary"><?php echo lang('filter_options_show'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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

    <!-- Results container for HTMX -->
    <div id="activatorsResults">
        <?php
        // Load initial results if form was submitted
        if( $this->input->post('band') != NULL) {
            $vucc_grids = array();
            if ($activators_vucc_array) {
               foreach ($activators_vucc_array as $line) {
                  $vucc_grids[$line->call] = $line->vucc_grids;
               }
            }
            if ($activators_array) {
                $this->load->view('activators/component_table', array(
                    'activators_array' => $activators_array,
                    'vucc_grids' => $vucc_grids,
                    'custom_date_format' => $custom_date_format,
                    'band' => $this->input->post('band'),
                    'leogeo' => $this->input->post('leogeo')
                ));
            }
            else {
                echo '<div class="alert alert-info" role="alert">No activators found for the selected filters.</div>';
            }
        }
        ?>
    </div>

</div>

<script>
document.getElementById('band').addEventListener('change', function() {
    const leogeoContainer = document.getElementById('leogeoContainer');
    if (this.value === 'SAT') {
        leogeoContainer.style.display = 'block';
    } else {
        leogeoContainer.style.display = 'none';
    }
});
</script>
    </div>

</div>
