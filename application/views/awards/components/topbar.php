<?php
$topbar_title = $title ?? ($page_title ?? 'Awards');
$topbar_subtitle = $subtitle ?? '';
$topbar_icon = $icon ?? 'fas fa-trophy';
$topbar_info_lines = $info_lines ?? null;
$topbar_actions = $actions ?? array();
$topbar_has_info = is_array($topbar_info_lines) && count($topbar_info_lines) > 0;
?>
<?php if ($topbar_has_info) { ?>
<div id="awardInfoButton" class="awards-info-wrap">
    <script>
        var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
        var lang_award_info_ln1 = "<?php echo $topbar_info_lines[0] ?? ''; ?>";
        var lang_award_info_ln2 = "<?php echo $topbar_info_lines[1] ?? ''; ?>";
        var lang_award_info_ln3 = "<?php echo $topbar_info_lines[2] ?? ''; ?>";
        var lang_award_info_ln4 = "<?php echo $topbar_info_lines[3] ?? ''; ?>";
    </script>
<?php } ?>
    <div class="card awards-topbar mb-3">
        <div class="card-body py-2 px-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="awards-brand-icon d-inline-flex align-items-center justify-content-center">
                    <i class="<?php echo htmlspecialchars($topbar_icon, ENT_QUOTES, 'UTF-8'); ?>"></i>
                </span>
                <div>
                    <h1 class="h5 mb-0 fw-semibold"><?php echo $topbar_title; ?></h1>
                    <?php if ($topbar_subtitle !== '') { ?>
                        <div class="small text-muted"><?php echo $topbar_subtitle; ?></div>
                    <?php } ?>
                </div>
            </div>
            <div class="d-flex flex-wrap align-items-center gap-2">
                <?php foreach ($topbar_actions as $action) { ?>
                    <a href="<?php echo $action['url']; ?>" class="btn btn-sm <?php echo $action['class'] ?? 'btn-outline-secondary'; ?>">
                        <?php if (!empty($action['icon'])) { ?><i class="<?php echo htmlspecialchars($action['icon'], ENT_QUOTES, 'UTF-8'); ?> me-1"></i><?php } ?>
                        <?php echo $action['label']; ?>
                    </a>
                <?php } ?>
                <?php if ($topbar_has_info) { ?>
                    <button type="button" class="btn btn-sm btn-primary" id="displayAwardInfo">
                        <i class="fas fa-info-circle me-1"></i><?php echo lang('awards_info_button'); ?>
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>
<?php if ($topbar_has_info) { ?>
</div>
<?php } ?>
