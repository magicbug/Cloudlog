<?php foreach ($note->result() as $row) { ?>
<h2>Note - <?php echo $row->title; ?></h2>
<div class="wrap_content note">
<?php echo nl2br($row->note); ?>

<p>Options: <a href="<?php echo site_url('notes/edit'); ?>/<?php echo $row->id; ?>"><img src="<?php echo base_url(); ?>images/application_edit.png" width="16" height="16" alt="Edit" /></a> <a href="<?php echo site_url('notes/delete'); ?>/<?php echo $row->id; ?>"><img src="<?php echo base_url(); ?>images/delete.png" width="16" height="16" alt="Delete" /></a></p>
</div>
<?php } ?>