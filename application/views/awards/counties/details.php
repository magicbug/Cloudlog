<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <h3><?php echo lang('general_word_filtering_on'); ?> <?php echo $filter ?></h3>
    <?php
    $i = 1;
    if ($counties_array && !empty($counties_array['counties'])) {
        echo '<table  style="width:100%" class="countiestable table table-sm table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>#</td>
            <td>State</td>
            <td>County</td>
        </tr>
        </thead>
        <tbody>';
        foreach ($counties_array['counties'] as $county) {
            $state = htmlspecialchars($county['COL_STATE']);
            $name = htmlspecialchars($county['COL_CNTY']);
            echo '<tr>
            <td>'. $i++ .'</td>
            <td>'. $state .'</td>
            <td><a href="javascript:displayCountyContacts(' . htmlspecialchars(json_encode($county['COL_STATE'])) . ',' . htmlspecialchars(json_encode($county['COL_CNTY'])) . ')">'. $name .'</a></td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
    }

    if ($counties_array && !empty($counties_array['cities'])) { ?>
        <h4 class="mt-4" id="independent-cities">Independent city credits</h4>
        <p class="text-muted">These contacts are in an independent city that borders more than one county, so they are not counted above. MARAC lets you claim any one of the adjoining counties for each contact.</p>
        <table style="width:100%" class="table table-sm table-bordered table-hover table-striped text-center">
            <thead>
                <tr>
                    <td>State</td>
                    <td>Independent city</td>
                    <td>Adjoining counties</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($counties_array['cities'] as $city) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($city['COL_STATE']); ?></td>
                        <td><a href="javascript:displayCountyContacts(<?php echo htmlspecialchars(json_encode($city['COL_STATE'])); ?>,<?php echo htmlspecialchars(json_encode($city['COL_CNTY'])); ?>)"><?php echo htmlspecialchars($city['COL_CNTY']); ?></a></td>
                        <td><?php echo htmlspecialchars(implode(', ', $city['neighbors'])); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } ?>
</div>
