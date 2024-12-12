<div class="container">

    <br>

    <h2><?php echo lang('statistics_distances_worked'); ?></h2>
    <script>
        var lang_general_word_qso_data = '<?php echo lang('general_word_qso_data'); ?>';
        var lang_statistics_distances_worked = '<?php echo lang('statistics_distances_worked'); ?>';
        var lang_statistics_distances_part1_contacts_were_plotted_furthest = '<?php echo lang('statistics_distances_part1_contacts_were_plotted_furthest'); ?>';
        var lang_statistics_distances_part2_contacts_were_plotted_furthest = '<?php echo lang('statistics_distances_part2_contacts_were_plotted_furthest'); ?>';
        var lang_statistics_distances_part3_contacts_were_plotted_furthest = '<?php echo lang('statistics_distances_part3_contacts_were_plotted_furthest'); ?>';
        var lang_statistics_distances_part4_contacts_were_plotted_furthest = '<?php echo lang('statistics_distances_part4_contacts_were_plotted_furthest'); ?>';
        var lang_statistics_distances_number_of_qsos = '<?php echo lang('statistics_distances_number_of_qsos'); ?>';
        var lang_gen_hamradio_distance = '<?php echo lang('gen_hamradio_distance'); ?>';
        var lang_statistics_distances_callsigns_worked = '<?php echo lang('statistics_distances_callsigns_worked'); ?>';
        var lang_statistics_distances_qsos_with = '<?php echo lang('lang_statistics_distances_qsos_with'); ?>';
    </script>
    <div id="distances_div">
        <form class="d-flex align-items-center">
            <label class="my-1 me-2" for="distplot_bands"><?php echo lang('gridsquares_band'); ?></label>
            <select class="form-select my-1 me-sm-2 w-auto"  id="distplot_bands">
                <option value="all"><?php echo lang('statistics_distances_bands_all')?></option>
                <?php if (count($sats_available) != 0) { ?>
                    <option value="sat">SAT</option>
                <?php } ?>
                <?php foreach($bands_available as $band) {
                    echo '<option value="' . $band . '"' . '>' . $band . '</option>'."\n";
                } ?>
            </select>
            <?php if (count($sats_available) != 0) { ?>
                <label class="my-1 me-2" for="distplot_sats" id="distplot_sats_lbl" style="display: none"><?php echo lang('general_word_satellite')?></label>
                <select class="form-select my-1 me-sm-2 w-auto" disabled id="distplot_sats" style="display: none">
                    <option value="All"><?php echo lang('statistics_distances_modes_all')?></option>
                    <?php foreach($sats_available as $sat) {
                        echo '<option value="' . $sat . '"' . '>' . $sat . '</option>'."\n";
                    } ?>
                </select>
            <?php } else { ?>
                <input id="distplot_sats" type="hidden" value="All"></input>
            <?php } ?>
			<label class="my-1 me-2" for="distplot_modes"><?php echo lang('gridsquares_mode'); ?></label>
            <select class="form-select my-1 me-sm-2 w-auto" id="distplot_modes">
			<option value="all"><?php echo lang('general_word_all')?></option>
                    <?php
                    foreach($modes as $mode) {
                        if ($mode->submode ?? '' == '') {
                            echo '<option value="' . $mode . '">' . strtoupper($mode) . '</option>'."\n";
                        }
                    }
                    ?>
            </select>
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
			<label class="my-1 me-2" for="distplot_propag"><?php echo lang('gen_hamradio_propagation_mode') ?></label>
            <select class="form-select my-1 me-sm-2 w-auto" id="distplot_propag">
                <option value="all" <?php if ($this->input->post('prop_mode') == "all" || $this->input->method() !== 'post') echo ' selected'; ?>><?php echo lang('general_word_all') ?></option>
                <option value="" <?php if ($this->input->post('prop_mode') == "" && $this->input->method() == 'post') echo ' selected'; ?>><?php echo lang('general_word_undefined') ?></option>
                <?php
                    foreach($prop_modes as $key => $label) {
                        echo '<option value="' . $key . '">' . $label . '</option>';
                    }
                ?>
            </select>
			<label class="my-1 me-2" for="distplot_powers"><?php echo lang('gen_hamradio_transmit_power'); ?></label>
            <select class="form-select my-1 me-sm-2 w-auto" id="distplot_powers">
			<option value="all"><?php echo lang('statistics_distances_bands_all')?></option>
                    <?php foreach($powers as $power) {
                        echo '<option value="' . $power . '">' . ($power ? $power : lang('general_word_undefined'))  . '</option>'."\n";
                    }
                    ?>
            </select>
            <button id="plot" type="button" name="plot" class="btn btn-primary" onclick="distPlot(this.form)"><?php echo lang('filter_options_show')?></button>
        </form>
    </div>

</div>