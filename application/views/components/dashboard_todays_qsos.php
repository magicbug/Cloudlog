<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php if ($todays_qsos >= 1) { ?>
	<span class="dashboard-today-pill has-qsos">
		<i class="fas fa-broadcast-tower"></i>
		<span><?php echo lang('dashboard_you_have_had'); ?> <strong><?php echo number_format((int) $todays_qsos); ?></strong> <?php echo $todays_qsos != 1 ? lang('dashboard_qsos_today') : str_replace('QSOs', 'QSO', lang('dashboard_qsos_today')); ?></span>
	</span>
<?php } else { ?>
	<span class="dashboard-today-pill no-qsos">
		<i class="fas fa-broadcast-tower"></i>
		<span><?php echo lang('notice_turn_the_radio_on'); ?></span>
	</span>
<?php } ?>
