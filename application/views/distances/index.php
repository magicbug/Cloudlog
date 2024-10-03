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
                <label class="my-1 me-2" for="distplot_sats"><?php echo lang('general_word_satellite')?></label>
                <select class="form-select my-1 me-sm-2 w-auto" disabled id="distplot_sats">
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