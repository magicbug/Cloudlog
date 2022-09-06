<div class="container" id="edit_band_dialog">
	<form>

		<input type="hidden" name="id" value="<?php echo $my_band->id; ?>">
		<div class="form-group">
			<label for="modeInput">Band</label>
			<input type="text" class="form-control" name="band" id="bandInput" aria-describedby="bandInputHelp" value="<?php if(set_value('band') != "") { echo set_value('band'); } else { echo $my_band->band; } ?>" required>
			<small id="bandInputHelp" class="form-text text-muted">Name of band</small>
		</div>

		<button type="button" onclick="saveUpdatedBand(this.form);" class="btn btn-primary"><i class="fas fa-plus-square"></i> Update band</button>

	</form>
</div>