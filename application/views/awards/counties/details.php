<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <h3><?php echo lang('general_word_filtering_on'); ?> <?php echo $filter ?></h3>
    <?php
    $i = 1;
    if ($counties_array) {
        echo '<table  style="width:100%" class="countiestable table table-sm table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>#</td>
            <td>State</td>
            <td>County</td>
        </tr>
        </thead>
        <tbody>';
        foreach ($counties_array as $county) {
            echo '<tr>
            <td>'. $i++ .'</td>
            <td>'. $county['COL_STATE'] .'</td>
            <td><a href=\'javascript:displayCountyContacts("'. $county['COL_STATE'] .'","'. $county['COL_CNTY'] .'")\'>'. $county['COL_CNTY'] .'</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>
