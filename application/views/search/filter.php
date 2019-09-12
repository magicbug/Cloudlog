<div class="container">

	<br>

	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
			<p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

	<!-- Filter options here -->
	<div class="card">
	  <div class="card-header">
	    <?php echo $page_title; ?>
	  </div>
	  <div class="card-body">

		<div class="card-text" id="builder"></div>
		
		<p class="card-text">
		<button class="btn btn-primary" id="btn-get">Search</button>

		<button class="btn btn-warning" id="btn-reset">Reset</button>
		</p>

	    	<span class="badge badge-info">Info</span> You can find out how to use the <a href="https://github.com/magicbug/Cloudlog/wiki/Search----Filter" target="_blank">search filter functons</a> in the wiki.</a>
	    </p>

	  </div>
	</div>

	<br>

	<!-- Search Results here -->
	<div class="card search-results-box">
	  <div class="card-header">
	    Search Results
	  </div>
	  <div class="card-body">

		<div class="table-responsive">
			<table id="results" class="table table-striped table-hover">
				<tr class="titles">
					<td>Date</td>
					<td>Time</td>
					<td>Call</td>
					<td>Mode</td>
					<td>Sent</td>
					<td>Recv</td>
					<td>Band</td>
					<td>Country</td>
					<?php if(($this->config->item('use_auth')) && ($this->session->userdata('user_type') >= 2)) { ?>
					<td>QSL</td>
					
					<?php if($this->session->userdata('user_eqsl_name') != "") { ?>
						<td>eQSL</td>
					<?php } else { ?>
						<td></td>
					<?php } ?>

					<?php if($this->session->userdata('user_lotw_name') != "") { ?>
						<td>LoTW</td>
					<?php } else { ?>
						<td></td>
					<?php } ?>

					<td></td>
					<?php } ?>
				</tr>
	    	</table>
	    </div>

	  </div>
	</div>

</div>