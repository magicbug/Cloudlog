<div class="container" id="edit_band_dialog">
	<form>

		<input type="hidden" name="id" value="<?php echo $my_band->id; ?>">
		<div class="mb-3">
			<label for="bandInput"><?php echo lang('gen_hamradio_band'); ?></label>
			<input type="text" class="form-control" name="band" id="bandInput" aria-describedby="bandInputHelp" value="<?php if(set_value('band') != "") { echo set_value('band'); } else { echo $my_band->band; } ?>" required>
			<small id="bandInputHelp" class="form-text text-muted"><?php echo lang('options_bands_name_band'); ?></small>
		</div>
		<div class="mb-3">
			<label for="bandGroup"><?php echo lang('gen_hamradio_bandgroup'); ?></label>
			<input type="text" class="form-control" name="bandgroup" id="bandGroup" aria-describedby="bandgroupInputHelp" value="<?php if(set_value('bandgroup') != "") { echo set_value('bandgroup'); } else { echo $my_band->bandgroup; } ?>" required>
			<small id="bandgroupInputHelp" class="form-text text-muted"><?php echo lang('options_bands_name_bandgroup'); ?></small>
		</div>
		<div class="mb-3">
			<label for="ssbqrg"><?php echo lang('options_bands_ssb_qrg'); ?></label>
			<input type="text" class="form-control" name="ssbqrg" id="ssbqrg" aria-describedby="ssbqrgInputHelp" value="<?php echo $my_band->ssb; ?>" required>
			<small id="ssbqrgInputHelp" class="form-text text-muted"><?php echo lang('options_bands_ssb_qrg_hint'); ?></small>
		</div>
		<div class="mb-3">
			<label for="dataqrg"><?php echo lang('options_bands_data_qrg'); ?></label>
			<input type="text" class="form-control" name="dataqrg" id="dataqrg" aria-describedby="dataqrgInputHelp" value="<?php echo $my_band->data; ?>" required>
			<small id="dataqrgInputHelp" class="form-text text-muted"><?php echo lang('options_bands_data_qrg_hint'); ?></small>
		</div>
		<div class="mb-3">
			<label for="cwqrg"><?php echo lang('options_bands_cw_qrg'); ?></label>
			<input type="text" class="form-control" name="cwqrg" id="cwqrg" aria-describedby="cwqrgInputHelp" value="<?php echo $my_band->cw; ?>" required>
			<small id="cwqrgInputHelp" class="form-text text-muted"><?php echo lang('options_bands_cw_qrg_hint'); ?></small>
		</div>

		<button type="button" onclick="saveUpdatedBand(this.form);" class="btn btn-primary"><i class="fas fa-plus-square"></i> <?php echo lang('options_save'); ?></button>

	</form>
</div>