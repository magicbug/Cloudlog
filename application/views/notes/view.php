<?php foreach ($note->result() as $row) { ?>
<h2>Note - <?php echo $row->title; ?></h2>
<div class="wrap_content note">
<?php echo nl2br($row->note); ?>

<p>Options:
<ul>
	<li><a href="<?php echo site_url('notes/edit'); ?>/<?php echo $row->id; ?>">Edit</a></li>
	<li><a href="<?php echo site_url('notes/delete'); ?>/<?php echo $row->id; ?>">Delete</a></li>
</ul></p>
</div>
<?php } ?>