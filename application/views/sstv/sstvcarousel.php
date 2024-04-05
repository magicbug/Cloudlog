<div id="sstvCarouselIndicators" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators" id="sstv-carousel-indicators">
        <?php
        $i = 0;
        foreach ($sstvimages as $image) {
            echo '<li data-bs-target="#sstvCarouselIndicators" data-bs-slide-to="' . $i . '"';
            if ($i == 0) {
                echo 'class="active"';
            }
            $i++;
            echo '></li>';
        }
        ?>
    </ol>
    <div id="sstv-carousel-inner" class="carousel-inner">
        <?php
            $i = 1;
            foreach ($sstvimages as $image) {
                echo '<div class="text-center carousel-item carouselimageid_' . $image->id;
                if ($i == 1) {
                    echo ' active';
                }
                echo '">';
                echo '<img class="img-fluid w-qsl" src="' . base_url() . '/assets/sstvimages/' . $image->filename .'" alt="SSTV picture #'. $i++.'">';
                echo '</div>';
            }
        ?>
    </div>
    <a class="carousel-control-prev" href="#sstvCarouselIndicators" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </a>
    <a class="carousel-control-next" href="#sstvCarouselIndicators" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </a>
</div>
