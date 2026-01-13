<?php
$custom_date_format = $this->session->userdata('user_date_format') ?: $this->config->item('qso_date_format');
$first = $first_last['first'] ?? null;
$last = $first_last['last'] ?? null;
?>
<style>
.sota-stats-card {
    border-left: 4px solid #007bff;
    padding: 20px;
    margin-bottom: 15px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.sota-stats-card.confirmed {
    border-left-color: #28a745;
    background-color: #d4edda;
}

.sota-stats-card.first-last {
    border-left-color: #17a2b8;
    background-color: #d1ecf1;
}

.sota-stats-card h5 {
    margin: 0 0 10px 0;
    color: #333;
    font-weight: 600;
}

.sota-stats-number {
    font-size: 28px;
    font-weight: bold;
    color: #007bff;
    margin: 5px 0;
}

.sota-stats-card.confirmed .sota-stats-number {
    color: #28a745;
}

.sota-stats-card.first-last .sota-stats-number {
    color: #17a2b8;
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
