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
    foreach ($qslimages as $image) {
        echo '<div class="text-center carousel-item carouselimageid_' . $image->id;
        if ($i == 1) {
            echo ' active';
        }
        echo '">';?>
		<table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">
		<thead>
			<tr>
				<th>Callsign</th>
				<th>Date/Time</th>
				<th>Mode</th>
				<th>Band</th>
				<th>Name</th>
				<th>DXCC</th>
				<th>State</th>
				<th>CQ Zone</th>
				<th>IOTA</th>
				<th>Gridsquare</th>
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
        <?php echo '<img class="img-fluid w-qsl" src="' . base_url() . '/assets/qslcard/' . $image->filename .'" alt="QSL picture #'. $i++.'">';
        echo '</div>';
    }
    ?>
</div>
<?php if (count($qslimages) > 1) { ?>
	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="sr-only">Next</span>
	</a>
<?php } ?>
</div>
