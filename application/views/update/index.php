<div id="container">
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
<script>
$(document).ready(function(){
    $('#btn_update_dxcc').bind('click', function(){
        $('#dxcc_update_status').show();
        $.ajax({url:"update/dxcc"});
        setTimeout(update_stats,5000);
    });
    function update_stats(){
        $('#dxcc_update_status').load('<?php echo base_url()?>updates/status.html', function(val){
            $('#dxcc_update_staus').html(val);

            if ((val  === null) || (val.substring(0,4) !="DONE")){
                setTimeout(update_stats, 5000);
            }
        });

    }

});
</script>



