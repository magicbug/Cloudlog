<?php
$worked = isset($worked) ? (int)$worked : 0;
$thresholds = isset($thresholds) ? $thresholds : [10,25,50,100];
?>
<div class="row g-3">
    <?php foreach ($thresholds as $t) {
        $pct = max(0,min(100, $worked*100/$t));
        $remaining = max(0, $t - $worked);
    ?>
    <div class="col-12 col-md-6">
        <div class="card card-body">
            <div class="d-flex justify-content-between">
                <strong><?php echo $t; ?> parks</strong>
                <span><?php echo $worked; ?> / <?php echo $t; ?> (<?php echo round($pct); ?>%)</span>
            </div>
            <div class="progress mt-2" style="height:8px;">
                <div class="progress-bar" role="progressbar" style="width: <?php echo $pct; ?>%;" aria-valuenow="<?php echo $pct; ?>" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <small class="text-muted">Remaining: <?php echo $remaining; ?></small>
        </div>
    </div>
    <?php } ?>
</div>
