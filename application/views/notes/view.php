<div class="container notes">

	<div class="card"">
	  <div class="card-body">
	  	<?php foreach ($note->result() as $row) { ?>
	    <h2 class="card-title">Note - <?php echo $row->title; ?></h2>
	    <p class="card-text"><?php echo nl2br($row->note); ?></p>

	    <a href="<?php echo site_url('notes/edit'); ?>/<?php echo $row->id; ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Note</a>

	    <a href="<?php echo site_url('notes/delete'); ?>/<?php echo $row->id; ?>" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Delete Note</a>
	    <?php } ?>
	  </div
>	</div>

</div>