
<div class="container">

	<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<div class="card">
		<div class="card-header">
			<?php echo $page_title; ?>
		</div>
		<div class="card-body">
			<h5 class="card-title"></h5>
			<p class="card-text"></p>
			<?php if($this->session->flashdata('notice')) { ?>
				<div id="message" >
					<?php echo $this->session->flashdata('notice'); ?>
				</div>
			<?php } ?>

			<?php $this->load->helper('form'); ?>

			<?php echo validation_errors(); ?>

			<form method="post" action="<?php echo site_url('themes/edit/'); ?><?php echo $theme->id; ?>" name="edit_theme">
				<div class="mb-3">
					<label for="themenameInput">Theme Name</label>
					<input type="text" class="form-control" name="name" id="nameInput" aria-describedby="themenameInputHelp" value="<?php if(set_value('name') != "") { echo set_value('name'); } else { echo $theme->name; } ?>" required>
					<small id="themenameInputHelp" class="form-text text-muted">This is the name that is used to display the theme in the theme list.</small>
				</div>

				<div class="mb-3">
					<label for="foldernameInput">Folder Name</label>
					<input type="text" class="form-control" name="foldername" id="foldernameInput" aria-describedby="foldernameInputHelp" value="<?php if(set_value('foldername') != "") { echo set_value('foldername'); } else { echo $theme->foldername; } ?>">
					<small id="foldernameInputHelp" class="form-text text-muted">This is the name of the folder where your CSS-files are placed under assets/css.</small>
				</div>

				<button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus-square"></i> Update Theme</button>

			</form>
		</div>
	</div>

	<br>

</div>
