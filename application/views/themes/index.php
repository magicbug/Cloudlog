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
		<div class="card-header">
			Themes list
		</div>
		<div class="card-body">
			<p class="card-text">
				Using the theme list, you can control which Themes are shown in the account settings. Deleting a theme here, does not delete the css theme folder.
			</p>
			<div class="table-responsive">
				<table style="width:100%" class="contesttable table table-sm table-striped">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Foldername</th>
						<th scope="col"></th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($themes as $theme) { ?>
						<tr>
							<td><?php echo $theme->name;?></td>
							<td><?php echo $theme->foldername;?></td>
							<td>
								<a href="<?php echo site_url('themes/edit')."/".$theme->id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
							</td>
							<td class='theme_<?php echo $theme->id ?>'>
								<a href="javascript:deleteTheme('<?php echo $theme->id; ?>', '<?php echo $theme->name; ?>');" class="btn btn-danger btn-sm" ><i class="fas fa-trash-alt"></i> Delete</a>
							</td>
						</tr>

					<?php } ?>
					</tbody>
					<table>
			</div>
			<br/>
			<p><button onclick="addThemeDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add a Theme</button></p>
		</div>
	</div>
