<div id="container">

	<h2>DX Cluster</h2>

	<div class="row">
	  <div class="span13">
	  	<script type="text/javascript">

		  $(document).ready(function(){
			
			$('#load_spots').load('<?php echo site_url('dxcluster/all_spots');?>').fadeIn("slow");
			
		  });

		var auto_refresh = setInterval(
			function ()
			{
			$('#load_spots').load('<?php echo site_url('dxcluster/all_spots');?>').fadeIn("slow");
			}, 4000); // refresh every 10000 milliseconds
			</script>
		<div class="contents">
		<table cellspacing="0" class="spots">
			<tr class="title">
				<td>Date</td>
				<td>Callsign</td>
				<td>Freq</td>
				<td>DX Callsign</td>
				<td>Comment</td>
			</tr>
			<tbody id="load_spots"></tbody>
		</table>
			</div>
	  </div>
	  <div class="span2 offset1">
	  <a class="btn primary" href="<?php echo site_url('dxcluster'); ?>">All Spots</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/160'); ?>">160m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/80'); ?>">80m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/40'); ?>">40m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/30'); ?>">30m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/20'); ?>">20m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/17'); ?>">17m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/15'); ?>">15m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/12'); ?>">12m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/10'); ?>">10m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/6'); ?>">6m</a><br>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/4'); ?>">4m</a><br>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/2'); ?>">2m</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/07'); ?>">70cm</a>
	  <a class="btn" href="<?php echo site_url('dxcluster/custom/023'); ?>">23cm</a>
	  </div>
	</div>

</div>