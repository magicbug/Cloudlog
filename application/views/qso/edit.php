<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>

		<!-- JS  -->
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-1.5.1.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui-1.8.12.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-dropdown.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-tabs.js"></script>

		<script type="text/javascript">
		  $(function () {
			$('.tabs').tabs()
		  })
		</script>

		<!-- CSS Files -->
		<link type="text/css" href="<?php echo base_url(); ?>css/flick/jquery-ui-1.8.12.custom.css" rel="stylesheet" />
		<link rel="stylesheet" href="<?php echo base_url();?>css/bootcamp/bootstrap.css" type="text/css" />
	</head>

	<body>

		<!-- Option to Delete QSO -->
		<div style="float: right; padding-right: 60px; padding-top: 30px;">
			<p><a href="<?php echo site_url('qso/delete'); ?>/<?php echo $COL_PRIMARY_KEY; ?>" >Delete QSO <img src="<?php echo base_url(); ?>/images/delete.png" width="16" height="16" alt="Delete" /></a></p>
		</div>

		<?php echo validation_errors(); ?>
		<form method="post" action="<?php echo site_url('qso/edit'); ?>" name="qsos">

		<table>
			<tr>
				<td>Start Date</td>
				<td>End Date</td>
			</tr>

			<tr>
				<td><input type="text" name="time_on" value="<?php echo $COL_TIME_ON; ?>" /></td>
				<td><input type="text" name="time_off" value="<?php echo $COL_TIME_OFF; ?>" /></td>
			</tr>
		</table>

		<table>
			<tr>
				<td>Callsign</td>
				<td><input type="text" name="callsign" value="<?php echo $COL_CALL; ?>" /></td>
			</tr>

			<?php if($COL_FREQ) { ?>
			<tr>
				<td>Freq</td>
				<td><input type="text" name="freq" value="<?php echo $COL_FREQ; ?>" /></td>
			</tr>
			<?php } ?>

			<tr>
				<td>Mode</td>
				<td><input type="text" name="mode" value="<?php echo $COL_MODE; ?>" /></td>
			</tr>

			<tr>
				<td>Band</td>
				<td><input type="text" name="band" value="<?php echo $COL_BAND; ?>" /></td>
			</tr>

			<tr>
				<td>RST Sent</td>
				<td><input type="text" name="rst_sent" value="<?php echo $COL_RST_SENT; ?>" /></td>
			</tr>

			<tr>
				<td>RST Recv</td>
				<td><input type="text" name="rst_recv" value="<?php echo $COL_RST_RCVD; ?>" /></td>
			</tr>

			<?php if ($COL_STX_STRING) { ?>
			<tr>
				<td>TX Serial</td>
				<td><input type="text" name="stx_string" value="<?php echo $COL_STX_STRING; ?>" /></td>
			</tr>
			<?php } ?>

			<?php if ($COL_SRX_STRING) { ?>
			<tr>
				<td>RX Serial</td>
				<td><input type="text" name="srx_string" value="<?php echo $COL_SRX_STRING; ?>" /></td>
			</tr>
			<?php } ?>

			<tr>
				<td>Gridsquare</td>
				<td>
					<input id="locator" type="text" name="locator" value="<?php echo $COL_GRIDSQUARE; ?>" size="7" />
					<p>For single Gridsquares</p>
				</td>
			</tr>

			<tr>
				<td>VUCC Gridsquare</td>
				<td>
					<input id="locator" type="text" name="vucc_grids" value="<?php echo $COL_VUCC_GRIDS; ?>" size="7" />			
					<p>Used for VUCC MultiGrids</p>
				</td>
			</tr>

			<tr>
				<td>Name</td>
				<td><input type="text" name="name" value="<?php echo $COL_NAME; ?>" /></td>
			</tr>

			<tr>
				<td>QTH</td>
				<td><input type="text" name="qth" value="<?php echo $COL_QTH; ?>" /></td>
			</tr>


			<tr>
				<td>Comment</td>
				<td><input type="text" name="comment" value="<?php echo $COL_COMMENT; ?>" /></td>
			</tr>

			<tr>
				<td>Propagation Mode</td>
				<td><input type="text" name="prop_mode" value="<?php echo $COL_PROP_MODE; ?>" /></td>
			</tr>
		</table>

		<table>
			<tr>
				<td>Sat Name</td>
				<td>Sat Mode</td>
			</tr>

			<tr>
				<td><input type="text" name="sat_name" value="<?php echo $COL_SAT_NAME; ?>" /></td>
				<td><input type="text" name="sat_mode" value="<?php echo $COL_SAT_MODE; ?>" /></td>
			</tr>
		</table>

		<table>
			<tr>
				<td>IOTA</td>
				<td><input type="text" name="iota_ref" value="<?php echo $COL_IOTA; ?>" /></td>
			</tr>

			<tr>
				<td>Country</td>
				<td><input type="text" name="country" value="<?php echo $COL_COUNTRY; ?>" /></td>
			</tr>

		</table>

		<h3>QSLing</h3>

		<ul class="tabs">
		  <li class="active"><a href="#paper">Paper</a></li>
		  <li><a href="#eqsl">eQSL</a></li>
		  <li><a href="#lotw">LoTW</a></li>
		</ul>

		<div class="pill-content">
		  <div class="active" id="paper">
		<table>
			<tr>
				<td>Sent</td>
				<td><select name="qsl_sent">
					<option value="N" <?php if($COL_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
					<option value="Y" <?php if($COL_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
					<option value="R" <?php if($COL_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
					<option value="Q" <?php if($COL_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
					<option value="I" <?php if($COL_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				</select></td>
				<td>Recv</td>
				<td><select name="qsl_recv">
					<option value="N" <?php if($COL_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
					<option value="Y" <?php if($COL_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
					<option value="R" <?php if($COL_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
					<option value="Q" <?php if($COL_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
					<option value="I" <?php if($COL_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
				</select></td>
			</tr>
			<tr>
				<td></td>

				<!-- <?php if($COL_QSL_SENT_VIA == "") { echo "selected=\"selected\""; } ?> -->

				<td><select name="qsl_sent_method">
					<option value="" <?php if($COL_QSL_SENT_VIA == "") { echo "selected=\"selected\""; } ?>>Method</option>
					<option value="D" <?php if($COL_QSL_SENT_VIA == "D") { echo "selected=\"selected\""; } ?>>Direct</option>
					<option value="B" <?php if($COL_QSL_SENT_VIA == "B") { echo "selected=\"selected\""; } ?>>Bureau</option>
					<option value="E" <?php if($COL_QSL_SENT_VIA == "E") { echo "selected=\"selected\""; } ?>>Electronic</option>
					<option value="M" <?php if($COL_QSL_SENT_VIA == "M") { echo "selected=\"selected\""; } ?>>Manager</option>
				</select></td>
				<td></td>
				<td><select name="qsl_recv_method">
					<option value="" <?php if($COL_QSL_RCVD_VIA == "") { echo "selected=\"selected\""; } ?>>Method</option>
					<option value="D" <?php if($COL_QSL_RCVD_VIA == "D") { echo "selected=\"selected\""; } ?>>Direct</option>
					<option value="B" <?php if($COL_QSL_RCVD_VIA == "B") { echo "selected=\"selected\""; } ?>>Bureau</option>
					<option value="E" <?php if($COL_QSL_RCVD_VIA == "E") { echo "selected=\"selected\""; } ?>>Electronic</option>
					<option value="M" <?php if($COL_QSL_RCVD_VIA == "M") { echo "selected=\"selected\""; } ?>>Manager</option>
				</select></td>
			</tr>
		</table>

		  </div>
		  <div id="eqsl">
			<table>
				<tr>
					<td>Sent</td>
					<td><select name="eqsl_sent">
						<option value="N" <?php if($COL_EQSL_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_EQSL_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_EQSL_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="Q" <?php if($COL_EQSL_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
						<option value="I" <?php if($COL_EQSL_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
					</select></td>
					<td>Recv</td>
					<td><select name="eqsl_recv">
						<option value="N" <?php if($COL_EQSL_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_EQSL_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_EQSL_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="I" <?php if($COL_EQSL_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
						<option value="V" <?php if($COL_EQSL_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
					</select></td>
				</tr>
			</table>
		  </div>
		  <div id="lotw">
			<table>
				<tr>
					<td>Sent</td>
					<td><select name="lotw_sent">
						<option value="N" <?php if($COL_LOTW_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_LOTW_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_LOTW_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="Q" <?php if($COL_LOTW_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
						<option value="I" <?php if($COL_LOTW_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
					</select></td>
					<td>Recv</td>
					<td><select name="lotw_recv">
						<option value="N" <?php if($COL_LOTW_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_LOTW_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_LOTW_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="I" <?php if($COL_LOTW_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
						<option value="V" <?php if($COL_LOTW_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
					</select></td>
				</tr>
			</table>
		  </div>
		</div>

		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />

		<div class="actions">
			<input type="submit" class="btn primary" value="Save changes" onclick="closeME();">
		</div>

	</form>
	</body>
</html>

</div>
