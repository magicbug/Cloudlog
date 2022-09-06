
<div class="container" id="create_mode">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

		<?php if($this->session->flashdata('notice')) { ?>
			<div id="message" >
			<?php echo $this->session->flashdata('notice'); ?>
			</div>
		<?php } ?>

		<?php $this->load->helper('form'); ?>

		<?php echo validation_errors(); ?>

		<form>
		<div class="form-group">
		    <label for="bandInput">Band</label>
		    <input type="text" class="form-control" name="band" id="bandInput" aria-describedby="bandInputHelp" required>
		    <small id="bandInputHelp" class="form-text text-muted">Band name</small>
		  </div>
		  
			<button type="button" onclick="createBand(this.form);" class="btn btn-primary"><i class="fas fa-plus-square"></i> Create band</button>

		</form>
</div>