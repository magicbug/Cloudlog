<div class="container api">

<br>
<?php if($this->session->flashdata('notice')) { ?>
<div class="alert alert-success" role="alert">
	<?php echo $this->session->flashdata('notice'); ?>
</div>
<?php } ?>

<div class="card">
  <div class="card-header">
    <?php echo $page_title; ?>
  </div>
  <div class="card-body">
    <h5 class="card-title">Editing Description for API Key: <?php echo $api_info['key']; ?></h5>

<?php if(validation_errors()) { ?>
<div class="alert alert-warning" role="alert">
  <?php echo validation_errors(); ?>
</div>
<?php } ?>


	<form method="post" action="<?php echo site_url('api/edit'); ?>/<?php echo $api_info['key']; ?>" name="APIDescription">
	  <div class="mb-3">
	    <label for="APIDescription">API Description</label>
	    <input type="text" class="form-control" name="api_desc" id="APIDescription" aria-describedby="APIDescriptionHelp" value="<?php echo $api_info['description']; ?>">
	    <small id="APIDescriptionHelp" class="form-text text-muted">Simple name to describe what you use this API for.</small>
	  </div>

	  <input type="hidden" name="api_key" value="<?php echo $api_info['key']; ?>">

	  <button type="submit" class="btn btn-primary">Submit</button>
	</form>

  </div>
</div>

</div>

