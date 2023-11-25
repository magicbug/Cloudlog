<div id="qsl_card_labels_container" class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<?php echo validation_errors(); ?>

<form method="post" action="<?php echo site_url('labels/updateLabel/' . $label->id); ?>" name="create_label_type">

	<div class="card">
		<h2 class="card-header"><?php echo $page_title; ?></h2>

		<div class="card-body">

			<!-- Label Name Input -->
	    	<div class="mb-3">
			    <label for="LabelName">Label Name</label>
			    <input name="label_name" type="text" class="form-control" id="LabelName" aria-describedby="label_nameHelp" placeholder="Code 925041 6x3 Generic Label Sheet" value="<?php if(isset($label->label_name)) { echo $label->label_name; } ?>">
			    <small id="label_nameHelp" class="form-text text-muted">Label name used for display purposes so pick something meaningful perhaps the label style.</small>
			</div>

			<div class="mb-3 row">
    			<label class="col-sm-2 col-form-label" for="paperType_id">Paper Type</label>
			    <div class="col-sm-4">
				    <select name="paper_type_id" class="form-select" id="paperType_id">
						<?php
							foreach($papertypes as $paper){
								echo '<option value="' . ($paper->paper_id ?? '') . '"';
								if (($label->paper_type_id ?? '') == ($paper->paper_id ?? '')) echo ' selected';
								echo '>' . ucwords(strtolower(($paper->paper_name ?? ''))) . '</option>';
							}
						?>
					</select>
			    </div>

    			<label class="col-sm-2 col-form-label" for="measurementType">Measurement used</label>
			    <div class="col-sm-4">
				    <select name="measurementType" class="form-select" id="measurementType">
						<option value="mm" <?php if($label->metric == "mm") { echo "selected=\"selected\""; } ?>>Millimeters</option>
						<option value="in" <?php if($label->metric == "in") { echo "selected=\"selected\""; } ?>>Inches</option>
					</select>
			    </div>
  			</div>

			<div class="mb-3 row">
    			<label class="col-sm-2 col-form-label" for="marginTop">Margin Top</label>
			    <div class="col-sm-4">
				    <input name="marginTop" type="text" class="form-control" id="marginTop" aria-describedby="marginTopHelp" value="<?php if(isset($label->margintop)) { echo $label->margintop; } ?>">
			    	<small id="marginTopHelp" class="form-text text-muted">Top margin of labels</small>
			    </div>

    			<label class="col-sm-2 col-form-label" for="marginLeft">Margin Left</label>
			    <div class="col-sm-4">
				    <input name="marginLeft" type="text" class="form-control" id="marginLeft" aria-describedby="marginLeftHelp" value="<?php if(isset($label->marginleft)) { echo $label->marginleft; } ?>">
			    	<small id="marginLeftHelp" class="form-text text-muted">Left margin of labels.</small>
			    </div>
  			</div>

  			<div class="mb-3 row">
    			<label class="col-sm-2 col-form-label" for="NX">Labels horizontally</label>
			    <div class="col-sm-4">
				    <input name="NX" type="number" min="1" max="40" step="1" class="form-control" id="NX" aria-describedby="NXHelp" value="<?php if(isset($label->nx)) { echo $label->nx; } ?>">
			    	<small id="NXHelp" class="form-text text-muted">Number of labels horizontally across the page.</small>
			    </div>

    			<label class="col-sm-2 col-form-label" for="NY">Labels vertically</label>
			    <div class="col-sm-4">
				    <input name="NY" type="number" min="1" max="40" step="1" class="form-control" id="NY" aria-describedby="NYHelp" value="<?php if(isset($label->ny)) { echo $label->ny; } ?>">
			    	<small id="NYHelp" class="form-text text-muted">Number of labels vertically across the page.</small>
			    </div>
  			</div>

  			<div class="mb-3 row">
    			<label class="col-sm-2 col-form-label" for="SpaceX">Horizontal space</label>
			    <div class="col-sm-4">
				    <input name="SpaceX" type="text" class="form-control" id="SpaceX" value="<?php if(isset($label->spacex)) { echo $label->spacex; } ?>">
					<small id="NYHelp" class="form-text text-muted">Horizontal space between 2 labels.</small>
			    </div>

    			<label class="col-sm-2 col-form-label" for="SpaceY">Vertical space</label>
			    <div class="col-sm-4">
				    <input name="SpaceY" type="text" class="form-control" id="SpaceY" value="<?php if(isset($label->spacey)) { echo $label->spacey; } ?>">
					<small id="NYHelp" class="form-text text-muted">Vertical space between 2 labels.</small>
			    </div>
  			</div>

			<div class="mb-3 row">
    			<label class="col-sm-2 col-form-label" for="width">Width of label</label>
			    <div class="col-sm-4">
				    <input name="width" type="text" class="form-control" id="width" aria-describedby="widthHelp" value="<?php if(isset($label->width)) { echo $label->width; } ?>">
			    	<small id="widthHelp" class="form-text text-muted">Total width of one label.</small>
			    </div>

    			<label class="col-sm-2 col-form-label" for="height">Height of label</label>
			    <div class="col-sm-4">
				    <input name="height" type="text" class="form-control" id="height" aria-describedby="heightHelp" value="<?php if(isset($label->height)) { echo $label->height; } ?>">
			    	<small id="heightHelp" class="form-text text-muted">Total height of one label</small>
			    </div>
  			</div>

  			<div class="mb-3 row">
    			<label class="col-sm-2 col-form-label" for="font_size">Font Size</label>
			    <div class="col-sm-4">
				    <input name="font_size" type="number" min="1" max="40" step="1" class="form-control" id="font_size" aria-describedby="font_sizeHelp" value="<?php if(isset($label->font_size)) { echo $label->font_size; } ?>">
			    	<small id="font_sizeHelp" class="form-text text-muted">Font size used on the label don't go too big.</small>
			    </div>

    			<label class="col-sm-2 col-form-label" for="font_size">QSOs on label</label>
			    <div class="col-sm-4">
				    <input name="label_qsos" type="number" min="1" max="40" step="1" class="form-control" id="label_qsos" aria-describedby="font_sizeHelp" value="<?php if(isset($label->qsos)) { echo $label->qsos; } ?>">
			    </div>
  			</div>
  			<button type="submit" class="btn btn-primary"><i class="fas fa-plus-square"></i> Save Label Type</button>
		</div>
	</div>

</form>

</div>
<br>
