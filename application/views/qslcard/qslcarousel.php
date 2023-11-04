<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
<?php if (count($qslimages) > 1) { ?>
<ol class="carousel-indicators">
    <?php
    $i = 0;
    foreach ($qslimages as $image) {
        echo '<li data-target="#carouselExampleIndicators" data-slide-to="'.$i.'" '.(($i == 0)?'class="active"':'').'></li>';
        $i++;
    }
    ?>
</ol>
<?php } ?>
<div class="carousel-inner">

    <?php
    $i = 1;
    $this->load->model('Qsl_model');
    foreach ($qslimages as $image) {
        echo '<div class="text-center carousel-item carouselimageid_'.$image->id.(($i == 1)?' active':'').'">';
        echo '<img class="img-fluid w-qsl" src="' . base_url() . $this->Qsl_model->getQslPath() . $image->filename .'" alt="QSL picture #'. $i++.'">';
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
