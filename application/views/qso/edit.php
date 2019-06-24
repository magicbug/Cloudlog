<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fontawesome/css/all.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/general.css">

  <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

	</head>

	<body class="qso-edit-box">

		<!-- Option to Delete QSO -->
		<div style="float: right; padding-right: 60px; padding-top: 30px;">
			<p><a class="btn btn-danger" href="<?php echo site_url('qso/delete'); ?>/<?php echo $COL_PRIMARY_KEY; ?>" onclick="return confirm('Are you sure you want the delete QSO?');"><i class="fas fa-trash-alt"></i> Delete QSO</a></p>
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
				<td>DOK</td>
				<td><input type="text" name="darc_dok" value="<?php echo $COL_DARC_DOK; ?>" /></td>
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
				<td>SOTA</td>
				<td><input type="text" name="sota_ref" value="<?php echo $COL_SOTA_REF; ?>" /></td>
			</tr>

			<tr>
				<td>Country</td>
				<td><input type="text" name="country" value="<?php echo $COL_COUNTRY; ?>" /></td>
			</tr>

		</table>

		<h3>QSLing</h3>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">QSL Card</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">eQSL</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">LOTW</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label">Sent</label>
              <div class="col-sm-9">
                <select class="custom-select" name="qsl_sent">
					<option value="N" <?php if($COL_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
					<option value="Y" <?php if($COL_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
					<option value="R" <?php if($COL_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
					<option value="Q" <?php if($COL_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
					<option value="I" <?php if($COL_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				</select>
              </div>
            </div>

            <div class="form-group row">
              <label for="sent-method" class="col-sm-3 col-form-label">Sent Method</label>
              <div class="col-sm-9">
                <select class="custom-select" name="qsl_sent_method">
					<option value="" <?php if($COL_QSL_SENT_VIA == "") { echo "selected=\"selected\""; } ?>>Method</option>
					<option value="D" <?php if($COL_QSL_SENT_VIA == "D") { echo "selected=\"selected\""; } ?>>Direct</option>
					<option value="B" <?php if($COL_QSL_SENT_VIA == "B") { echo "selected=\"selected\""; } ?>>Bureau</option>
					<option value="E" <?php if($COL_QSL_SENT_VIA == "E") { echo "selected=\"selected\""; } ?>>Electronic</option>
					<option value="M" <?php if($COL_QSL_SENT_VIA == "M") { echo "selected=\"selected\""; } ?>>Manager</option>
				</select>
              </div>
            </div>

            <div class="form-group row">
              <label for="qsl-via" class="col-sm-2 col-form-label">Sent Via</label>
              <div class="col-sm-10">
                <input type="text" id="qsl-via" class="form-control" name="qsl_via_callsign" value="<?php echo $COL_QSL_VIA; ?>" />
              </div>
            </div>

            <div class="form-group row">
              <label for="sent-method" class="col-sm-3 col-form-label">Received</label>
              <div class="col-sm-9">
                <select class="custom-select" name="qsl_recv">
					<option value="N" <?php if($COL_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
					<option value="Y" <?php if($COL_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
					<option value="R" <?php if($COL_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
					<option value="Q" <?php if($COL_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
					<option value="I" <?php if($COL_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
				</select>
			  </div>
            </div>

            <div class="form-group row">
              <label for="sent-method" class="col-sm-3 col-form-label">Received Method</label>
              <div class="col-sm-9">
                <select class="custom-select" name="qsl_recv_method">
					<option value="" <?php if($COL_QSL_RCVD_VIA == "") { echo "selected=\"selected\""; } ?>>Method</option>
					<option value="D" <?php if($COL_QSL_RCVD_VIA == "D") { echo "selected=\"selected\""; } ?>>Direct</option>
					<option value="B" <?php if($COL_QSL_RCVD_VIA == "B") { echo "selected=\"selected\""; } ?>>Bureau</option>
					<option value="E" <?php if($COL_QSL_RCVD_VIA == "E") { echo "selected=\"selected\""; } ?>>Electronic</option>
					<option value="M" <?php if($COL_QSL_RCVD_VIA == "M") { echo "selected=\"selected\""; } ?>>Manager</option>
				</select>
			  </div>
            </div>
  </div>

  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label">Sent</label>
              <div class="col-sm-9">
              <select class="custom-select" name="eqsl_sent">
						<option value="N" <?php if($COL_EQSL_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_EQSL_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_EQSL_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="Q" <?php if($COL_EQSL_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
						<option value="I" <?php if($COL_EQSL_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
					</select>
					</div>
            </div>

            <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label">Received</label>
              <div class="col-sm-9">
              <select class="custom-select" name="eqsl_recv">
						<option value="N" <?php if($COL_EQSL_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_EQSL_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_EQSL_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="I" <?php if($COL_EQSL_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
						<option value="V" <?php if($COL_EQSL_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
					</select></div>
            </div>  	
  </div>
  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
  	        <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label">Sent</label>
              <div class="col-sm-9">
              <select class="custom-select" name="lotw_sent">
						<option value="N" <?php if($COL_LOTW_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_LOTW_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_LOTW_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="Q" <?php if($COL_LOTW_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
						<option value="I" <?php if($COL_LOTW_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
					</select></div>
            </div>

            <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label">Received</label>
              <div class="col-sm-9">
              <select class="custom-select" name="lotw_recv">
						<option value="N" <?php if($COL_LOTW_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
						<option value="Y" <?php if($COL_LOTW_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
						<option value="R" <?php if($COL_LOTW_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
						<option value="I" <?php if($COL_LOTW_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
						<option value="V" <?php if($COL_LOTW_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
					</select></div>
            </div>
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
