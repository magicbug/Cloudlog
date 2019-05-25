<div class="container">
	<h2><?php echo $page_title; ?></h2>


    <input type="submit" id="btn_update_dxcc" value="Update Dxcc" />

    <div id="dxcc_update_status">
    Status:</br>
    </div>
<br/>
    <a href="<?php echo site_url('update/check_missing_dxcc');?>">Check missing DXCC/Countries values</a>
    <a href="<?php echo site_url('update/check_missing_dxcc/all');?>">[Re-Check ALL]</a>
</div>

<style>
#dxcc_update_status{
   display: None;
}

</style>


