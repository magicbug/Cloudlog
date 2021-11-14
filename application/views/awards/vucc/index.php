<div class="container">
    <h2><?php echo $page_title; ?></h2>

<?php if (!empty($vucc_array)) { ?>

        <table class="table table-sm table-bordered table-hover table-striped table-condensed text-center">
            <thead>
            <tr>
                <td>Band</td>
                <td>Grids Worked</td>
                <td>Grids Confirmed</td>
            </tr>
            </thead>
            <tbody>
                <?php foreach($vucc_array as $band => $vucc) {
                    echo '<tr>';
                    echo '<td><a href=\'vucc_band?Band="'. $band . '"\'>'. $band .'</td>';
                    echo '<td><a href=\'vucc_band?Band="'. $band . '"&Type="worked"\'>'. $vucc['worked'] .'</td>';
                    echo '<td><a href=\'vucc_band?Band="'. $band . '"&Type="confirmed"\'>'. $vucc['confirmed'] .'</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <?php } else {
            echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
        } ?>
</div>