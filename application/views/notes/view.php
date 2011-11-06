<div id="container">
<?php foreach ($note->result() as $row) { ?>
	<h2>Note - <?php echo $row->title; ?></h2>

	<div class="row show-grid">
	  <div class="span13">
	  
	<?php echo nl2br($row->note); ?>


	  </div>
	  <div class="span2 offset1">
	 	 <a class="btn primary" href="<?php echo site_url('notes/edit'); ?>/<?php echo $row->id; ?>">Edit Note</a>
	 	 <br/><br/>
	 	 <a class="btn" href="<?php echo site_url('notes/delete'); ?>/<?php echo $row->id; ?>">Delete Note</a>
	  </div>
	</div>
<?php } ?>
</div>