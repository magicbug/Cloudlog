<div class="container">
	<h2><?php echo $page_title; ?></h2>

	<?php if(!extension_loaded('xml')) { ?>

	<div class="alert alert-danger" role="alert">
	  You must install php-xml for this to work.
	</div>

	<?php } else { ?>

		<input type="submit" id="btn_update_dxcc" value="Update Dxcc" />

		<div id="dxcc_update_status">Status:</br></div>
			
		<br/>
		    
		<a href="<?php echo site_url('update/check_missing_dxcc');?>">Check missing DXCC/Countries values</a>
		<a href="<?php echo site_url('update/check_missing_dxcc/all');?>">[Re-Check ALL]</a>

		<style>
			#dxcc_update_status{
			   display: None;
			}
		</style>
	<?php } ?>
</div>


