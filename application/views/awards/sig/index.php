<div class="container">
<h2><?php echo $page_title; ?></h2>

<?php if ($sig_types) { ?>
    <table style="width:100%" class="table-sm table tabledxcc table-bordered table-hover table-striped table-condensed text-center">

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
    echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
}
?>
</div>