<div class="container qso_panel">

<div class="row">
  
  <div class="col-sm-5">
    <div class="card">
        
    <form id="qso_input" method="post" action="<?php echo site_url('qso') . "?manual=" . $_GET['manual']; ?>" name="qsos">

      <div class="card-header"> 
        <ul class="nav nav-tabs card-header-tabs pull-right"  id="myTab" role="tablist">
          <li class="nav-item">
           <a class="nav-link active" id="qsp-tab" data-toggle="tab" href="#qso" role="tab" aria-controls="qso" aria-selected="true">QSO</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="station-tab" data-toggle="tab" href="#station" role="tab" aria-controls="station" aria-selected="false">Station</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false">General</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="satellite-tab" data-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false">Satellite</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="qsl-tab" data-toggle="tab" href="#qsl" role="tab" aria-controls="qsl" aria-selected="false">QSLing</a>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="qso" role="tabpanel" aria-labelledby="qso-tab">
                      <!-- HTML for Date/Time -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="start_date">Date</label>
                  <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php echo date('d-m-Y'); ?>" <?php echo ($_GET['manual'] == 0 ? "disabled" : "");  ?> >
                </div>

                <div class="form-group col-md-6">
                  <label for="start_time">Time</label>
                  <input type="text" class="form-control form-control-sm input_time" name="start_time" id="start_time" value="<?php echo date('H:i'); ?>" size="7" <?php echo ($_GET['manual'] == 0 ? "disabled" : "");  ?>>
                </div>

                <?php if ( $_GET['manual'] == 0 ) { ?>
                  <input class="input_time" type="hidden" id="start_time"  name="start_time"value="<?php echo date('H:i'); ?>" />
                  <input class="input_date" type="hidden" id="start_date" name="start_date" value="<?php echo date('d-m-Y'); ?>" />
                <?php } ?>
              </div>



              <!-- Callsign Input -->
              <div class="form-group">
                <label for="callsign">Callsign</label>
                <input type="text" class="form-control" id="callsign" name="callsign" required>
                <small id="callsign_info" class="badge badge-secondary"></small> <small id="lotw_info" class="badge badge-light"></small>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="mode">Mode</label>
                  <select id="mode" class="form-control mode form-control-sm" name="mode">
                  <?php
                      $this->load->library('frequency');
                      foreach(Frequency::modes as $mode){
                          printf("<option value=\"%s\" %s>%s</option>", $mode, $this->session->userdata('mode')==$mode?"selected=\"selected\"":"",$mode);
                      }
                  ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="band">Band</label>

                  <select id="band" class="form-control form-control-sm" name="band">
                    <optgroup label="HF">
                      <option value="160m" <?php if($this->session->userdata('band') == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
                      <option value="80m" <?php if($this->session->userdata('band') == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
                      <option value="60m" <?php if($this->session->userdata('band') == "60m") { echo "selected=\"selected\""; } ?>>60m</option>
                      <option value="40m" <?php if($this->session->userdata('band') == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
                      <option value="30m" <?php if($this->session->userdata('band') == "30m") { echo "selected=\"selected\""; } ?>>30m</option>
                      <option value="20m" <?php if($this->session->userdata('band') == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
                      <option value="17m" <?php if($this->session->userdata('band') == "17m") { echo "selected=\"selected\""; } ?>>17m</option>
                      <option value="15m" <?php if($this->session->userdata('band') == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
                      <option value="12m" <?php if($this->session->userdata('band') == "12m") { echo "selected=\"selected\""; } ?>>12m</option>
                      <option value="10m" <?php if($this->session->userdata('band') == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
                    </optgroup>

                    <optgroup label="VHF">
                      <option value="6m" <?php if($this->session->userdata('band') == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
                      <option value="4m" <?php if($this->session->userdata('band') == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
                      <option value="2m" <?php if($this->session->userdata('band') == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
                    </optgroup>

                    <optgroup label="UHF">
                      <option value="70cm" <?php if($this->session->userdata('band') == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
                      <option value="23cm" <?php if($this->session->userdata('band') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
                      <option value="13cm" <?php if($this->session->userdata('band') == "13cm") { echo "selected=\"selected\""; } ?>>13cm</option>
                      <option value="9cm" <?php if($this->session->userdata('band') == "9cm") { echo "selected=\"selected\""; } ?>>9cm</option>
                    </optgroup>

                    <optgroup label="Microwave">
                      <option value="3cm" <?php if($this->session->userdata('band') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
                    </optgroup>
                  </select>
                </div>
              </div>

              <!-- Signal Report Information -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="rst_sent">RST (S)</label>
                  <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                </div>

                <div class="form-group col-md-6">
                  <label for="rst_recv">RST (R)</label>
                  <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="59">
                </div>
              </div>

              <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                </div>
              </div>

              <div class="form-group row">
                <label for="qth" class="col-sm-3 col-form-label">Location</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="qth" id="qth" value="">
                </div>
              </div>

              <div class="form-group row">
                  <label for="locator" class="col-sm-3 col-form-label">Locator</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="locator" id="locator" value="">
                    <small id="locator_info" class="form-text text-muted"></small>
                </div>
              </div>

              <div class="form-group row">
                  <label for="comment" class="col-sm-3 col-form-label">Comment</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                </div>
              </div>

              <div class="form-group">
                  <label for="dxcc_id">DXCC</label>
                  <select class="form-control" id="dxcc_id" name="dxcc_id" required>

                      <?php
                      foreach($dxcc as $d){
                          echo '<option value=' . $d->adif . '>' . $d->prefix . ' - ' . $d->name . '</option>';
                      }
                      ?>

                  </select>
              </div>
              <div class="form-row">
                  <div class="form-group col-md-6">
                      <label for="cqz">CQ Zone</label>
                      <select class="form-control" id="cqz" name="cqz" required>
                          <?php
                          for ($i = 1; $i<=40; $i++) {
                              echo '<option value="'. $i . '">'. $i .'</option>';
                          }
                          ?>
                      </select>
                  </div>

              </div>
          </div>

          <!-- Station Panel Data -->
          <div class="tab-pane fade" id="station" role="tabpanel" aria-labelledby="station-tab">
            <div class="form-group">
              <label for="inputStationProfile">Station Profile</label>
              <select id="stationProfile" class="custom-select" name="station_profile">
                <?php foreach ($stations->result() as $stationrow) { ?>
                <option value="<?php echo $stationrow->station_id; ?>" <?php if($active_station_profile == $stationrow->station_id) { echo "selected=\"selected\""; } ?>><?php echo $stationrow->station_profile_name; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="inputRadio">Radio</label>
              <select class="custom-select radios" id="radio" name="radio">
                <option value="0" selected="selected">None</option>
                <?php foreach ($radios->result() as $row) { ?>
                <option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
                <?php } ?>
                </select>
            </div>

            <div class="form-group">
              <label for="frequency">Frequency</label>
              <input type="text" class="form-control" id="frequency" name="freq_display" value="<?php echo $this->session->userdata('freq'); ?>" />
            </div>

            <div class="form-group">
              <label for="frequency_rx">Frequency (RX)</label>
              <input type="text" class="form-control" id="frequency_rx" name="freq_display_rx" value="<?php echo $this->session->userdata('freq_rx'); ?>" />
            </div>
          </div>

          <!-- General Items -->
          <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="form-group">
              <label for="selectPropagation">Propagation Mode</label>
              <select class="custom-select" id="selectPropagation" name="prop_mode">
                <option value="" <?php if(!empty($this->session->userdata('prop_mode'))) { echo "selected=\"selected\""; } ?>></option>
                <option value="AUR" <?php if($this->session->userdata('prop_mode') == "AUR") { echo "selected=\"selected\""; } ?>>Aurora</option>
                <option value="AUE" <?php if($this->session->userdata('prop_mode') == "AUE") { echo "selected=\"selected\""; } ?>>Aurora-E</option>
                <option value="BS" <?php if($this->session->userdata('prop_mode') == "BS") { echo "selected=\"selected\""; } ?>>Back scatter</option>
                <option value="ECH" <?php if($this->session->userdata('prop_mode') == "ECH") { echo "selected=\"selected\""; } ?>>EchoLink</option>
                <option value="EME" <?php if($this->session->userdata('prop_mode') == "EME") { echo "selected=\"selected\""; } ?>>Earth-Moon-Earth</option>
                <option value="ES" <?php if($this->session->userdata('prop_mode') == "ES") { echo "selected=\"selected\""; } ?>>Sporadic E</option>
                <option value="FAI" <?php if($this->session->userdata('prop_mode') == "FAI") { echo "selected=\"selected\""; } ?>>Field Aligned Irregularities</option>
                <option value="F2" <?php if($this->session->userdata('prop_mode') == "F2") { echo "selected=\"selected\""; } ?>>F2 Reflection</option>
                <option value="INTERNET" <?php if($this->session->userdata('prop_mode') == "INTERNET") { echo "selected=\"selected\""; } ?>>Internet-assisted</option>
                <option value="ION" <?php if($this->session->userdata('prop_mode') == "ION") { echo "selected=\"selected\""; } ?>>Ionoscatter</option>
                <option value="IRL" <?php if($this->session->userdata('prop_mode') == "IRL") { echo "selected=\"selected\""; } ?>>IRLP</option>
                <option value="MS" <?php if($this->session->userdata('prop_mode') == "AUR") { echo "selected=\"selected\""; } ?>>Meteor scatter</option>
                <option value="RPT" <?php if($this->session->userdata('prop_mode') == "RPT") { echo "selected=\"selected\""; } ?>>Terrestrial or atmospheric repeater or transponder</option>
                <option value="RS" <?php if($this->session->userdata('prop_mode') == "RS") { echo "selected=\"selected\""; } ?>>Rain scatter</option>
                <option value="SAT" <?php if($this->session->userdata('prop_mode') == "SAT") { echo "selected=\"selected\""; } ?>>Satellite</option>
                <option value="TEP" <?php if($this->session->userdata('prop_mode') == "TEP") { echo "selected=\"selected\""; } ?>>Trans-equatorial</option>
                <option value="TR" <?php if($this->session->userdata('prop_mode') == "TR") { echo "selected=\"selected\""; } ?>>Tropospheric ducting</option>
              </select>
            </div>

            <div class="form-group">
              <label for="usa_state">USA State</label>
              <select class="custom-select" id="input_usa_state" name="usa_state">
                <option value=""></option>
                <option value="AL">Alabama (AL)</option>
                <option value="AK">Alaska (AK)</option>
                <option value="AZ">Arizona (AZ)</option>
                <option value="AR">Arkansas (AR)</option>
                <option value="CA">California (CA)</option>
                <option value="CO">Colorado (CO)</option>
                <option value="CT">Connecticut (CT)</option>
                <option value="DE">Delaware (DE)</option>
                <option value="DC">District Of Columbia (DC)</option>
                <option value="FL">Florida (FL)</option>
                <option value="GA">Georgia (GA)</option>
                <option value="HI">Hawaii (HI)</option>
                <option value="ID">Idaho (ID)</option>
                <option value="IL">Illinois (IL)</option>
                <option value="IN">Indiana (IN)</option>
                <option value="IA">Iowa (IA)</option>
                <option value="KS">Kansas (KS)</option>
                <option value="KY">Kentucky (KY)</option>
                <option value="LA">Louisiana (LA)</option>
                <option value="ME">Maine (ME)</option>
                <option value="MD">Maryland (MD)</option>
                <option value="MA">Massachusetts (MA)</option>
                <option value="MI">Michigan (MI)</option>
                <option value="MN">Minnesota (MN)</option>
                <option value="MS">Mississippi (MS)</option>
                <option value="MO">Missouri (MO)</option>
                <option value="MT">Montana (MT)</option>
                <option value="NE">Nebraska (NE)</option>
                <option value="NV">Nevada (NV)</option>
                <option value="NH">New Hampshire (NH)</option>
                <option value="NJ">New Jersey (NJ)</option>
                <option value="NM">New Mexico (NM)</option>
                <option value="NY">New York (NY)</option>
                <option value="NC">North Carolina (NC)</option>
                <option value="ND">North Dakota (ND)</option>
                <option value="OH">Ohio (OH)</option>
                <option value="OK">Oklahoma (OK)</option>
                <option value="OR">Oregon (OR)</option>
                <option value="PA">Pennsylvania (PA)</option>
                <option value="RI">Rhode Island (RI)</option>
                <option value="SC">South Carolina (SC)</option>
                <option value="SD">South Dakota (SD)</option>
                <option value="TN">Tennessee (TN)</option>
                <option value="TX">Texas (TX)</option>
                <option value="UT">Utah (UT)</option>
                <option value="VT">Vermont (VT)</option>
                <option value="VA">Virginia (VA)</option>
                <option value="WA">Washington (WA)</option>
                <option value="WV">West Virginia (WV)</option>
                <option value="WI">Wisconsin (WI)</option>
                <option value="WY">Wyoming (WY)</option>
              </select>
            </div>

            <div class="form-group">
              <label for="iota_ref">IOTA Reference</label>
              <input class="form-control" id="iota_ref" type="text" name="iota_ref" value="" /> e.g: EU-005
            </div>

            <div class="form-group">
              <label for="sota_ref">SOTA Reference</label>
              <input class="form-control" id="sota_ref" type="text" name="sota_ref" value="" /> e.g: GM/NS-001
            </div>

            <div class="form-group">
              <label for="sota_ref">DOK</label>
              <input class="form-control" id="darc_dok" type="text" name="darc_dok" value="" /> e.g: Q03
            </div>
          </div>
          
          <!-- Satellite Panel -->
          <div class="tab-pane fade" id="satellite" role="tabpanel" aria-labelledby="satellite-tab">
            <div class="form-group">
              <label for="inputSatName">Satellite Name</label>
              <input id="sat_name" type="text" name="sat_name" class="form-control" value="<?php echo $this->session->userdata('sat_name'); ?>" />
            </div>

            <div class="form-group">
              <label for="inputSatMode">Satellite Mode</label>
              <input id="sat_mode" type="text" name="sat_mode" class="form-control" value="<?php echo $this->session->userdata('sat_mode'); ?>" />
            </div>
          </div>
          
          <!-- QSL Tab -->
          <div class="tab-pane fade" id="qsl" role="tabpanel" aria-labelledby="qsl-tab">
            
            <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label">Sent</label>
              <div class="col-sm-9">
                <select class="custom-select" id="sent" name="qsl_sent">
                  <option value="N" selected="selected">No</option>
                  <option value="Y">Yes</option>
                  <option value="R">Requested</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="sent-method" class="col-sm-3 col-form-label">Method</label>
              <div class="col-sm-9">
                <select class="custom-select" id="sent-method" name="qsl_sent_method">
                 <option value="" selected="selected">Method</option>
                 <option value="D">Direct</option>
                 <option value="B">Bureau</option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="qsl_via" class="col-sm-2 col-form-label">Via</label>
              <div class="col-sm-10">
                <input type="text" id="qsl_via" class="form-control" name="qsl_via" value="" />
              </div>
            </div>

          </div>
        </div>

        

        <div class="info">
          <input size="20" id="country" type="hidden" name="country" value="" />
        </div>
        
        <button type="reset" class="btn btn-light" onclick="reset_fields()">Reset</button>
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save QSO</button>
      </div>
    </form>
    </div>
  </div>


  <div class="col-sm-7">

<?php if($notice) { ?>
<div class="alert alert-info" role="alert">
  <?php echo $notice; ?>
</div>
<?php } ?>

<?php if(validation_errors()) { ?>
<div class="alert alert-warning" role="alert">
  <?php echo validation_errors(); ?>
</div>
<?php } ?>

    <div class="card qso-map">
        <div class="card-header">
          <h4 class="card-title">QSO Map</h4>
        </div>

            <div id="qsomap" style="width: 100%; height: 200px;"></div>
    </div>

    <div class="card callsign-suggest">
        <div class="card-header"><h4 class="card-title">Suggestions</h4></div>

        <div class="card-body callsign-suggestions"></div>
    </div>

    <div class="card previous-qsos">
      <div class="card-header"><h4 class="card-title">Previous Contacts</h4></div>

        <div id="partial_view"></div>

        <div id="qso-last-table">

          <div class="table-responsive">
            <table class="table">
              <tr class="log_title titles">
                <td>Date/Time</td>
                <td>Call</td>
                <td>Mode</td>
                <td>Sent</td>
                <td>Recv</td>
                <td>Band</td>
              </tr>

              <?php $i = 0; 
              foreach ($query->result() as $row) { ?>
                    <?php  echo '<tr class="tr'.($i & 1).'">'; ?>
                    <td><?php echo date($this->config->item('qso_date_format').' H:i',strtotime($row->COL_TIME_ON)); ?></td>
                    <td><a class="qsobox" data-fancybox data-type="iframe" data-width="750" data-height="520" data-src="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>" href="javascript:;"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a></td>
                    <td><?php echo $row->COL_MODE; ?></td>
                    <td><?php echo $row->COL_RST_SENT; ?></td>
                    <td><?php echo $row->COL_RST_RCVD; ?></td>
                    <?php if($row->COL_SAT_NAME != null) { ?>
                    <td><?php echo $row->COL_SAT_NAME; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row->COL_BAND; ?></td>
                    <?php } ?>
                  </tr>
              <?php $i++; } ?>
            </table>
          </div>
        </div>
      </div>
    </div>    
  </div>

</div>

</div>
