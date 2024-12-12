<div class="container custom-map-QSOs">
    <br>
    <h2><?php echo $logbook_name; echo strpos($logbook_name, 'logbook') ? '' : ' logbook'; ?> QSOs (Custom Dates)</h2>

    <?php if ($this->session->flashdata('notice')) { ?>
        <div class="alert alert-info" role="alert">
            <?php echo $this->session->flashdata('notice'); ?>
        </div>
    <?php } ?>

    <form method="post" action="<?php echo site_url('map/custom'); ?>">
        <div class="row">
            <div class="mb-3 col-md-3">
                <label for="from"><?php echo lang('gen_from_date') . ": " ?></label>
                <input name="from" id="from" type="date" class="form-control w-auto" value="<?php echo $date_from; ?>">
            </div>

            <div class="mb-3 col-md-3">
                <label for="to"><?php echo lang('gen_to_date') . ": " ?></label>
                <input name="to" id="to" type="date" class="form-control w-auto" value="<?php echo $date_to; ?>" max="<?php echo $date_to; ?>">
            </div>

            <div class="mb-3 col-md-3 d-flex align-items-end">
                <input class="btn btn-secondary" type="button" value="<?php echo lang('set_log_to_full_dates'); ?>" onclick="get_oldest_qso_date();">
            </div>
        </div>

        <div class="row">
            <div class="mb-3 col-md-3">
                <label for="band">Band</label>
                <select id="band2" name="band" class="form-select">
                    <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>>Every band</option>
                    <?php foreach ($worked_bands as $band) {
                        echo '<option value="' . $band . '"';
                        if ($this->input->post('band') == $band) echo ' selected';
                        echo '>' . $band . '</option>' . "\n";
                    } ?>
                </select>
            </div>

            <div class="mb-3 col-md-3">
                <label for="mode">Mode</label>
                <select id="mode" name="mode" class="form-select">
                    <option value="All" <?php if ($this->input->post('mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>><?php echo lang('general_word_all') ?></option>
                    <?php
                    foreach ($modes as $mode) {
                        if ($mode->submode ?? '' == '') {
                            echo '<option value="' . $mode . '">' . strtoupper($mode) . '</option>'."\n";
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
                // Sort translated propagation modes alphabetically
                $prop_modes = ['AS' => lang('gen_hamradio_propagation_AS'),
                               'AUR' => lang('gen_hamradio_propagation_AUR'),
                               'AUE' => lang('gen_hamradio_propagation_AUE'),
                               'BS' => lang('gen_hamradio_propagation_BS'),
                               'ECH' => lang('gen_hamradio_propagation_ECH'),
                               'EME' => lang('gen_hamradio_propagation_EME'),
                               'ES' => lang('gen_hamradio_propagation_ES'),
                               'FAI' => lang('gen_hamradio_propagation_FAI'),
                               'F2' => lang('gen_hamradio_propagation_F2'),
                               'INTERNET' => lang('gen_hamradio_propagation_INTERNET'),
                               'ION' => lang('gen_hamradio_propagation_ION'),
                               'IRL' => lang('gen_hamradio_propagation_IRL'),
                               'MS' => lang('gen_hamradio_propagation_MS'),
                               'RPT' => lang('gen_hamradio_propagation_RPT'),
                               'RS' => lang('gen_hamradio_propagation_RS'),
                               'SAT' => lang('gen_hamradio_propagation_SAT'),
                               'TEP' => lang('gen_hamradio_propagation_TEP'),
                               'TR' => lang('gen_hamradio_propagation_TR')];
                asort($prop_modes);
            ?>
            <div class="mb-3 col-md-3">
                <label for="selectPropagation">Propagation Mode</label>
                <select class="form-select" id="selectPropagation" name="prop_mode">
                    <option value="All" <?php if ($this->input->post('prop_mode') == "All" || $this->input->method() !== 'post') echo ' selected'; ?>><?php echo lang('general_word_all') ?></option>
                    <option value="" <?php if ($this->input->post('prop_mode') == "" && $this->input->method() == 'post') echo ' selected'; ?>><?php echo lang('general_word_undefined') ?></option>
                    <?php
                        foreach($prop_modes as $key => $label) {
                            echo '<option value="' . $key . '">';
                            if ($this->input->post('prop_mode') == $key) echo ' selected';
                            echo $label . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="mb-2 col-md-2">
                <input class="btn btn-primary btn_submit_map_custom" type="button" value="Load Map">
            </div>
            <div class="mb-4 col-md-4">
                <div class="alert alert-danger warningOnSubmit" style="display:none;"><span><i class="fas fa-times-circle"></i></span> <span class="warningOnSubmit_txt ms-1">Error</span></div>
            </div>
        </div>
    </form>

</div>

<!-- Map -->
<div id="custommap" class="map-leaflet mt-2" style="width: 100%; height: 1000px;"></div>

<div class="alert alert-success" role="alert">Showing QSOs for Custom Dates for Active Logbook <?php echo $logbook_name ?></div>