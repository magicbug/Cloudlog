<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
<?php if (count($qslimages) > 1) { ?>
<ol class="carousel-indicators">
    <?php
    $i = 0;
    foreach ($qslimages as $image) {
        echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . $i . '"';
        if ($i == 0) {
            echo 'class="active"';
        }
        $i++;
        echo '></li>';
    }
    ?>
</ol>
<?php } ?>
<div class="carousel-inner">

    <?php
    $i = 1;
    $CI =& get_instance();
    $CI->load->model('Qsl_model');
    foreach ($qslimages as $image) {
        echo '<div class="text-center carousel-item carouselimageid_'.$image->id.(($i == 1)?' active':'').'" >';
		?>
		<table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
		<thead>
			<tr>
				<th><?php echo lang('gen_hamradio_callsign'); ?></th>
				<th><?php echo lang('general_word_datetime'); ?></th>
				<th><?php echo lang('gen_hamradio_mode'); ?></th>
				<th><?php echo lang('gen_hamradio_band'); ?></th>
				<th><?php echo lang('general_word_name'); ?></th>
				<th><?php echo lang('gen_hamradio_dxcc'); ?></th>
				<th><?php echo lang('gen_hamradio_state'); ?></th>
				<th><?php echo lang('gen_hamradio_cq_zone'); ?></th>
				<th><?php echo lang('gen_hamradio_iota'); ?></th>
				<th><?php echo lang('gen_hamradio_gridsquare'); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php
			echo '<tr>';
			echo '<td>'.$image->COL_CALL.'</td>';
			echo '<td>'.$image->COL_TIME_ON.'</td>';
			echo '<td>'.$image->COL_MODE.'</td>';
			echo '<td>'.$image->COL_BAND.'</td>';
			echo '<td>'.$image->COL_NAME.'</td>';
			echo '<td>'.$image->COL_COUNTRY.'</td>';
			echo '<td>'.$image->COL_STATE.'</td>';
			echo '<td>'.$image->COL_CQZ.'</td>';
			echo '<td>'.$image->COL_IOTA.'</td>';
			echo '<td>'.$image->COL_GRIDSQUARE.'</td>';
			echo '</tr>';
		?>
		</tbody>
</table>
        <?php echo '<img class="img-fluid w-qsl" src="' . base_url() . $CI->Qsl_model->getQslPath() . $image->filename .'" alt="QSL picture #'. $i++.'">';
        echo '</div>';
    }
    ?>
</div>
<?php if (count($qslimages) > 1) { ?>
	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only"><?php echo lang('general_word_previous'); ?></span>
	</a>
	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only"><?php echo lang('general_word_next'); ?></span>
	</a>
<?php } ?>
</div>
