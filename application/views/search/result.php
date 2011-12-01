<h2>Results for <?php echo $id; ?></h2>

<p>Sorry, but we didn't find any past QSOs with <?php echo $id; ?></p>

<?php if(isset($callsign['callsign'])) { ?>
<h3>Callbook Search for <?php echo $id; ?></h3>

<table>

<tr>
	<td>Callsign</td>
	<td><?php echo $callsign['callsign']; ?></td>
</tr>

<tr>
	<td>Name</td>
	<td><?php echo $callsign['name']; ?></td>
</tr>

<tr>
	<td>City</td>
	<td><?php echo $callsign['city']; ?></td>
</tr>

<tr>
	<td>Gridsquare</td>
	<td><?php echo $callsign['gridsquare']; ?></td>
</tr>

</table>

<?php } ?>