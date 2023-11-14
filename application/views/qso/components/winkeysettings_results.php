<div id="modal-backdrop" class="modal-backdrop fade show" style="display:block;"></div>
<div id="modal" class="modal fade show" tabindex="-1" style="display:block;">
<form hx-post="<?php echo base_url();?>index.php/qso/cwmacrosave" hx-target=".modal-body">
	<div class="modal-dialog modal-dialog-centered">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title">Winkey Macros</h5>
		</div>

		<div class="modal-body">
				<div class="mb-3 row">
    				<label for="function1_name" class="col-sm-5 col-form-label">Function 1 - Name</label>
    				<div class="col-sm-7">
						<input name="function1_name" type="text" class="form-control" id="function1_name" maxlength="6" value="<?php echo $result->function1_name; ?>">
					</div>
  				</div>

				<div class="mb-3 row">
    				<label for="function1_macro" class="col-sm-5 col-form-label">Function 1 - Macro</label>
    				<div class="col-sm-7">
						<input name="function1_macro" type="text" class="form-control" id="function1_macro" value="<?php echo $result->function1_macro; ?>">
					</div>
  				</div>

				<hr>

				<div class="mb-3 row">
    				<label for="function2_name" class="col-sm-5 col-form-label">Function 2 - Name</label>
    				<div class="col-sm-7">
						<input name="function2_name" type="text" class="form-control" id="function2_name" maxlength="6" value="<?php echo $result->function2_name; ?>">
					</div>
  				</div>

				<div class="mb-3 row">
    				<label for="function2_macro" class="col-sm-5 col-form-label">Function 2 - Macro</label>
    				<div class="col-sm-7">
						<input name="function2_macro" type="text" class="form-control" id="function2_macro" value="<?php echo $result->function2_macro; ?>">
					</div>
  				</div>

				<hr>

				<div class="mb-3 row">
    				<label for="function3_name" class="col-sm-5 col-form-label">Function 3 - Name</label>
    				<div class="col-sm-7">
						<input name="function3_name" type="text" class="form-control" id="function3_name" maxlength="6" value="<?php echo $result->function3_name; ?>">
					</div>
  				</div>

				<div class="mb-3 row">
    				<label for="function3_macro" class="col-sm-5 col-form-label">Function 3 - Macro</label>
    				<div class="col-sm-7">
						<input name="function3_macro" type="text" class="form-control" id="function3_macro" value="<?php echo $result->function3_macro; ?>">
					</div>
  				</div>

				<hr>

				<div class="mb-3 row">
    				<label for="function4_name" class="col-sm-5 col-form-label">Function 4 - Name</label>
    				<div class="col-sm-7">
						<input name="function4_name" type="text" class="form-control" id="function4_name" maxlength="6" value="<?php echo $result->function4_name; ?>">
					</div>
  				</div>

				<div class="mb-3 row">
    				<label for="function4_macro" class="col-sm-5 col-form-label">Function 4 - Macro</label>
    				<div class="col-sm-7">
						<input name="function4_macro" type="text" class="form-control" id="function4_macro" value="<?php echo $result->function4_macro; ?>">
					</div>
  				</div>

				<hr>

				<div class="mb-3 row">
    				<label for="function5_name" class="col-sm-5 col-form-label">Function 5 - Name</label>
    				<div class="col-sm-7">
						<input name="function5_name" type="text" class="form-control" id="function5_name" maxlength="6" value="<?php echo $result->function5_name; ?>">
					</div>
  				</div>

				<div class="mb-3 row">
    				<label for="function5_macro" class="col-sm-5 col-form-label">Function 5 - Macro</label>
    				<div class="col-sm-7">
						<input name="function5_macro" type="text" class="form-control" id="function5_macro" value="<?php echo $result->function5_macro; ?>">
					</div>
  				</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary">Save</button>
			<button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
		</div>
	  </div>
	</div>
	</form>
</div>
