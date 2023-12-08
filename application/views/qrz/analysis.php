<div class="container">
<h2><?php echo $page_title; ?></h2>

<?php
if (isset ($table_headers)) {
        echo $table_headers;
} else {
        echo 'No data imported. please check selected date. Must be in the past!';
}
?>
<?php if (isset ($table)) {echo $table;} ?>

</div>
