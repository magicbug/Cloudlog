<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'Special Interest Groups &middot; Summary of logged SIG programs and references',
        'icon' => 'fas fa-star',
        'info_lines' => array(
            lang('awards_sig_description_ln1'),
            lang('awards_sig_description_ln2'),
            lang('awards_sig_description_ln3'),
            lang('awards_sig_description_ln4'),
        ),
    )); ?>

    <?php if ($sig_types) { ?>
        <div class="card">
        <div class="table-responsive">
        <table style="width:100%" class="table-sm table table-hover align-middle mb-0 text-center">
            <thead>
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
        </div>
        </div>
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