<div class="container">
<h2><?php echo $page_title; ?></h2>

<?php
if (isset ($tableheaders)) {
        echo $tableheaders;
} else {
        echo 'No data imported. please check selected date. Must be in the past!';
}
?>
<?php if (isset ($table)) {echo $table;} ?>

</div>
