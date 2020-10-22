<div class="container">

	<br>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>

	<h2><?php echo $page_title; ?></h2>

	<div class="card">
	  <div class="card-header">
	    Export Requested QSLs for Printing
	  </div>
	  <div class="card-body">
	    <p class="card-text">Here you can export requested QSLs as CSV or ADIF files for printing and, optionally, mark them as sent via bureau.</p>
	    <p class="card-text">Requested QSLs are any QSOs with a value of "Requested" or "Queued" in their "QSL Sent" field.</p>
	    <p class="card-text">Only QSOs under the active station profile will be exported.</p>
		    
    <a href="<?php echo site_url('qslprint/exportcsv'); ?>" title="Export CSV-file" target="_blank" class="btn btn-outline-secondary btn-sm">Export requested QSLs to CSV-file</a>
    
    <a href="<?php echo site_url('qslprint/exportadif'); ?>" title="Export ADIF" target="_blank" class="btn btn-outline-secondary btn-sm">Export requested QSLs to ADIF-file</a>

    <a href="<?php echo site_url('qslprint/qsl_printed'); ?>" title="Mark QSLs as printed" target="_blank" class="btn btn-outline-secondary btn-sm">Mark requested QSLs as sent</a>
 
	  </div>
	</div>

</div>
