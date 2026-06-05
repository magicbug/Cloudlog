<div id="modal-backdrop" class="modal-backdrop fade show" style="display:block;"></div>
<div id="modal" class="modal fade show" tabindex="-1" style="display:block;">
<form hx-post="<?php echo base_url();?>index.php/qso/cwmacrosave" hx-target="#save-response" hx-swap="innerHTML">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
	  <div class="modal-content">
		<div class="modal-header">
		  <h5 class="modal-title">Winkey Macros</h5>
		  <button type="button" class="btn-close" onclick="closeModal()" aria-label="Close"></button>
		</div>

		<div class="modal-body">
			<div id="save-response"></div>
			<?php $macro_result = isset($result) ? $result : null; ?>

			<div class="alert alert-light border small py-2 mb-3" role="note">
				<strong>Macro tokens:</strong> <code>[MYCALL]</code>, <code>[CALL]</code>, <code>[RSTS]</code>
			</div>

			<div class="row g-2 align-items-end mb-2 small text-muted fw-bold">
				<div class="col-12 col-md-2">Key</div>
				<div class="col-12 col-md-3">Button Label</div>
				<div class="col-12 col-md-7">Macro Text</div>
			</div>

			<div class="row g-2 align-items-end mb-3">
				<div class="col-12 col-md-2"><span class="badge text-bg-warning">F1</span></div>
				<div class="col-12 col-md-3"><input name="function1_name" type="text" class="form-control" id="function1_name" maxlength="6" placeholder="CQ" value="<?php echo $macro_result ? $macro_result->function1_name : ''; ?>"></div>
				<div class="col-12 col-md-7"><input name="function1_macro" type="text" class="form-control" id="function1_macro" placeholder="CQ CQ CQ DE [MYCALL] [MYCALL] K" value="<?php echo $macro_result ? $macro_result->function1_macro : ''; ?>"></div>
			</div>

			<div class="row g-2 align-items-end mb-3">
				<div class="col-12 col-md-2"><span class="badge text-bg-warning">F2</span></div>
				<div class="col-12 col-md-3"><input name="function2_name" type="text" class="form-control" id="function2_name" maxlength="6" placeholder="REPT" value="<?php echo $macro_result ? $macro_result->function2_name : ''; ?>"></div>
				<div class="col-12 col-md-7"><input name="function2_macro" type="text" class="form-control" id="function2_macro" placeholder="[CALL] DE [MYCALL] [RSTS] [RSTS] K" value="<?php echo $macro_result ? $macro_result->function2_macro : ''; ?>"></div>
			</div>

			<div class="row g-2 align-items-end mb-3">
				<div class="col-12 col-md-2"><span class="badge text-bg-warning">F3</span></div>
				<div class="col-12 col-md-3"><input name="function3_name" type="text" class="form-control" id="function3_name" maxlength="6" placeholder="TU" value="<?php echo $macro_result ? $macro_result->function3_name : ''; ?>"></div>
				<div class="col-12 col-md-7"><input name="function3_macro" type="text" class="form-control" id="function3_macro" placeholder="[CALL] TU 73 DE [MYCALL] K" value="<?php echo $macro_result ? $macro_result->function3_macro : ''; ?>"></div>
			</div>

			<div class="row g-2 align-items-end mb-3">
				<div class="col-12 col-md-2"><span class="badge text-bg-warning">F4</span></div>
				<div class="col-12 col-md-3"><input name="function4_name" type="text" class="form-control" id="function4_name" maxlength="6" placeholder="QRZ" value="<?php echo $macro_result ? $macro_result->function4_name : ''; ?>"></div>
				<div class="col-12 col-md-7"><input name="function4_macro" type="text" class="form-control" id="function4_macro" placeholder="QRZ DE [MYCALL] K" value="<?php echo $macro_result ? $macro_result->function4_macro : ''; ?>"></div>
			</div>

			<div class="row g-2 align-items-end">
				<div class="col-12 col-md-2"><span class="badge text-bg-warning">F5</span></div>
				<div class="col-12 col-md-3"><input name="function5_name" type="text" class="form-control" id="function5_name" maxlength="6" placeholder="TEST" value="<?php echo $macro_result ? $macro_result->function5_name : ''; ?>"></div>
				<div class="col-12 col-md-7"><input name="function5_macro" type="text" class="form-control" id="function5_macro" placeholder="TEST DE [MYCALL] K" value="<?php echo $macro_result ? $macro_result->function5_macro : ''; ?>"></div>
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
