<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <!-- Sub Nav for Awards -->

    <?php $this->load->view("awards/nav_bar")?>
        <table class="table table-bordered table-hover table-striped table-condensed text-center">
            <thead>
            <tr>
                <td>Band</td>
                <td>Grids worked</td>
                <td>Grids confirmed</td>
            </tr>
            </thead>
            <tbody>
                <?php foreach($vucc_array as $band => $vucc) {
                    echo '<tr>';
                    echo '<td><a href=\'vucc_band?Band="'. $band . '"\'>'. $band .'</td>';
                    echo '<td>' . $vucc['worked'] . '</td>';
                    echo '<td>' . $vucc['confirmed'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
</div>