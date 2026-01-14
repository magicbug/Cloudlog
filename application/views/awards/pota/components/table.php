<?php
$custom_date_format = $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format');
?>
<table id="potatable" class="table table-sm table-striped table-hover w-100">
    <thead>
        <tr>
            <th style="text-align:center"><?php echo lang('gen_hamradio_pota_reference'); ?></th>
            <th style="text-align:center"><?php echo lang('general_word_date'); ?></th>
            <th style="text-align:center"><?php echo lang('general_word_time'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_callsign'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_band'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_rsts'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_rstr'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)) { foreach ($rows as $row) { $ts=strtotime($row->COL_TIME_ON); ?>
        <tr>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_POTA_REF); ?></td>
            <td style="text-align:center"><?php echo date($custom_date_format, $ts); ?></td>
            <td style="text-align:center"><?php echo date('H:i', $ts); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_CALL); ?></td>
            <td style="text-align:center"><?php echo ($row->COL_SAT_NAME ? htmlspecialchars($row->COL_SAT_NAME) : htmlspecialchars($row->COL_BAND)); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_RST_SENT); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_RST_RCVD); ?></td>
        </tr>
        <?php } } else { ?>
        <tr><td colspan="7" class="text-center"><?php echo lang('general_word_nothing_found'); ?></td></tr>
        <?php } ?>
    </tbody>
</table>
