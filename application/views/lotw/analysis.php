<div class="container">
<h2><?php echo $page_title; ?></h2>

<?php 
if (isset ($lotw_table_headers)) {
	echo $lotw_table_headers;
} else {
	echo 'No data imported. please check selected date. Must be in the past!';
}
?>
<?php if (isset ($lotw_table)) {echo $lotw_table;} ?>

</div>
