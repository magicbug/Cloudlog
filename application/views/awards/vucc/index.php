<div class="container">
  <!-- Award Info Box -->
  <br>
  <div id="awardInfoButton">
    <script>
      var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
      var lang_award_info_ln1 = "<?php echo lang('awards_vucc_description_ln1'); ?>";
      var lang_award_info_ln2 = "<?php echo lang('awards_vucc_description_ln2'); ?>";
      var lang_award_info_ln3 = "<?php echo lang('awards_vucc_description_ln3'); ?>";
      var lang_award_info_ln4 = "<?php echo lang('awards_vucc_description_ln4'); ?>";
    </script>
    <h2><?php echo $page_title; ?></h2>
    <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
  </div>
  <!-- End of Award Info Box -->
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
            echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
        } ?>
</div>
