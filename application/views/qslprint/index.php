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
	    <p class="card-text">
	    	Here you can export requested QSLs as CSV-file or ADIF and mark them as sent via buro in a mass transaction if you like. Requested QSOs are QSOs marked as "Requested" or "Queued" in the QSL-sent-field. The considered QSOs for this functions would be those of the active station profile.
	    </p>
		
		    
    <a href="<?php echo site_url('qslprint/exportcsv'); ?>" title="Export CSV-file" target="_blank" class="btn btn-outline-secondary btn-sm">Export requested QSLs to CSV-file</a>
    
    <a href="<?php echo site_url('qslprint/exportadif'); ?>" title="Export ADIF" target="_blank" class="btn btn-outline-secondary btn-sm">Export requested QSLs to ADIF-file</a>

    <a href="<?php echo site_url('qslprint/qsl_printed'); ?>" title="Mark QSLs as printed" target="_blank" class="btn btn-outline-secondary btn-sm">Mark requested QSLs as sent</a>
 
	  </div>
	</div>

</div>
