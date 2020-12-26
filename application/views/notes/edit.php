

<div class="container notes">
<?php foreach ($note->result() as $row) { ?>
<div class="card">
  <div class="card-header">
    <h2 class="card-title">Edit Note</h2>
	<ul class="nav nav-tabs card-header-tabs">
	    <li class="nav-item">
	    	<a class="nav-link" href="<?php echo site_url('notes'); ?>">Notes</a>
	    </li>
	    <li class="nav-item">
	    	<a class="nav-link" href="<?php echo site_url('notes/add'); ?>">Create Note</a>
	    </li>
	</ul>
  </div>

  <div class="card-body">

  	<?php if (!empty(validation_errors())): ?>
    <div class="alert alert-danger">
        <a class="close" data-dismiss="alert" title="close">x</a>
        <ul><?php echo (validation_errors('<li>', '</li>')); ?></ul>
    </div>
	<?php endif; ?>

	<form method="post" action="<?php echo site_url('notes/edit'); ?>/<?php echo $id; ?>" name="notes_add" id="notes_add">

	<div class="form-group">
		<label for="inputTitle">Title</label>
		<input type="text" name="title" class="form-control" value="<?php echo $row->title; ?>" id="inputTitle">
	</div>

	<div class="form-group">
	   <label for="catSelect">Category</label>
	   <select name="category" class="form-control" id="catSelect">
	   	<option value="General" selected="selected">General</option>
		<option value="Antennas">Antennas</option>
		<option value="Satellites">Satellites</option>
	   </select>
	</div>

	<div class="form-group">
		<label for="inputTitle">Note Contents</label>
		<div id="quillArea"><?php echo $row->note; ?></div>
		<textarea name="content" style="display:none" id="hiddenArea"></textarea>
	</div>

	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<button type="submit" value="Submit" class="btn btn-primary">Save Note</button>
	</form>
  </div>

  <?php } ?>
</div>

</div>

