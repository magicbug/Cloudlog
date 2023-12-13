<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_counties_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_counties_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_counties_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_counties_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->
    <?php if ($counties_array) { ?>
    <table  style="width:100%" class="countiestable table table-sm table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>State</td>
            <td>Counties Worked</td>
            <td>Counties Confirmed</td>
        </tr>
        </thead>
        <tbody>
        <?php
        $worked = 0;
        $confirmed = 0;
        foreach($counties_array as $counties) {
            echo '<tr>';
            echo '<td>' . $counties['COL_STATE'] .'</td>';
            echo '<td><a href=\'counties_details?State="'.$counties['COL_STATE'].'"&Type="worked"\'>'. $counties['countycountworked'] .'</a></td>';
            echo '<td><a href=\'counties_details?State="'.$counties['COL_STATE'].'"&Type="confirmed"\'>'. $counties['countycountconfirmed'] .'</a></td>';
            echo '</tr>';
            $worked += $counties['countycountworked'];
            $confirmed += $counties['countycountconfirmed'];
        }
        ?><tfoot><tr>
            <td>Total</td>
            <td><a href=counties_details?State="All"&Type="worked"><?php echo $worked ?></a></td>
            <td><a href=counties_details?State="All"&Type="confirmed"><?php echo $confirmed ?></a></td>
        </tr></tfoot>
        </tbody>
    </table>
    <?php } else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    }
    ?>
</div>