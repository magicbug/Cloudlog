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
			<p></p>
		</div>

		<?php echo validation_errors(); ?>
		<form method="post" action="<?php echo site_url('qso/edit'); ?>" name="qsos">
<div class="card">
	<div class="card-header"> 
		<nav class="card-header-tabs">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link active" id="nav-qso-tab" data-toggle="tab" href="#nav-qso" role="tab" aria-controls="nav-qso" aria-selected="true">QSO</a>
				<a class="nav-item nav-link" id="nav-satellites-tab" data-toggle="tab" href="#nav-satellites" role="tab" aria-controls="nav-awards" aria-selected="true">Sats</a>
				<a class="nav-item nav-link" id="nav-awards-tab" data-toggle="tab" href="#nav-awards" role="tab" aria-controls="nav-awards" aria-selected="true">Awards</a>
				<a class="nav-item nav-link" id="nav-qsl-tab" data-toggle="tab" href="#nav-qsl" role="tab" aria-controls="nav-qsl" aria-selected="false">QSL</a>
				<a class="nav-item nav-link" id="nav-station-tab" data-toggle="tab" href="#nav-station" role="tab" aria-controls="nav-station" aria-selected="false">Station</a>
			</div>
		</nav>

	</div>

	<div class="card-body">

		<div class="tab-content" id="nav-tabContent">
			<div class="tab-pane fade show active" id="nav-qso" role="tabpanel" aria-labelledby="nav-qso-tab">
				
				<div class="form-group">
                  <label for="start_date">Start Date/Time</label>
                  <input type="text" class="form-control form-control-sm input_date" name="time_on" id="time_on" value="<?php echo $COL_TIME_ON; ?>">
                </div>

                <div class="form-group">
                  <label for="start_time">End Date/Time</label>
                  <input type="text" class="form-control form-control-sm input_time" name="time_off" id="time_off" value="<?php echo $COL_TIME_OFF; ?>">
                </div>

	            <div class="form-group">
	            	<label for="callsign">Callsign</label>
	                <input type="text" class="form-control" id="callsign" name="callsign" value="<?php echo $COL_CALL; ?>">
	            </div>

	            <?php if($COL_FREQ) { ?>
	            <div class="form-group">
	            	<label for="freq">Frequency</label>
	                <input type="text" class="form-control" id="freq" name="freq" value="<?php echo $COL_FREQ; ?>">
	            </div>
	            <?php } ?>

	            <div class="form-group">
	            	<label for="freq">Mode</label>
	                <input type="text" class="form-control" id="mode" name="mode" value="<?php echo $COL_MODE; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="freq">Band</label>
	                <input type="text" class="form-control" id="band" name="band" value="<?php echo $COL_BAND; ?>">
	            </div>

		        <div class="form-row">
	                <div class="form-group col-md-6">
	                  <label for="rst_sent">RST (S)</label>
	                  <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="<?php echo $COL_RST_SENT; ?>">
	                </div>

	                <div class="form-group col-md-6">
	                  <label for="rst_recv">RST (R)</label>
	                  <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="<?php echo $COL_RST_RCVD; ?>">
	                </div>
	            </div>

	            <?php if ($COL_STX_STRING) { ?>
	            <div class="form-group">
	            	<label for="stx_string">TX Serial</label>
	                <input type="text" class="form-control" id="band" name="stx_string" value="<?php echo $COL_STX_STRING; ?>">
	            </div>
	        	<?php } ?>

	        	<?php if ($COL_SRX_STRING) { ?>
	            <div class="form-group">
	            	<label for="srx_string">RX Serial</label>
	                <input type="text" class="form-control" id="srx_string" name="srx_string" value="<?php echo $COL_SRX_STRING; ?>">
	            </div>
	        	<?php } ?>

	        	<div class="form-group">
	            	<label for="locator">Gridsquare</label>
	                <input type="text" class="form-control" id="locator" name="locator" value="<?php echo $COL_GRIDSQUARE; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="vucc_grids">VUCC Gridsquare</label>
	                <input type="text" class="form-control" id="vucc_grids" name="vucc_grids" value="<?php echo $COL_VUCC_GRIDS; ?>">
	                <p>Used for VUCC MultiGrids</p>
	            </div>

	            <div class="form-group">
	            	<label for="name">Name</label>
	                <input type="text" class="form-control" id="name" name="name" value="<?php echo $COL_NAME; ?>">
	            </div>

				<div class="form-group">
	            	<label for="qth">QTH</label>
	                <input type="text" class="form-control" id="qth" name="qth" value="<?php echo $COL_QTH; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="comment">Comment</label>
	                <input type="text" class="form-control" id="comment" name="comment" value="<?php echo $COL_COMMENT; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="prop_mode">Propagation Mode</label>
	                <input type="text" class="form-control" id="prop_mode" name="prop_mode" value="<?php echo $COL_PROP_MODE; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="country">Country</label>
	                <input type="text" class="form-control" id="country" name="country" value="<?php echo $COL_COUNTRY; ?>">
	            </div>

			</div>

			<!-- Satellite Panel Contents -->
			<div class="tab-pane fade" id="nav-satellites" role="tabpanel" aria-labelledby="nav-satellites-tab">
	            <div class="form-group">
	                <label for="sat_name">Sat Name</label>
	                <input type="text" class="form-control form-control-sm" name="sat_name" id="sat_name" value="<?php echo $COL_SAT_NAME; ?>">
	            </div>

	            <div class="form-group">
	                <label for="sat_mode">Sat Mode</label>
	            	<input type="text" class="form-control form-control-sm" name="sat_mode" id="sat_mode" value="<?php echo $COL_SAT_MODE; ?>">
	            </div>
			</div>

			<!-- Awards Panel Contents -->
			<div class="tab-pane fade" id="nav-awards" role="tabpanel" aria-labelledby="nav-awards-tab">
				<div class="form-group">
	            	<label for="iota_ref">IOTA</label>
	                <input type="text" class="form-control" id="iota_ref" name="iota_ref" value="<?php echo $COL_IOTA; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="sota_ref">SOTA</label>
	                <input type="text" class="form-control" id="sota_ref" name="sota_ref" value="<?php echo $COL_SOTA_REF; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="darc_dok">DOK</label>
	                <input type="text" class="form-control" id="darc_dok" name="darc_dok" value="<?php echo $COL_DARC_DOK; ?>">
	            </div>
			</div>

			<!-- QSL Panel Contents -->
			<div class="tab-pane fade" id="nav-qsl" role="tabpanel" aria-labelledby="nav-qsl-tab">
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

			</div>

			<!-- Station Panel Contents -->
			<div class="tab-pane fade" id="nav-station" role="tabpanel" aria-labelledby="nav-station-tab">

		<?php
				$CI =& get_instance();
				$CI->load->model('stations');
				$my_stations = $CI->stations->all();
		 ?>

	            <div class="form-group">
	              <label for="inputStationProfile">Change Station Profile</label>
	              <select id="stationProfile" class="custom-select" name="station_profile">
	                <?php foreach ($my_stations->result() as $stationrow) { ?>
	                <option value="<?php echo $stationrow->station_id; ?>" <?php if($station_id == $stationrow->station_id) { echo "selected=\"selected\""; } ?>><?php echo $stationrow->station_profile_name; ?></option>
	                <?php } ?>
	              </select>
	            </div>

	             <div class="form-group">
				    <label for="operatorCallsign">Operator Callsign</label>
				    <input type="text" id="operatorCallsign" class="form-control" name="operator_callsign" value="<?php echo $COL_OPERATOR; ?>" />
				</div>


			</div>

		</div>

		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />

		<div class="actions">
			<input type="submit" class="btn btn-success" value="Save changes" onclick="closeME();">
			<a class="btn btn-danger float-right" href="<?php echo site_url('qso/delete'); ?>/<?php echo $COL_PRIMARY_KEY; ?>" onclick="return confirm('Are you sure you want the delete QSO?');"><i class="fas fa-trash-alt"></i> Delete QSO</a>
		</div>
	</div>
</div>

	</form>
	</body>
</html>

</div>
