<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <h3><?php echo lang('general_word_filtering_on'); ?> <?php echo $filter ?></h3>
    <?php
    $i = 1;
    if ($vucc_array) {
        echo '<table style="width:100%" class="table table-sm tablevucc table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>#</td>
            <td>Gridsquare</td>';

        if ($type != 'worked') {
            echo '<td>QSL</td>
                <td>LoTW</td>';
        } else {
            echo '<td>Call</td>';
        }
        echo '</tr>
        </thead>
        <tbody>';
    foreach ($vucc_array as $vucc => $value) {      // Fills the table with the data
        echo '<tr>
            <td>'. $i++ .'</td>
			<td><a href=\'javascript:displayContacts("'. $vucc .'","'. $band . '","All","VUCC")\'>'. $vucc .'</td>';

            if ($type != 'worked') {
                echo '<td>'. $value['qsl'] . '</td>
                    <td>'. $value['lotw'] .'</td>';
            } else {
            echo '<td>'. $value['call'] .'</td>';
            }

        echo '</tr>';
        }
        echo '</tbody></table></div>';

    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>
