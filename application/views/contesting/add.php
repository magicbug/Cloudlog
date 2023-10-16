<div class="container">

	<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<h2><?php echo $page_title; ?></h2>

	<div class="card">
		<div class="card-body">
			<p class="card-text">
				<?php echo lang('admin_contest_menu_line_1'); ?>
			</p>
			<p class="card-text">
				<?php echo lang('admin_contest_menu_line_2'); ?>
			</p>
			<div class="table-responsive">
				<table style="width:100%" class="contesttable table table-sm table-striped">
					<thead>
					<tr>
						<th scope="col"><?php echo lang('admin_contest_menu_name'); ?></th>
						<th scope="col"><?php echo lang('admin_contest_menu_adif'); ?></th>
						<th scope="col"><?php echo lang('admin_contest_menu_active'); ?></th>
						<th scope="col"></th>
						<th scope="col"></th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($contests as $row) { ?>
						<tr>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['adifname'];?></td>
							<script>
								var lang_admin_contest_menu_n_active = '<?php echo lang('admin_contest_menu_n_active'); ?>';
								var lang_admin_contest_menu_activate = '<?php echo lang('admin_contest_menu_activate'); ?>';
								var lang_admin_contest_menu_active = '<?php echo lang('admin_contest_menu_active'); ?>';
								var lang_admin_contest_menu_deactivate = '<?php echo lang('admin_contest_menu_deactivate'); ?>';
							</script>
							<td class='contest_<?php echo $row['id'] ?>'><?php if ($row['active'] == 1) { echo lang('admin_contest_menu_active');} else { echo lang('admin_contest_menu_n_active');};?></td>
							<td style="text-align: center">
								<?php if ($row['active'] == 1) {
									echo "<button onclick='javascript:deactivateContest(". $row['id'] . ")' class='btn_" . $row['id'] . " btn btn-secondary btn-sm'>" . lang('admin_contest_menu_deactivate') . "</button>";
								} else {
									echo "<button onclick='javascript:activateContest(". $row['id'] . ")' class='btn_" . $row['id'] . " btn btn-secondary btn-sm'>" . lang('admin_contest_menu_activate') . "</button>";
								};?>
							</td>
							<td>
								<script>
									var lang_admin_danger = '<?php echo lang('admin_danger'); ?>';
									var lang_admin_contest_deletion_warning = '<?php echo lang('admin_contest_deletion_warning'); ?>';
									var lang_admin_contest_active_all_warning = '<?php echo lang('admin_contest_active_all_warning'); ?>';
									var lang_admin_contest_deactive_all_warning = '<?php echo lang('admin_contest_deactive_all_warning'); ?>';
								</script>
								<a href="<?php echo site_url('contesting/edit')."/".$row['id']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> <?php echo lang('admin_edit'); ?></a>
							</td>
							<td>
								<a href="javascript:deleteContest('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>');" class="btn btn-danger btn-sm" ><i class="fas fa-trash-alt"></i> <?php echo lang('admin_delete'); ?></a>
							</td>
						</tr>

					<?php } ?>
					</tbody>
					<table>
			</div>
			<br/>
			<p>
				<script>
					var lang_admin_contest_add_contest = '<?php echo lang('admin_contest_add_contest'); ?>';
					var lang_admin_close = '<?php echo lang('admin_close'); ?>'
				</script>
				<button onclick="createContestDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> <?php echo lang('admin_contest_add_contest'); ?></button>
				<button onclick="activateAllContests();" class="btn btn-primary btn-sm"><?php echo lang('admin_contest_all_active'); ?></button>
				<button onclick="deactivateAllContests();" class="btn btn-primary btn-sm"><?php echo lang('admin_contest_all_deactive'); ?></button>
			</p>
		</div>
	</div>
