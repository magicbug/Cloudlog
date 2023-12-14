
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
		<div class="mb-3">
		    <label for="modeInput">ADIF Mode</label>
		    <input type="text" class="form-control" name="mode" id="modeInput" aria-describedby="modeInputHelp" required>
		    <small id="modeInputHelp" class="form-text text-muted">Name of mode in ADIF-specification</small>
		  </div>
		  
		  <div class="mb-3">
		    <label for="submodeInput">ADIF Sub-Mode</label>
		    <input type="text" class="form-control" name="submode" id="submodeInput" aria-describedby="submodeInputHelp">
		    <small id="submodeInputHelp" class="form-text text-muted">Name of sub-mode in ADIF-specification</small>
		  </div>

			<div class="mb-3">
		    <label for="qrgmodeInput">SSB/CW/DATA</label>
			<select id="qrgmodeInput" class="form-select mode form-select-sm" name="qrgmode">
				<option value="CW">CW</option>
				<option value="SSB">SSB</option>
				<option value="DATA">DATA</option>
			</select>
		    <small id="qrgmodeInputHelp" class="form-text text-muted">Defines the QRG-segment in bandplan.</small>
		  </div>

		  <div class="mb-3">
		    <label for="activeInput">Active</label>
			<select id="activeInput" class="form-select mode form-select-sm" name="active">
				<option value="1">active</option>
				<option value="0">not active</option>
			</select>
		    <small id="activeInputHelp" class="form-text text-muted">Set to active if to be listed in Modes-list</small>
		  </div>

			<button type="button" onclick="createMode(this.form);" class="btn btn-primary"><i class="fas fa-plus-square"></i> Create mode</button>

		</form>
</div>