<div class="container">
    <!-- Award Info Box -->
    <br>
    <div id="awardInfoButton">
        <script>
        var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
        var lang_award_info_ln1 = "<?php echo lang('awards_sig_description_ln1'); ?>";
        var lang_award_info_ln2 = "<?php echo lang('awards_sig_description_ln2'); ?>";
        var lang_award_info_ln3 = "<?php echo lang('awards_sig_description_ln3'); ?>";
        var lang_award_info_ln4 = "<?php echo lang('awards_sig_description_ln4'); ?>";
        </script>
        <h2><?php echo $page_title; ?></h2>
        <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
    </div>
    <!-- End of Award Info Box -->

    <?php if ($sig_types) { ?>
        <table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
            <thead class="table-light">
            <tr>
                <th><?php echo lang('awards_sig_table_name'); ?></th>
                <th><?php echo lang('awards_sig_table_qsos'); ?></th>
                <th><?php echo lang('awards_sig_table_refs'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sig_types as $row) {
                // Redirect WAB to dedicated WAB awards page
                $link = (strtoupper($row->col_sig) === 'WAB') 
                    ? site_url('awards/wab')
                    : site_url('awards/sig_details?type=' . urlencode($row->col_sig));
            ?>
            <tr>
                <td>
                    <?php echo htmlspecialchars($row->col_sig); ?>
                </td>
                <td>
                    <a href="<?php echo $link; ?>"><?php echo $row->qsos; ?></a>
                </td>
                <td>
                    <a href="<?php echo $link; ?>"><?php echo $row->refs; ?></a>
                </td>
            </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php }
    else {
        echo '<div class="alert alert-info alert-dismissible fade show" role="alert">';
        echo '<i class="fa fa-info-circle me-2"></i>';
        echo '<strong>No SIGs recorded yet</strong>';
        echo '<p class="mb-0 mt-2">SIGs (Special Interest Groups) are custom awards tracked using your own defined categories. Perfect for club awards, regional challenges, or any custom tracking. To start tracking a SIG:</p>';
        echo '<ol class="mb-0 mt-2">';
        echo '<li>Log a QSO and edit it</li>';
        echo '<li>Enter a category name in the <strong>SIG</strong> field (e.g., "WAB", "Club Challenge", "Regional Award")</li>';
        echo '<li>Enter a reference identifier in the <strong>SIG Info</strong> field (e.g., "IO87", "MEM-001", "REG-2024")</li>';
        echo '<li>Save the QSO</li>';
        echo '</ol>';
        echo '<p class="mb-0 mt-2">Your SIG awards will appear here once you have logged QSOs with SIG categories.</p>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
    ?>
</div>