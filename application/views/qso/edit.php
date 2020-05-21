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

	<body class="container-fluid qso-edit-box">

<div class="container-fluid">
	<div class="row">
		<div class="col">
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
                <div class="form-row">
                    <div class="form-group col-sm-6">
                      <label for="start_date">Start Date/Time</label>
                      <input type="text" class="form-control form-control-sm input_date" name="time_on" id="time_on" value="<?php echo $qso->COL_TIME_ON; ?>">
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="start_time">End Date/Time</label>
                      <input type="text" class="form-control form-control-sm input_time" name="time_off" id="time_off" value="<?php echo $qso->COL_TIME_OFF; ?>">
                    </div>
                </div>

	            <div class="form-group">
	            	<label for="callsign">Callsign</label>
	                <input type="text" class="form-control" id="callsign" name="callsign" value="<?php echo $qso->COL_CALL; ?>">
	            </div>

	            <?php if($qso->COL_FREQ) { ?>
	            <div class="form-group">
	            	<label for="freq">Frequency</label>
	                <input type="text" class="form-control" id="freq" name="freq" value="<?php echo $qso->COL_FREQ; ?>">
	            </div>
	            <?php } ?>
                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="freq">Mode</label>
                            <select id="mode" class="form-control mode form-control-sm" name="mode">
                  <?php
                      foreach($modes->result() as $mode){
                        printf("<option value=\"%s\" %s>%s</option>", $mode->mode, $this->session->userdata('mode')==$mode->mode?"selected=\"selected\"":"",$mode->mode);
                      }
                  ?>
                            </select>
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="freq">Band</label>
                        <select id="band" class="form-control form-control-sm" name="band">
                            <optgroup label="HF">
                                <option value="160m" <?php if($qso->COL_BAND == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
                                <option value="80m" <?php if($qso->COL_BAND == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
                                <option value="60m" <?php if($qso->COL_BAND == "60m") { echo "selected=\"selected\""; } ?>>60m</option>
                                <option value="40m" <?php if($qso->COL_BAND == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
                                <option value="30m" <?php if($qso->COL_BAND == "30m") { echo "selected=\"selected\""; } ?>>30m</option>
                                <option value="20m" <?php if($qso->COL_BAND == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
                                <option value="17m" <?php if($qso->COL_BAND == "17m") { echo "selected=\"selected\""; } ?>>17m</option>
                                <option value="15m" <?php if($qso->COL_BAND == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
                                <option value="12m" <?php if($qso->COL_BAND == "12m") { echo "selected=\"selected\""; } ?>>12m</option>
                                <option value="10m" <?php if($qso->COL_BAND == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
                            </optgroup>

                            <optgroup label="VHF">
                                <option value="6m" <?php if($qso->COL_BAND == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
                                <option value="4m" <?php if($qso->COL_BAND == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
                                <option value="2m" <?php if($qso->COL_BAND == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
                            </optgroup>

                            <optgroup label="UHF">
                                <option value="70cm" <?php if($qso->COL_BAND == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
                                <option value="23cm" <?php if($qso->COL_BAND == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
                                <option value="13cm" <?php if($qso->COL_BAND == "13cm") { echo "selected=\"selected\""; } ?>>13cm</option>
                                <option value="9cm" <?php if($qso->COL_BAND == "9cm") { echo "selected=\"selected\""; } ?>>9cm</option>
                            </optgroup>

                            <optgroup label="Microwave">
                                <option value="3cm" <?php if($qso->COL_BAND == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                    <div class="form-row">
                        <div class="form-group col-sm-6">
                          <label for="rst_sent">RST (S)</label>
                          <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="<?php echo $qso->COL_RST_SENT; ?>">
                        </div>

                        <div class="form-group col-sm-6">
                          <label for="rst_recv">RST (R)</label>
                          <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="<?php echo $qso->COL_RST_RCVD; ?>">
                        </div>
                    </div>

	            <?php if ($qso->COL_STX_STRING) { ?>
	            <div class="form-group">
	            	<label for="stx_string">TX Serial</label>
	                <input type="text" class="form-control" id="band" name="stx_string" value="<?php echo $qso->COL_STX_STRING; ?>">
	            </div>
	        	<?php } ?>

	        	<?php if ($qso->COL_SRX_STRING) { ?>
	            <div class="form-group">
	            	<label for="srx_string">RX Serial</label>
	                <input type="text" class="form-control" id="srx_string" name="srx_string" value="<?php echo $qso->COL_SRX_STRING; ?>">
	            </div>
	        	<?php } ?>
                <div class="form-row">
                    <div class="form-group col-sm-6">
                        <label for="locator">Gridsquare</label>
                        <input type="text" class="form-control" id="locator" name="locator" value="<?php echo $qso->COL_GRIDSQUARE; ?>">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="vucc_grids">VUCC Gridsquare</label>
                        <input type="text" class="form-control" id="vucc_grids" name="vucc_grids" value="<?php echo $qso->COL_VUCC_GRIDS; ?>">
                        <p>Used for VUCC MultiGrids</p>
                    </div>
                </div>

	            <div class="form-group">
	            	<label for="name">Name</label>
	                <input type="text" class="form-control" id="name" name="name" value="<?php echo $qso->COL_NAME; ?>">
	            </div>

				<div class="form-group">
	            	<label for="qth">QTH</label>
	                <input type="text" class="form-control" id="qth" name="qth" value="<?php echo $qso->COL_QTH; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="comment">Comment</label>
	                <input type="text" class="form-control" id="comment" name="comment" value="<?php echo $qso->COL_COMMENT; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="prop_mode">Propagation Mode</label>
                    <select class="custom-select" id="prop_mode" name="prop_mode">
                        <option value="" <?php if($qso->COL_PROP_MODE == "") { echo "selected=\"selected\""; } ?>></option>
                        <option value="AUR" <?php if($qso->COL_PROP_MODE == "AUR") { echo "selected=\"selected\""; } ?>>Aurora</option>
                        <option value="AUE" <?php if($qso->COL_PROP_MODE == "AUE") { echo "selected=\"selected\""; } ?>>Aurora-E</option>
                        <option value="BS" <?php if($qso->COL_PROP_MODE == "BS") { echo "selected=\"selected\""; } ?>>Back scatter</option>
                        <option value="ECH" <?php if($qso->COL_PROP_MODE == "ECH") { echo "selected=\"selected\""; } ?>>EchoLink</option>
                        <option value="EME" <?php if($qso->COL_PROP_MODE == "EME") { echo "selected=\"selected\""; } ?>>Earth-Moon-Earth</option>
                        <option value="ES" <?php if($qso->COL_PROP_MODE == "ES") { echo "selected=\"selected\""; } ?>>Sporadic E</option>
                        <option value="FAI" <?php if($qso->COL_PROP_MODE == "FAI") { echo "selected=\"selected\""; } ?>>Field Aligned Irregularities</option>
                        <option value="F2" <?php if($qso->COL_PROP_MODE == "F2") { echo "selected=\"selected\""; } ?>>F2 Reflection</option>
                        <option value="INTERNET" <?php if($qso->COL_PROP_MODE == "INTERNET") { echo "selected=\"selected\""; } ?>>Internet-assisted</option>
                        <option value="ION" <?php if($qso->COL_PROP_MODE == "ION") { echo "selected=\"selected\""; } ?>>Ionoscatter</option>
                        <option value="IRL" <?php if($qso->COL_PROP_MODE == "IRL") { echo "selected=\"selected\""; } ?>>IRLP</option>
                        <option value="MS" <?php if($qso->COL_PROP_MODE == "MS") { echo "selected=\"selected\""; } ?>>Meteor scatter</option>
                        <option value="RPT" <?php if($qso->COL_PROP_MODE == "RPT") { echo "selected=\"selected\""; } ?>>Terrestrial or atmospheric repeater or transponder</option>
                        <option value="RS" <?php if($qso->COL_PROP_MODE == "RS") { echo "selected=\"selected\""; } ?>>Rain scatter</option>
                        <option value="SAT" <?php if($qso->COL_PROP_MODE == "SAT") { echo "selected=\"selected\""; } ?>>Satellite</option>
                        <option value="TEP" <?php if($qso->COL_PROP_MODE == "TEP") { echo "selected=\"selected\""; } ?>>Trans-equatorial</option>
                        <option value="TR" <?php if($qso->COL_PROP_MODE == "TR") { echo "selected=\"selected\""; } ?>>Tropospheric ducting</option>
                    </select>
	            </div>

	                <input type="hidden" class="form-control" id="country" name="country" value="<?php echo $qso->COL_COUNTRY; ?>">

                <div class="form-group">
                    <label for="dxcc_id">DXCC</label>
                    <select class="custom-select" id="dxcc_id" name="dxcc_id" required>

                        <?php
                        foreach($dxcc as $d){
                            echo '<option value=' . $d->adif;
                            if ($qso->COL_DXCC == $d->adif) {
                                echo " selected=\"selected\"";
                            }
                            echo '>' . $d->prefix . ' - ' . $d->name . '</option>';
                        }
                        ?>

                    </select>
                </div>

			</div>

			<!-- Satellite Panel Contents -->
			<div class="tab-pane fade" id="nav-satellites" role="tabpanel" aria-labelledby="nav-satellites-tab">
	            <div class="form-group">
	                <label for="sat_name">Sat Name</label>
	                <input type="text" class="form-control form-control-sm" name="sat_name" id="sat_name" value="<?php echo $qso->COL_SAT_NAME; ?>">
	            </div>

	            <div class="form-group">
	                <label for="sat_mode">Sat Mode</label>
	            	<input type="text" class="form-control form-control-sm" name="sat_mode" id="sat_mode" value="<?php echo $qso->COL_SAT_MODE; ?>">
	            </div>
			</div>

			<!-- Awards Panel Contents -->
			<div class="tab-pane fade" id="nav-awards" role="tabpanel" aria-labelledby="nav-awards-tab">

                <div class="form-group">
                    <label for="cqz">CQ Zone</label>
                    <select class="custom-select" id="cqz" name="cqz" required>
                        <?php
                        for ($i = 1; $i<=40; $i++) {
                            echo '<option value='. $i;
                            if ($qso->COL_CQZ == $i) {
                                echo " selected=\"selected\"";
                            }
                            echo '>'. $i .'</option>';
                        }
                        ?>
                    </select>
                </div>


				<div class="form-group">
	            	<label for="usa_state">USA State</label>
                    <select class="custom-select" id="input_usa_state" name="usa_state">
                        <option value=""></option>
                        <option value="AL" <?php if($qso->COL_STATE == "AL") { echo "selected=\"selected\""; } ?>>Alabama (AL)</option>
                        <option value="AK" <?php if($qso->COL_STATE == "AK") { echo "selected=\"selected\""; } ?>>Alaska (AK)</option>
                        <option value="AZ" <?php if($qso->COL_STATE == "AZ") { echo "selected=\"selected\""; } ?>>Arizona (AZ)</option>
                        <option value="AR" <?php if($qso->COL_STATE == "AR") { echo "selected=\"selected\""; } ?>>Arkansas (AR)</option>
                        <option value="CA" <?php if($qso->COL_STATE == "CA") { echo "selected=\"selected\""; } ?>>California (CA)</option>
                        <option value="CO" <?php if($qso->COL_STATE == "CO") { echo "selected=\"selected\""; } ?>>Colorado (CO)</option>
                        <option value="CT" <?php if($qso->COL_STATE == "CT") { echo "selected=\"selected\""; } ?>>Connecticut (CT)</option>
                        <option value="DE" <?php if($qso->COL_STATE == "DE") { echo "selected=\"selected\""; } ?>>Delaware (DE)</option>
                        <option value="DC" <?php if($qso->COL_STATE == "DC") { echo "selected=\"selected\""; } ?>>District Of Columbia (DC)</option>
                        <option value="FL" <?php if($qso->COL_STATE == "FL") { echo "selected=\"selected\""; } ?>>Florida (FL)</option>
                        <option value="GA" <?php if($qso->COL_STATE == "GA") { echo "selected=\"selected\""; } ?>>Georgia (GA)</option>
                        <option value="HI" <?php if($qso->COL_STATE == "HI") { echo "selected=\"selected\""; } ?>>Hawaii (HI)</option>
                        <option value="ID" <?php if($qso->COL_STATE == "ID") { echo "selected=\"selected\""; } ?>>Idaho (ID)</option>
                        <option value="IL" <?php if($qso->COL_STATE == "IL") { echo "selected=\"selected\""; } ?>>Illinois (IL)</option>
                        <option value="IN" <?php if($qso->COL_STATE == "IN") { echo "selected=\"selected\""; } ?>>Indiana (IN)</option>
                        <option value="IA" <?php if($qso->COL_STATE == "IA") { echo "selected=\"selected\""; } ?>>Iowa (IA)</option>
                        <option value="KS" <?php if($qso->COL_STATE == "KS") { echo "selected=\"selected\""; } ?>>Kansas (KS)</option>
                        <option value="KY" <?php if($qso->COL_STATE == "KY") { echo "selected=\"selected\""; } ?>>Kentucky (KY)</option>
                        <option value="LA" <?php if($qso->COL_STATE == "LA") { echo "selected=\"selected\""; } ?>>Louisiana (LA)</option>
                        <option value="ME" <?php if($qso->COL_STATE == "ME") { echo "selected=\"selected\""; } ?>>Maine (ME)</option>
                        <option value="MD" <?php if($qso->COL_STATE == "MD") { echo "selected=\"selected\""; } ?>>Maryland (MD)</option>
                        <option value="MA" <?php if($qso->COL_STATE == "MA") { echo "selected=\"selected\""; } ?>>Massachusetts (MA)</option>
                        <option value="MI" <?php if($qso->COL_STATE == "MI") { echo "selected=\"selected\""; } ?>>Michigan (MI)</option>
                        <option value="MN" <?php if($qso->COL_STATE == "MN") { echo "selected=\"selected\""; } ?>>Minnesota (MN)</option>
                        <option value="MS" <?php if($qso->COL_STATE == "MS") { echo "selected=\"selected\""; } ?>>Mississippi (MS)</option>
                        <option value="MO" <?php if($qso->COL_STATE == "MO") { echo "selected=\"selected\""; } ?>>Missouri (MO)</option>
                        <option value="MT" <?php if($qso->COL_STATE == "MT") { echo "selected=\"selected\""; } ?>>Montana (MT)</option>
                        <option value="NE" <?php if($qso->COL_STATE == "ME") { echo "selected=\"selected\""; } ?>>Nebraska (NE)</option>
                        <option value="NV" <?php if($qso->COL_STATE == "NV") { echo "selected=\"selected\""; } ?>>Nevada (NV)</option>
                        <option value="NH" <?php if($qso->COL_STATE == "NH") { echo "selected=\"selected\""; } ?>>New Hampshire (NH)</option>
                        <option value="NJ" <?php if($qso->COL_STATE == "NJ") { echo "selected=\"selected\""; } ?>>New Jersey (NJ)</option>
                        <option value="NM" <?php if($qso->COL_STATE == "NM") { echo "selected=\"selected\""; } ?>>New Mexico (NM)</option>
                        <option value="NY" <?php if($qso->COL_STATE == "NY") { echo "selected=\"selected\""; } ?>>New York (NY)</option>
                        <option value="NC" <?php if($qso->COL_STATE == "NC") { echo "selected=\"selected\""; } ?>>North Carolina (NC)</option>
                        <option value="ND" <?php if($qso->COL_STATE == "ND") { echo "selected=\"selected\""; } ?>>North Dakota (ND)</option>
                        <option value="OH" <?php if($qso->COL_STATE == "OH") { echo "selected=\"selected\""; } ?>>Ohio (OH)</option>
                        <option value="OK" <?php if($qso->COL_STATE == "OK") { echo "selected=\"selected\""; } ?>>Oklahoma (OK)</option>
                        <option value="OR" <?php if($qso->COL_STATE == "OR") { echo "selected=\"selected\""; } ?>>Oregon (OR)</option>
                        <option value="PA" <?php if($qso->COL_STATE == "PA") { echo "selected=\"selected\""; } ?>>Pennsylvania (PA)</option>
                        <option value="RI" <?php if($qso->COL_STATE == "RI") { echo "selected=\"selected\""; } ?>>Rhode Island (RI)</option>
                        <option value="SC" <?php if($qso->COL_STATE == "SC") { echo "selected=\"selected\""; } ?>>South Carolina (SC)</option>
                        <option value="SD" <?php if($qso->COL_STATE == "SD") { echo "selected=\"selected\""; } ?>>South Dakota (SD)</option>
                        <option value="TN" <?php if($qso->COL_STATE == "TN") { echo "selected=\"selected\""; } ?>>Tennessee (TN)</option>
                        <option value="TX" <?php if($qso->COL_STATE == "TX") { echo "selected=\"selected\""; } ?>>Texas (TX)</option>
                        <option value="UT" <?php if($qso->COL_STATE == "UT") { echo "selected=\"selected\""; } ?>>Utah (UT)</option>
                        <option value="VT" <?php if($qso->COL_STATE == "VT") { echo "selected=\"selected\""; } ?>>Vermont (VT)</option>
                        <option value="VA" <?php if($qso->COL_STATE == "VA") { echo "selected=\"selected\""; } ?>>Virginia (VA)</option>
                        <option value="WA" <?php if($qso->COL_STATE == "WA") { echo "selected=\"selected\""; } ?>>Washington (WA)</option>
                        <option value="WV" <?php if($qso->COL_STATE == "WV") { echo "selected=\"selected\""; } ?>>West Virginia (WV)</option>
                        <option value="WI" <?php if($qso->COL_STATE == "WI") { echo "selected=\"selected\""; } ?>>Wisconsin (WI)</option>
                        <option value="WY" <?php if($qso->COL_STATE == "WY") { echo "selected=\"selected\""; } ?>>Wyoming (WY)</option>
                    </select>
	            </div>

				<div class="form-group">
	            	<label for="iota_ref">IOTA</label>
                    <select class="custom-select" id="iota_ref" name="iota_ref">
                        <option value =""></option>

                        <?php
                        foreach($iota as $i){
                            echo '<option value=' . $i->tag;
                                if ($qso->COL_IOTA == $i->tag) {
                                    echo " selected=\"selected\"";
                                }
                            echo '>' . $i->tag . ' - ' . $i->name . '</option>';
                        }
                        ?>

                    </select>
	            </div>

	            <div class="form-group">
	            	<label for="sota_ref">SOTA</label>
	                <input type="text" class="form-control" id="sota_ref" name="sota_ref" value="<?php echo $qso->COL_SOTA_REF; ?>">
	            </div>

	            <div class="form-group">
	            	<label for="darc_dok">DOK</label>
	                <input type="text" class="form-control" id="darc_dok" name="darc_dok" value="<?php echo $qso->COL_DARC_DOK; ?>">
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
				          <option value="N" <?php if($qso->COL_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
				          <option value="Y" <?php if($qso->COL_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
				          <option value="R" <?php if($qso->COL_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
				          <option value="Q" <?php if($qso->COL_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
				          <option value="I" <?php if($qso->COL_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				        </select>
				              </div>
				            </div>

				            <div class="form-group row">
				              <label for="sent-method" class="col-sm-3 col-form-label">Sent Method</label>
				              <div class="col-sm-9">
				                <select class="custom-select" name="qsl_sent_method">
				          <option value="" <?php if($qso->COL_QSL_SENT_VIA == "") { echo "selected=\"selected\""; } ?>>Method</option>
				          <option value="D" <?php if($qso->COL_QSL_SENT_VIA == "D") { echo "selected=\"selected\""; } ?>>Direct</option>
				          <option value="B" <?php if($qso->COL_QSL_SENT_VIA == "B") { echo "selected=\"selected\""; } ?>>Bureau</option>
				          <option value="E" <?php if($qso->COL_QSL_SENT_VIA == "E") { echo "selected=\"selected\""; } ?>>Electronic</option>
				          <option value="M" <?php if($qso->COL_QSL_SENT_VIA == "M") { echo "selected=\"selected\""; } ?>>Manager</option>
				        </select>
				              </div>
				            </div>

				            <div class="form-group row">
				              <label for="qsl-via" class="col-sm-2 col-form-label">Sent Via</label>
				              <div class="col-sm-10">
				                <input type="text" id="qsl-via" class="form-control" name="qsl_via_callsign" value="<?php echo $qso->COL_QSL_VIA; ?>" />
				              </div>
				            </div>

				            <div class="form-group row">
				              <label for="sent-method" class="col-sm-3 col-form-label">Received</label>
				              <div class="col-sm-9">
				                <select class="custom-select" name="qsl_recv">
				          <option value="N" <?php if($qso->COL_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
				          <option value="Y" <?php if($qso->COL_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
				          <option value="R" <?php if($qso->COL_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
				          <option value="Q" <?php if($qso->COL_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				          <option value="I" <?php if($qso->COL_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
				        </select>
				        </div>
				            </div>

				            <div class="form-group row">
				              <label for="sent-method" class="col-sm-3 col-form-label">Received Method</label>
				              <div class="col-sm-9">
				                <select class="custom-select" name="qsl_recv_method">
				          <option value="" <?php if($qso->COL_QSL_RCVD_VIA == "") { echo "selected=\"selected\""; } ?>>Method</option>
				          <option value="D" <?php if($qso->COL_QSL_RCVD_VIA == "D") { echo "selected=\"selected\""; } ?>>Direct</option>
				          <option value="B" <?php if($qso->COL_QSL_RCVD_VIA == "B") { echo "selected=\"selected\""; } ?>>Bureau</option>
				          <option value="E" <?php if($qso->COL_QSL_RCVD_VIA == "E") { echo "selected=\"selected\""; } ?>>Electronic</option>
				          <option value="M" <?php if($qso->COL_QSL_RCVD_VIA == "M") { echo "selected=\"selected\""; } ?>>Manager</option>
				        </select>
				        </div>
				            </div>
				  </div>

				  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				            <div class="form-group row">
				              <label for="sent" class="col-sm-3 col-form-label">Sent</label>
				              <div class="col-sm-9">
				              <select class="custom-select" name="eqsl_sent">
				            <option value="N" <?php if($qso->COL_EQSL_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
				            <option value="Y" <?php if($qso->COL_EQSL_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
				            <option value="R" <?php if($qso->COL_EQSL_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
				            <option value="Q" <?php if($qso->COL_EQSL_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
				            <option value="I" <?php if($qso->COL_EQSL_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				          </select>
				          </div>
				            </div>

				            <div class="form-group row">
				              <label for="sent" class="col-sm-3 col-form-label">Received</label>
				              <div class="col-sm-9">
				              <select class="custom-select" name="eqsl_recv">
				            <option value="N" <?php if($qso->COL_EQSL_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
				            <option value="Y" <?php if($qso->COL_EQSL_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
				            <option value="R" <?php if($qso->COL_EQSL_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
				            <option value="I" <?php if($qso->COL_EQSL_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				            <option value="V" <?php if($qso->COL_EQSL_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
				          </select></div>
				            </div>    
				  </div>
				  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
				            <div class="form-group row">
				              <label for="sent" class="col-sm-3 col-form-label">Sent</label>
				              <div class="col-sm-9">
				              <select class="custom-select" name="lotw_sent">
				            <option value="N" <?php if($qso->COL_LOTW_QSL_SENT == "N") { echo "selected=\"selected\""; } ?>>No</option>
				            <option value="Y" <?php if($qso->COL_LOTW_QSL_SENT == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
				            <option value="R" <?php if($qso->COL_LOTW_QSL_SENT == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
				            <option value="Q" <?php if($qso->COL_LOTW_QSL_SENT == "Q") { echo "selected=\"selected\""; } ?>>Queued</option>
				            <option value="I" <?php if($qso->COL_LOTW_QSL_SENT == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				          </select></div>
				            </div>

				            <div class="form-group row">
				              <label for="sent" class="col-sm-3 col-form-label">Received</label>
				              <div class="col-sm-9">
				              <select class="custom-select" name="lotw_recv">
				            <option value="N" <?php if($qso->COL_LOTW_QSL_RCVD == "N") { echo "selected=\"selected\""; } ?>>No</option>
				            <option value="Y" <?php if($qso->COL_LOTW_QSL_RCVD == "Y") { echo "selected=\"selected\""; } ?>>Yes</option>
				            <option value="R" <?php if($qso->COL_LOTW_QSL_RCVD == "R") { echo "selected=\"selected\""; } ?>>Requested</option>
				            <option value="I" <?php if($qso->COL_LOTW_QSL_RCVD == "I") { echo "selected=\"selected\""; } ?>>Invalid (Ignore)</option>
				            <option value="V" <?php if($qso->COL_LOTW_QSL_RCVD == "V") { echo "selected=\"selected\""; } ?>>Verified (Match)</option>
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
	                <option value="<?php echo $stationrow->station_id; ?>" <?php if($qso->station_id == $stationrow->station_id) { echo "selected=\"selected\""; } ?>><?php echo $stationrow->station_profile_name; ?></option>
	                <?php } ?>
	              </select>
	            </div>

	             <div class="form-group">
				    <label for="operatorCallsign">Operator Callsign</label>
				    <input type="text" id="operatorCallsign" class="form-control" name="operator_callsign" value="<?php echo $qso->COL_OPERATOR; ?>" />
				</div>


			</div>

		</div>

		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" />

		<div class="actions">
			<input type="submit" class="btn btn-success" value="Save changes" onclick="closeME();">
			<a class="btn btn-danger float-right" href="<?php echo site_url('qso/delete'); ?>/<?php echo $qso->COL_PRIMARY_KEY; ?>" onclick="return confirm('Are you sure you want the delete QSO?');"><i class="fas fa-trash-alt"></i> Delete QSO</a>
		</div>
	</div>
</div>

	</form>			
		</div>
	</div>
</div>

	</body>
</html>

</div>
