<div class="col-md-3">
	<div class="card">
		<ul class="list-group list-group-flush">
			<li class="list-group-item"><a class="nav-link" href="<?php echo site_url('options/appearance'); ?>"><?php echo lang('options_appearance'); ?></a></li>
			<li class="list-group-item"><a class="nav-link" href="<?php echo site_url('options/radio'); ?>"><?php echo lang('options_radios'); ?></a></li>
			<li class="list-group-item"><a class="nav-link" href="<?php echo site_url('options/email'); ?>"><?php echo lang('options_email'); ?></a></li>
			<?php if (!$this->config->item('disable_open_registration')) { ?>
			<li class="list-group-item"><a class="nav-link" href="<?php echo site_url('options/registration'); ?>"><?php echo lang('options_registration'); ?></a></li>
			<?php } ?>
			<li class="list-group-item"><a class="nav-link" href="<?php echo site_url('options/oqrs'); ?>"><?php echo lang('options_oqrs'); ?></a></li>
			<li class="list-group-item"><a class="nav-link" href="<?php echo site_url('options/version_dialog'); ?>"><?php echo lang('options_version_dialog'); ?></a></li>
		</ul>
	</div>
</div>
