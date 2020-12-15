<div class="container setup-check-list">
<br>
	<h1><?php echo $page_title; ?></h1>

	<p>Welcome to Cloudlog, before you can start logging QSOs you need to carry out the following.</p>

	<ul class="list-group">
		<li class="list-group-item list-group-item-danger" ><i class="fas fa-times-circle"></i> Install Country Files</li>
		<li class="list-group-item list-group-item-danger"><i class="fas fa-times-circle"></i> Create a user account & delete m0abc demo account</li>

		<?php if($current_active == 0) { ?>
			<li class="list-group-item list-group-item-danger"><i class="fas fa-times-circle""></i> <a target="_blank" href="<?php echo site_url('station');?>">Create Station Profile</a></li>
		<?php } else { ?>
			<li class="list-group-item list-group-item-success"><i class="fas fa-check-circle"></i> Created Station Profile.</li>
		<?php } ?>
		<li class="list-group-item list-group-item-info"><i class="fas fa-exclamation-circle"></i> Setup Cronjobs</li>
		<li class="list-group-item list-group-item-info"><i class="fas fa-exclamation-circle"></i> Setup Callbook Details</li>
	</ul>


</div>
