<div hx-get="<?php echo site_url('dashboard/todays_qso_component'); ?>" hx-trigger="every 30s">
    <?php if ($todays_qsos >= 1) { ?>
        <div class="alert alert-success" role="alert">
            <?php echo lang('dashboard_you_have_had'); ?> <strong><?php echo $todays_qsos; ?></strong> <?php echo $todays_qsos != 1 ? lang('dashboard_qsos_today') : str_replace('QSOs', 'QSO', lang('dashboard_qsos_today')); ?>
        </div>
    <?php } else { ?>
        <div class="alert alert-warning" role="alert">
            <span class="badge text-bg-info"><?php echo lang('general_word_important'); ?></span> <i class="fas fa-broadcast-tower"></i> <?php echo lang('notice_turn_the_radio_on'); ?>
        </div>
    <?php } ?>
</div>