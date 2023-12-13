<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
<?php if (count($qslimages) > 1) { ?>
<ol class="carousel-indicators">
    <?php
    $i = 0;
    foreach ($qslimages as $image) {
        echo '<li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="' . $i . '"';
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
        echo '">';
        echo '<img class="img-fluid w-qsl" src="' . base_url() . '/assets/qslcard/' . $image->filename .'" alt="QSL picture #'. $i++.'">';
        echo '</div>';
    }
    ?>
</div>
<?php if (count($qslimages) > 1) { ?>
	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</a>
	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</a>
<?php } ?>
</div>
