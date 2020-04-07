<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <h3>Filtering on <?php echo $filter ?></h3>
    <?php
    $i = 1;
    if ($vucc_array) {
        echo '<table class="table table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>#</td>
            <td>Gridsquare</td>
            <td>QSL</td>
            <td>LoTW</td>
        </tr>
        </thead>
        <tbody>';
        foreach ($vucc_array as $vucc => $value) {      // Fills the table with the data
            echo '<tr>
            <td>'. $i++ .'</td>
            <td><a href=\'vucc_details?Gridsquare="'. $vucc .'"&Band="'. $band . '"\'>'. $vucc .'</td>
            <td>'. $value['qsl'] . '</td>
            <td>'. $value['lotw'] .'</td>
        </tr>';
        }
        echo '</tfoot></table></div>';

    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>