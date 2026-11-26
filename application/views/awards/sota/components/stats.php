<?php
$custom_date_format = $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format');
$first = $first_last['first'] ?? null;
$last = $first_last['last'] ?? null;
?>
<style>
.sota-stats-card {
    border: 1px solid var(--bs-border-color, rgba(0, 0, 0, 0.12));
    border-left: 4px solid var(--bs-primary, #0d6efd);
    padding: 16px 20px;
    margin-bottom: 15px;
    background-color: var(--bs-tertiary-bg, #f8f9fa);
    color: var(--bs-body-color, inherit);
    border-radius: 0.65rem;
}

.sota-stats-card.confirmed {
    border-left-color: #198754;
    background-color: rgba(25, 135, 84, 0.12);
}

.sota-stats-card.first-last {
    border-left-color: #0dcaf0;
    background-color: var(--bs-tertiary-bg, #f8f9fa);
}

.sota-stats-card h5 {
    margin: 0 0 10px 0;
    color: var(--bs-body-color, inherit);
    font-weight: 600;
}

.sota-stats-number {
    font-size: 28px;
    font-weight: bold;
    color: var(--bs-primary, #0d6efd);
    margin: 5px 0;
}

.sota-stats-card.confirmed .sota-stats-number {
    color: #198754;
}

.sota-stats-card.first-last .sota-stats-number {
    color: #0dcaf0;
}
</style>

<div class="row g-3 mt-2">
    <div class="col-md-6">
        <div class="sota-stats-card">
            <h5>📍 SOTA Progress</h5>
            <div class="row">
                <div class="col-6">
                    <div class="text-muted small">Total Worked</div>
                    <div class="sota-stats-number"><?php echo (int)($total_uniques ?? 0); ?></div>
                </div>
                <div class="col-6">
                    <div class="text-muted small">Total Confirmed</div>
                    <div class="sota-stats-number" style="color: #28a745;"><?php echo (int)($confirmed_uniques ?? 0); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="sota-stats-card first-last">
            <h5>📅 First & Latest</h5>
            <div class="small">
                <div><strong>First:</strong> 
                    <?php if ($first) { $ts = strtotime($first->COL_TIME_ON); echo htmlspecialchars($first->COL_SOTA_REF).' · '.date($custom_date_format, $ts).' '.date('H:i', $ts); } else { echo '<span class="text-muted">-</span>'; } ?>
                </div>
                <div style="margin-top: 8px;"><strong>Latest:</strong> 
                    <?php if ($last) { $ts = strtotime($last->COL_TIME_ON); echo htmlspecialchars($last->COL_SOTA_REF).' · '.date($custom_date_format, $ts).' '.date('H:i', $ts); } else { echo '<span class="text-muted">-</span>'; } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="sota-stats-card">
            <h5>📊 By Band</h5>
            <?php if (!empty($by_band)) { foreach ($by_band as $band => $cnt) { ?>
                <div class="d-flex justify-content-between mb-2"><span><?php echo htmlspecialchars($band); ?></span><span class="badge bg-primary"><?php echo (int)$cnt; ?></span></div>
            <?php } } else { ?>
                <span class="text-muted">No band data</span>
            <?php } ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="sota-stats-card">
            <h5>🎙️ By Mode</h5>
            <?php if (!empty($by_mode)) { foreach ($by_mode as $mode => $cnt) { ?>
                <div class="d-flex justify-content-between mb-2"><span><?php echo htmlspecialchars($mode); ?></span><span class="badge bg-info"><?php echo (int)$cnt; ?></span></div>
            <?php } } else { ?>
                <span class="text-muted">No mode data</span>
            <?php } ?>
        </div>
    </div>
</div>
