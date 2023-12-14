<div class="container">
        <!-- Award Info Box -->
        <br>
        <div id="awardInfoButton">
            <script>
            var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
            var lang_award_info_ln1 = "<?php echo lang('awards_sig_description_ln1'); ?>";
            var lang_award_info_ln2 = "<?php echo lang('awards_sig_description_ln2'); ?>";
            var lang_award_info_ln3 = "<?php echo lang('awards_sig_description_ln3'); ?>";
            var lang_award_info_ln4 = "<?php echo lang('awards_sig_description_ln4'); ?>";
            </script>
            <h2><?php echo $page_title; ?></h2>
            <button type="button" class="btn btn-sm btn-primary me-1" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
        </div>
        <!-- End of Award Info Box -->

<?php if ($sig_types) { ?>
    <table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center">

	<tr>
		<td>Award Type</td>
        <td># QSOs</td>
        <td># Refs</td>
    </tr>

    <?php
    foreach ($sig_types as $row) {
	?>

    <tr>
		<td>
			<?php echo $row->col_sig; ?>
		</td>
        <td>
            <a href='sig_details?type="<?php echo $row->col_sig; ?>"'><?php echo $row->qsos; ?></a>
        </td>
        <td>
            <a href='sig_details?type="<?php echo $row->col_sig; ?>"'><?php echo $row->refs; ?></a>
        </td>
	</tr>
    <?php } ?>
	</table>
<?php }
else {
    echo '<div class="alert alert-danger" role="alert">Nothing found!</div>';
}
?>
</div>