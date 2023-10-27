
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
		    <label for="bandInput"><?php echo lang('gen_hamradio_band'); ?></label>
		    <input type="text" class="form-control" name="band" id="bandInput" aria-describedby="bandInputHelp" required>
		    <small id="bandInputHelp" class="form-text text-muted"><?php echo lang('options_bands_name_band'); ?></small>
		  </div>
		  <div class="form-group">
			<label for="bandGroup"><?php echo lang('gen_hamradio_bandgroup'); ?></label>
			<input type="text" class="form-control" name="bandgroup" id="bandGroup" aria-describedby="bandgroupInputHelp" required>
			<small id="bandgroupInputHelp" class="form-text text-muted"><?php echo lang('options_bands_name_bandgroup'); ?></small>
		</div>
		<div class="form-group">
			<label for="ssbqrg"><?php echo lang('options_bands_ssb_qrg'); ?></label>
			<input type="text" class="form-control" name="ssbqrg" id="ssbqrg" aria-describedby="ssbqrgInputHelp" required>
			<small id="ssbqrgInputHelp" class="form-text text-muted"><?php echo lang('options_bands_ssb_qrg_hint'); ?></small>
		</div>
		<div class="form-group">
			<label for="dataqrg"><?php echo lang('options_bands_data_qrg'); ?></label>
			<input type="text" class="form-control" name="dataqrg" id="dataqrg" aria-describedby="dataqrgInputHelp" required>
			<small id="dataqrgInputHelp" class="form-text text-muted"><?php echo lang('options_bands_data_qrg_hint'); ?></small>
		</div>
		<div class="form-group">
			<label for="cwqrg"><?php echo lang('options_bands_cw_qrg'); ?></label>
			<input type="text" class="form-control" name="cwqrg" id="cwqrg" aria-describedby="cwqrgInputHelp" required>
			<small id="cwqrgInputHelp" class="form-text text-muted"><?php echo lang('options_bands_cw_qrg_hint'); ?></small>
		</div>
		  
			<button type="button" onclick="createBand(this.form);" class="btn btn-primary"><i class="fas fa-plus-square"></i> <?php echo lang('options_save'); ?></button>

		</form>
</div>