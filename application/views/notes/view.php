<div class="container notes">

	<div class="card">
	<?php foreach ($note->result() as $row) { ?>
            <div class="card-header">
		<h2 class="card-title"><?php echo lang('notes_note'); ?> - <?php echo $row->title; ?></h2>
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                    	<a class="nav-link" href="<?php echo site_url('notes'); ?>">
                                <?php echo lang('notes'); ?>
                            </a>
                    </li>
                    <li class="nav-item">
                    	<a class="nav-link active" href="<?php echo site_url('notes/add'); ?>">
                            <?php echo lang('notes_create'); ?>
                        </a>
                    </li>
                </ul>
          </div>
	  <div class="card-body">
	    <p class="card-text"><?php echo nl2br($row->note); ?></p>

	    <a href="<?php echo site_url('notes/edit'); ?>/<?php echo $row->id; ?>" class="btn btn-primary">
	        <i class="fas fa-edit"></i> <?php echo lang('notes_edit'); ?>
            </a>

	    <a href="<?php echo site_url('notes/delete'); ?>/<?php echo $row->id; ?>" class="btn btn-danger">
	        <i class="fas fa-trash-alt"></i> <?php echo lang('notes_delete'); ?>
            </a>
	    <?php } ?>
	  </div
>	</div>

</div>
