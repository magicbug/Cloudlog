
<div class="container" id="create_mode">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?> <?php echo $my_mode->mode; ?>
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

		<form method="post" action="<?php echo site_url('mode/edit/'); ?><?php echo $my_mode->id; ?>" name="create_mode">

			<input type="hidden" name="id" value="<?php echo $my_mode->id; ?>">
		  <div class="mb-3">
		    <label for="modeInput">ADIF Mode</label>
		    <input type="text" class="form-control" name="mode" id="modeInput" aria-describedby="modeInputHelp" value="<?php if(set_value('mode') != "") { echo set_value('mode'); } else { echo $my_mode->mode; } ?>" required>
		    <small id="modeInputHelp" class="form-text text-muted">Name of mode in ADIF-specification</small>
		  </div>

		  <div class="mb-3">
		    <label for="modeInput">ADIF Sub-Mode</label>
		    <input type="text" class="form-control" name="submode" id="submodeInput" aria-describedby="submodeInputHelp" value="<?php if(set_value('submode') != "") { echo set_value('submode'); } else { echo $my_mode->submode; } ?>">
		    <small id="submodeInputHelp" class="form-text text-muted">Name of sub-mode in ADIF-specification</small>
		  </div>

			<div class="mb-3">
		    <label for="qrgmodeInput">SSB/CW/DATA</label>
			<select id="qrgmodeInput" class="form-select mode form-select-sm" name="qrgmode">
			<?php
			printf("<option value=\"CW\" %s>CW</option>", $my_mode->qrgmode=="CW"?"selected=\"selected\"":"");
			printf("<option value=\"SSB\" %s>SSB</option>", $my_mode->qrgmode=="SSB"?"selected=\"selected\"":"");
			printf("<option value=\"DATA\" %s>DATA</option>", $my_mode->qrgmode=="DATA"?"selected=\"selected\"":"");
			?>
			</select>
		    <small id="qrgmodeInputHelp" class="form-text text-muted">Defines the QRG-segment in bandplan.</small>
		  </div>

		  <div class="mb-3">
		    <label for="activeInput">Active</label>
			<select id="activeInput" class="form-select mode form-select-sm" name="active">
			<?php
			printf("<option value=\"1\" %s>active</option>", $my_mode->active==1?"selected=\"selected\"":"");
			printf("<option value=\"0\" %s>not active</option>", $my_mode->active==0?"selected=\"selected\"":"");
			?>
			</select>
		    <small id="activeInputHelp" class="form-text text-muted">Set to active if to be listed in Modes-list</small>
		  </div>

			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Update mode</button>

		</form>
  </div>
</div>

<br>

</div>