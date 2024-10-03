<div class="card-body">
	<h2>Results for <?php echo $id; ?></h2>

	<p>Sorry, but we didn't find any past QSOs with <?php echo $id; ?></p>

	<?php if (!empty($error)) { ?>
		<p><?php echo $error; ?></p>
	<?php } else { ?>
		<h3>Callbook Search for <?php echo $id; ?></h3>
		<?php if (isset($callsign['callsign'])) { ?>
			<table>

				<tr>
					<td align="left">Callsign</td>
					<td style="padding: 0.3em 0 0.3em 0.5em;" align="left"><?php echo str_replace("0", "&Oslash;", strtoupper($callsign['callsign'])); ?> <a target="_blank" href="https://www.qrz.com/db/<?php echo strtoupper($callsign['callsign']); ?>"><img style="vertical-align: baseline" width="16" height="16" src="<?php echo base_url(); ?>images/icons/qrz.png" alt="Lookup <?php echo strtoupper($callsign['callsign']); ?> on QRZ.com"></a> <a target="_blank" href="https://www.hamqth.com/<?php echo strtoupper($callsign['callsign']); ?>"><img style="vertical-align: baseline" width="16" height="16" src="<?php echo base_url(); ?>images/icons/hamqth.png" alt="Lookup <?php echo strtoupper($callsign['callsign']); ?> on HamQTH"></a></td>
				</tr>

				<tr>
					<td style="padding: 0 0.3em 0 0;" align="left">Name</td>
					<td style="padding: 0.3em 0 0.3em 0.5em;" align="left"><?php echo $callsign['name']; ?></td>
				</tr>

				<tr>
					<td style="padding: 0 0.3em 0 0;" align="left">City</td>
					<td style="padding: 0.3em 0 0.3em 0.5em;" align="left"><?php echo $callsign['city']; ?></td>
				</tr>

				<?php if (isset($callsign['dxcc_name'])) { ?>
					<tr>
						<td style="padding: 0 0.3em 0 0;" align="left">DXCC</td>
						<td style="padding: 0.3em 0 0.3em 0.5em;" align="left"><?php echo $callsign['dxcc_name']; ?></td>
					</tr>
				<?php } ?>

				<tr>
					<td style="padding: 0 0.3em 0 0;" align="left">Gridsquare</td>
					<td style="padding: 0.3em 0 0.3em 0.5em;" align="left">
						<?php
						if ($grid_worked != 0) {
							echo " <span data-bs-toggle=\"tooltip\" title=\"Worked\" class=\"badge text-bg-success\" style=\"padding-left: 0.2em; padding-right: 0.2em;\">" . strtoupper($callsign['gridsquare']) . "</span>";
						} else {
							echo " <span data-bs-toggle=\"tooltip\" title=\"Not Worked\" class=\"badge text-bg-danger\" style=\"padding-left: 0.2em; padding-right: 0.2em;\">" . strtoupper($callsign['gridsquare']) . "</span>";
						}
						?>
					</td>
				</tr>

			</table>
		<?php } ?>
	<?php } ?>
</div>