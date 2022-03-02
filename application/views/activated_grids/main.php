<div class="container">

	<br>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>

	<h2><?php echo $page_title; ?></h2>

	<nav class="nav">
	  <a class="nav-link" href="<?php echo site_url('activated_grids/satellites'); ?>">Satellites</a>
	  <a class="nav-link" href="<?php echo site_url('activated_grids/band/2m'); ?>">Band</a>
	</nav>
</div>
