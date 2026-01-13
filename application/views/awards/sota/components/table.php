<?php
$custom_date_format = $custom_date_format ?? ($this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format'));
$meta = $meta ?? [];
$confirmedRefs = isset($confirmed_refs) ? array_fill_keys($confirmed_refs, true) : [];
?>
<table id="sotatable" class="table table-sm table-striped table-hover w-100">
    <thead>
        <tr>
            <th style="text-align:center"><?php echo lang('gen_hamradio_sota'); ?></th>
            <th style="text-align:center">Summit Name</th>
            <th style="text-align:center">Region</th>
            <th style="text-align:center"><?php echo lang('general_word_date'); ?></th>
            <th style="text-align:center"><?php echo lang('general_word_time'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_callsign'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_band'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_mode'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_rsts'); ?></th>
            <th style="text-align:center"><?php echo lang('gen_hamradio_rstr'); ?></th>
            <th style="text-align:center" title="W = Worked, C = Confirmed">QSL</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)) { foreach ($rows as $row) { $ref = $row->COL_SOTA_REF; $normRef = strtoupper(trim($ref)); $info = $meta[$normRef] ?? []; $ts = strtotime($row->COL_TIME_ON); $modeVal = $row->COL_SUBMODE ?: $row->COL_MODE; $isConfirmed = isset($confirmedRefs[$normRef]); ?>
        <tr>
            <td style="text-align:center"><?php echo htmlspecialchars($ref); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($info['name'] ?? ''); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($info['region'] ?? ''); ?></td>
            <td style="text-align:center"><?php echo date($custom_date_format, $ts); ?></td>
            <td style="text-align:center"><?php echo date('H:i', $ts); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_CALL); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_BAND); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($modeVal); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_RST_SENT); ?></td>
            <td style="text-align:center"><?php echo htmlspecialchars($row->COL_RST_RCVD); ?></td>
            <td style="text-align:center"><span style="font-weight: bold; color: <?php echo $isConfirmed ? '#28a745' : '#007bff'; ?>"><?php echo $isConfirmed ? 'C' : 'W'; ?></span></td>
        </tr>
        <?php } } else { ?>
        <tr><td colspan="11" class="text-center"><?php echo lang('general_word_nothing_found'); ?></td></tr>
        <?php } ?>
    </tbody>
</table>
