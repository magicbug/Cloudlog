<?php
$first = isset($first_last['first']) ? $first_last['first'] : null;
$last = isset($first_last['last']) ? $first_last['last'] : null;
$custom_date_format = $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format');
?>
<div class="row g-3">
    <div class="col-auto">
        <div class="card card-body py-2">
            <strong><?php echo lang('awards_unique_total'); ?>:</strong> <?php echo (int)$total_uniques; ?>
        </div>
    </div>
    <div class="col-auto">
        <div class="card card-body py-2">
            <strong><?php echo lang('awards_confirmed_total'); ?>:</strong> <?php echo (int)$confirmed_uniques; ?>
        </div>
    </div>
    <div class="col-auto">
        <div class="card card-body py-2">
            <strong>First:</strong>
            <?php if ($first) { $ts=strtotime($first->COL_TIME_ON); echo htmlspecialchars($first->COL_POTA_REF).' · '.date($custom_date_format, $ts).' '.date('H:i', $ts); } else { echo '-'; } ?>
        </div>
    </div>
    <div class="col-auto">
        <div class="card card-body py-2">
            <strong>Last:</strong>
            <?php if ($last) { $ts=strtotime($last->COL_TIME_ON); echo htmlspecialchars($last->COL_POTA_REF).' · '.date($custom_date_format, $ts).' '.date('H:i', $ts); } else { echo '-'; } ?>
        </div>
    </div>
</div>
