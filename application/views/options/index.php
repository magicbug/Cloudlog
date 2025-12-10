<div class="container settings">

	<div class="row">
		<!-- Nav Start -->
		<?php $this->load->view('options/sidebar') ?>
		<!-- Nav End -->

		<!-- Content -->
		<div class="col-md-9">
            <div class="card mb-3">
                <div class="card-header">
					<h2 class="card-title mb-0"><i class="fas fa-cog"></i> <?php echo $page_title; ?></h2>
				</div>
                <div class="card-body">
                    <p class="lead"><?php echo lang('options_message1'); ?></p>
					<p>Select a category from the menu on the left to configure global settings for your Cloudlog installation.</p>
                </div>
            </div>

			<!-- Settings Overview Cards -->
			<div class="row">
				<div class="col-md-6 mb-3">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-palette text-primary"></i> <?php echo lang('options_appearance'); ?></h5>
							<p class="card-text">Configure themes, dashboard layout, and visual settings for your Cloudlog installation.</p>
							<a href="<?php echo site_url('options/appearance'); ?>" class="btn btn-sm btn-outline-primary">Configure</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-broadcast-tower text-success"></i> <?php echo lang('options_radios'); ?></h5>
							<p class="card-text">Configure radio control settings and integration with radio hardware.</p>
							<a href="<?php echo site_url('options/radio'); ?>" class="btn btn-sm btn-outline-success">Configure</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-envelope text-info"></i> <?php echo lang('options_email'); ?></h5>
							<p class="card-text">Set up email server configuration for sending notifications and QSL requests.</p>
							<a href="<?php echo site_url('options/email'); ?>" class="btn btn-sm btn-outline-info">Configure</a>
						</div>
					</div>
				</div>

				<?php if (!$this->config->item('disable_open_registration')) { ?>
				<div class="col-md-6 mb-3">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-user-plus text-warning"></i> <?php echo lang('options_registration'); ?></h5>
							<p class="card-text">Control whether new users can register for accounts on this installation.</p>
							<a href="<?php echo site_url('options/registration'); ?>" class="btn btn-sm btn-outline-warning">Configure</a>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="col-md-6 mb-3">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-id-card text-danger"></i> <?php echo lang('options_oqrs'); ?></h5>
							<p class="card-text">Configure Online QSL Request System settings for QSL card management.</p>
							<a href="<?php echo site_url('options/oqrs'); ?>" class="btn btn-sm btn-outline-danger">Configure</a>
						</div>
					</div>
				</div>

				<div class="col-md-6 mb-3">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title"><i class="fas fa-info-circle text-secondary"></i> <?php echo lang('options_version_dialog'); ?></h5>
							<p class="card-text">Customize the version update dialog and release notes displayed to users.</p>
							<a href="<?php echo site_url('options/version_dialog'); ?>" class="btn btn-sm btn-outline-secondary">Configure</a>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>