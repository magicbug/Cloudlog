<div id="container">
	<h2><?php echo $page_title; ?></h2>
	
	<?php if($this->session->flashdata('notice')) { ?>
	    <div id="message" >
	        <?php echo $this->session->flashdata('notice'); ?>
	    </div>
	<?php } ?>
	<?php

	$this->load->helper('form');

	?>
	<?php echo validation_errors(); ?>

	<form method="post" action="<?php echo site_url('station/create'); ?>" name="create_profile">

	<table>
		<tr>
			<td>Station Profile Name</td>
			<td><input type="text" name="station_profile_name" value="" required /></td>
		</tr>

		<tr>
			<td>Station Callsign</td>
			<td><input type="text" name="station_callsign" value="" required /></td>
		</tr>

		<tr>
			<td>DXCC # For Country</td>
			<td>
				<?php if ($dxcc_list->num_rows() > 0) { ?>
					<select id="dxcc_select" name="dxcc">
						<?php foreach ($dxcc_list->result() as $dxcc) { ?>
							<option value="<?php echo $dxcc->adif; ?>"><?php echo $dxcc->name; ?></option>
						<?php } ?>
					</select>
			<?php } ?>

			</td>
		</tr>

<script>
$( document ).ready(function() {
	$('#country').val($("#dxcc_select option:selected").text());

	$( "#dxcc_select" ).change(function() {
	  $('#country').val($("#dxcc_select option:selected").text());
	});
});

</script>

		<tr>
			<td>City</td>
			<td><input type="text" name="city" value="" required /></td>
		</tr>

		<tr>
			<td>Country</td>
			<td><input type="text" id="country" name="station_country" value="" required />
			</td>
		</tr>

		<tr>
			<td>Cnty</td>
			<td><input type="text" name="station_cnty" value="" /></td>
		</tr>

		<tr>
			<td>CQ Zone</td>
			<td><input type="text" name="station_cq" value="" required/></td>
		</tr>

		<tr>
			<td>ITU Zone</td>
			<td><input type="text" name="station_itu" value="" required/></td>
		</tr>


		<tr>
			<td>Gridsquare</td>
			<td><input type="text" name="gridsquare" value="" required /></td>
		</tr>

		<tr>
			<td>IOTA Reference</td>
			<td><input type="text" name="iota" value="" /></td>
		</tr>

		<tr>
			<td>SOTA Reference</td>
			<td><input type="text" name="sota" value="" /></td>
		</tr>
	</table>

	<div class="actions"><input class="btn primary" type="submit" value="Submit" /></div>

	</form>

	</div>