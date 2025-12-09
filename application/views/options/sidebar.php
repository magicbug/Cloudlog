<?php
// Determine active menu item
$current_page = $this->uri->segment(2) ?: 'index';
?>
<div class="col-md-3">
	<div class="card">
		<div class="card-header">
			<h5 class="card-title mb-0"><i class="fas fa-cog"></i> <?php echo lang('options_cloudlog_options'); ?></h5>
		</div>
		<ul class="list-group list-group-flush">
			<li class="list-group-item <?php echo ($current_page == 'appearance') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo site_url('options/appearance'); ?>">
					<i class="fas fa-palette"></i> <?php echo lang('options_appearance'); ?>
				</a>
			</li>
			<li class="list-group-item <?php echo ($current_page == 'radio') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo site_url('options/radio'); ?>">
					<i class="fas fa-broadcast-tower"></i> <?php echo lang('options_radios'); ?>
				</a>
			</li>
			<li class="list-group-item <?php echo ($current_page == 'email') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo site_url('options/email'); ?>">
					<i class="fas fa-envelope"></i> <?php echo lang('options_email'); ?>
				</a>
			</li>
			<?php if (!$this->config->item('disable_open_registration')) { ?>
			<li class="list-group-item <?php echo ($current_page == 'registration') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo site_url('options/registration'); ?>">
					<i class="fas fa-user-plus"></i> <?php echo lang('options_registration'); ?>
				</a>
			</li>
			<?php } ?>
			<li class="list-group-item <?php echo ($current_page == 'oqrs') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo site_url('options/oqrs'); ?>">
					<i class="fas fa-id-card"></i> <?php echo lang('options_oqrs'); ?>
				</a>
			</li>
			<li class="list-group-item <?php echo ($current_page == 'version_dialog') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo site_url('options/version_dialog'); ?>">
					<i class="fas fa-info-circle"></i> <?php echo lang('options_version_dialog'); ?>
				</a>
			</li>
		</ul>
	</div>
</div>
