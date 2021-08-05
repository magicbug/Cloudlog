<div class="container qso_panel">

<div class="row">

  <div class="col-sm-5">
    <div class="card">

    <form id="qso_input" method="post" action="<?php echo site_url('qso') . "?manual=" . $_GET['manual']; ?>" name="qsos">

      <div class="card-header">
        <ul style="font-size: 15px;" class="nav nav-tabs card-header-tabs pull-right"  id="myTab" role="tablist">
          <li class="nav-item">
           <a class="nav-link active" id="qsp-tab" data-toggle="tab" href="#qso" role="tab" aria-controls="qso" aria-selected="true"><?php echo $this->lang->line('gen_hamradio_qso'); ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="station-tab" data-toggle="tab" href="#station" role="tab" aria-controls="station" aria-selected="false"><?php echo $this->lang->line('gen_hamradio_station'); ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false"><?php echo $this->lang->line('general_word_general'); ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="satellite-tab" data-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false"><?php echo $this->lang->line('general_word_satellite_short'); ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="notes-tab" data-toggle="tab" href="#nav-notes" role="tab" aria-controls="notes" aria-selected="false"><?php echo $this->lang->line('general_word_notes'); ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" id="qsl-tab" data-toggle="tab" href="#qsl" role="tab" aria-controls="qsl" aria-selected="false"><?php echo $this->lang->line('gen_hamradio_qsl'); ?></a>
          </li>
        </ul>
      </div>

      <div class="card-body">
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="qso" role="tabpanel" aria-labelledby="qso-tab">
                      <!-- HTML for Date/Time -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="start_date"><?php echo $this->lang->line('general_word_date'); ?></label>
                  <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_date'); } else { echo date('d-m-Y');}?>" <?php echo ($_GET['manual'] == 0 ? "disabled" : "");  ?> >
                </div>

                <div class="form-group col-md-6">
                  <label for="start_time"><?php echo $this->lang->line('general_word_time'); ?></label>
                  <input type="text" class="form-control form-control-sm input_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_time'); } else {echo date('H:i'); } ?>" size="7" <?php echo ($_GET['manual'] == 0 ? "disabled" : "");  ?>>
                </div>

                <?php if ( $_GET['manual'] == 0 ) { ?>
                  <input class="input_time" type="hidden" id="start_time"  name="start_time"value="<?php echo date('H:i'); ?>" />
                  <input class="input_date" type="hidden" id="start_date" name="start_date" value="<?php echo date('d-m-Y'); ?>" />
                <?php } ?>
              </div>



              <!-- Callsign Input -->
              <div class="form-group">
                <label for="callsign"><?php echo $this->lang->line('gen_hamradio_callsign'); ?></label>
                <input type="text" class="form-control" id="callsign" name="callsign" required>
                <small id="callsign_info" class="badge badge-secondary"></small> <small id="lotw_info" class="badge badge-light"></small>
              </div>

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="mode"><?php echo $this->lang->line('gen_hamradio_mode'); ?></label>
                  <select id="mode" class="form-control mode form-control-sm" name="mode">
                  <?php
                      foreach($modes->result() as $mode){
                        if ($mode->submode == null) {
                          printf("<option value=\"%s\" %s>%s</option>", $mode->mode, $this->session->userdata('mode')==$mode->mode?"selected=\"selected\"":"",$mode->mode);
                        } else {
                          printf("<option value=\"%s\" %s>&rArr; %s</option>", $mode->submode, $this->session->userdata('mode')==$mode->submode?"selected=\"selected\"":"",$mode->submode);
                        }
                      }
                  ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="band"><?php echo $this->lang->line('gen_hamradio_band'); ?></label>

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
                      <option value="6cm" <?php if($this->session->userdata('band') == "6cm") { echo "selected=\"selected\""; } ?>>6cm</option>
                      <option value="3cm" <?php if($this->session->userdata('band') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
                    </optgroup>
                  </select>
                </div>
              </div>

              <!-- Signal Report Information -->
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="rst_sent"><?php echo $this->lang->line('gen_hamradio_rsts'); ?></label>
                  <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                </div>

                <div class="form-group col-md-6">
                  <label for="rst_recv"><?php echo $this->lang->line('gen_hamradio_rstr'); ?></label>
                  <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="59">
                </div>
              </div>

              <div class="form-group row">
                  <label for="name" class="col-sm-3 col-form-label"><?php echo $this->lang->line('general_word_name'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                </div>
              </div>

              <div class="form-group row">
                <label for="qth" class="col-sm-3 col-form-label"><?php echo $this->lang->line('general_word_location'); ?></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="qth" id="qth" value="">
                </div>
              </div>

              <div class="form-group row">
                  <label for="locator" class="col-sm-3 col-form-label"><?php echo $this->lang->line('gen_hamradio_locator'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="locator" id="locator" value="">
                    <small id="locator_info" class="form-text text-muted"></small>
                </div>
              </div>

              <div class="form-group row">
                  <label for="comment" class="col-sm-3 col-form-label"><?php echo $this->lang->line('general_word_comment'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                </div>
              </div>

          </div>

          <!-- Station Panel Data -->
          <div class="tab-pane fade" id="station" role="tabpanel" aria-labelledby="station-tab">
            <div class="form-group">
              <label for="stationProfile"><?php echo $this->lang->line('cloudlog_station_profile'); ?></label>
              <select id="stationProfile" class="custom-select" name="station_profile">
                <?php foreach ($stations->result() as $stationrow) { ?>
                <option value="<?php echo $stationrow->station_id; ?>" <?php if($active_station_profile == $stationrow->station_id) { echo "selected=\"selected\""; } ?>><?php echo $stationrow->station_profile_name; ?></option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label for="radio"><?php echo $this->lang->line('gen_hamradio_radio'); ?></label>
              <select class="custom-select radios" id="radio" name="radio">
                <option value="0" selected="selected"><?php echo $this->lang->line('general_word_none'); ?></option>
                <?php foreach ($radios->result() as $row) { ?>
                <option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
                <?php } ?>
                </select>
            </div>

            <div class="form-group">
              <label for="frequency"><?php echo $this->lang->line('gen_hamradio_frequency'); ?></label>
              <input type="text" class="form-control" id="frequency" name="freq_display" value="<?php echo $this->session->userdata('freq'); ?>" />
            </div>

            <div class="form-group">
              <label for="frequency_rx"><?php echo $this->lang->line('gen_hamradio_frequency_rx'); ?></label>
              <input type="text" class="form-control" id="frequency_rx" name="freq_display_rx" value="<?php echo $this->session->userdata('freq_rx'); ?>" />
            </div>

            <div class="form-group">
                  <label for="band_rx"><?php echo $this->lang->line('gen_hamradio_band_rx'); ?></label>

                  <select id="band_rx" class="form-control" name="band_rx">
                    <option value="" <?php if($this->session->userdata('band_rx') == "") { echo "selected=\"selected\""; } ?>></option>

                    <optgroup label="HF">
                      <option value="160m" <?php if($this->session->userdata('band_rx') == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
                      <option value="80m" <?php if($this->session->userdata('band_rx') == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
                      <option value="60m" <?php if($this->session->userdata('band_rx') == "60m") { echo "selected=\"selected\""; } ?>>60m</option>
                      <option value="40m" <?php if($this->session->userdata('band_rx') == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
                      <option value="30m" <?php if($this->session->userdata('band_rx') == "30m") { echo "selected=\"selected\""; } ?>>30m</option>
                      <option value="20m" <?php if($this->session->userdata('band_rx') == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
                      <option value="17m" <?php if($this->session->userdata('band_rx') == "17m") { echo "selected=\"selected\""; } ?>>17m</option>
                      <option value="15m" <?php if($this->session->userdata('band_rx') == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
                      <option value="12m" <?php if($this->session->userdata('band_rx') == "12m") { echo "selected=\"selected\""; } ?>>12m</option>
                      <option value="10m" <?php if($this->session->userdata('band_rx') == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
                    </optgroup>

                    <optgroup label="VHF">
                      <option value="6m" <?php if($this->session->userdata('band_rx') == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
                      <option value="4m" <?php if($this->session->userdata('band_rx') == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
                      <option value="2m" <?php if($this->session->userdata('band_rx') == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
                    </optgroup>

                    <optgroup label="UHF">
                      <option value="70cm" <?php if($this->session->userdata('band_rx') == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
                      <option value="23cm" <?php if($this->session->userdata('band_rx') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
                      <option value="13cm" <?php if($this->session->userdata('band_rx') == "13cm") { echo "selected=\"selected\""; } ?>>13cm</option>
                      <option value="9cm" <?php if($this->session->userdata('band_rx') == "9cm") { echo "selected=\"selected\""; } ?>>9cm</option>
                    </optgroup>

                    <optgroup label="Microwave">
                      <option value="6cm" <?php if($this->session->userdata('band_rx') == "6cm") { echo "selected=\"selected\""; } ?>>6cm</option>
                      <option value="3cm" <?php if($this->session->userdata('band_rx') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
                    </optgroup>
                  </select>
            </div>

            <div class="form-group">
              <label for="transmit_power"><?php echo $this->lang->line('gen_hamradio_transmit_power'); ?></label>
              <input type="number" step="0.001" class="form-control" id="transmit_power" name="transmit_power" value="<?php echo $this->session->userdata('transmit_power'); ?>" />
              <small id="powerHelp" class="form-text text-muted"><?php echo $this->lang->line('qso_transmit_power_helptext'); ?></small>
            </div>
          </div>

          <!-- General Items -->
          <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
              <div class="form-group">
                  <label for="dxcc_id"><?php echo $this->lang->line('gen_hamradio_dxcc'); ?></label>
                  <select class="custom-select" id="dxcc_id" name="dxcc_id" required>
                      <option value="0">None</option>
                      <?php
                      foreach($dxcc as $d){
                          echo '<option value=' . $d->adif . '>' . $d->prefix . ' - ' . ucwords(strtolower(($d->name))) . '</option>';
                      }
                      ?>

                  </select>
              </div>
              <div class="form-group">
                  <label for="cqz"><?php echo $this->lang->line('gen_hamradio_cq_zone'); ?></label>
                  <select class="custom-select" id="cqz" name="cqz" required>
                      <?php
                      for ($i = 0; $i<=40; $i++) {
                          echo '<option value="'. $i . '">'. $i .'</option>';
                      }
                      ?>
                  </select>
              </div>

            <div class="form-group">
              <label for="selectPropagation"><?php echo $this->lang->line('gen_hamradio_propagation_mode'); ?></label>
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
                <option value="MS" <?php if($this->session->userdata('prop_mode') == "MS") { echo "selected=\"selected\""; } ?>>Meteor scatter</option>
                <option value="RPT" <?php if($this->session->userdata('prop_mode') == "RPT") { echo "selected=\"selected\""; } ?>>Terrestrial or atmospheric repeater or transponder</option>
                <option value="RS" <?php if($this->session->userdata('prop_mode') == "RS") { echo "selected=\"selected\""; } ?>>Rain scatter</option>
                <option value="SAT" <?php if($this->session->userdata('prop_mode') == "SAT") { echo "selected=\"selected\""; } ?>>Satellite</option>
                <option value="TEP" <?php if($this->session->userdata('prop_mode') == "TEP") { echo "selected=\"selected\""; } ?>>Trans-equatorial</option>
                <option value="TR" <?php if($this->session->userdata('prop_mode') == "TR") { echo "selected=\"selected\""; } ?>>Tropospheric ducting</option>
              </select>
            </div>

            <div class="form-group">
              <label for="input_usa_state"><?php echo $this->lang->line('gen_hamradio_usa_state'); ?></label>
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
                  <label for="stationCntyInput"><?php echo $this->lang->line('gen_hamradio_county_reference'); ?></label>
                  <input disabled="disabled" class="form-control" id="stationCntyInput" type="text" name="county" value="" />
              </div>

            <div class="form-group">
              <label for="iota_ref"><?php echo $this->lang->line('gen_hamradio_iota_reference'); ?></label>
                    <select class="custom-select" id="iota_ref" name="iota_ref">
                        <option value =""></option>

                        <?php
                        foreach($iota as $i){
                            echo '<option value=' . $i->tag . '>' . $i->tag . ' - ' . $i->name . '</option>';
                        }
                        ?>

                    </select>
            </div>

            <div class="form-group">
              <label for="sota_ref"><?php echo $this->lang->line('gen_hamradio_sota_reference'); ?></label>
              <input class="form-control" id="sota_ref" type="text" name="sota_ref" value="" />
              <small id="sotaRefHelp" class="form-text text-muted"><?php echo $this->lang->line('qso_sota_ref_helptext'); ?></small>
            </div>

            <div class="form-group">
              <label for="sig"><?php echo $this->lang->line('gen_hamradio_sig'); ?></label>
              <input class="form-control" id="sig" type="text" name="sig" value="" />
              <small id="sigHelp" class="form-text text-muted"><?php echo $this->lang->line('qso_sig_helptext'); ?></small>
            </div>

            <div class="form-group">
              <label for="sig_info"><?php echo $this->lang->line('gen_hamradio_sig_info'); ?></label>
              <input class="form-control" id="sig_info" type="text" name="sig_info" value="" />
              <small id="sigInfoHelp" class="form-text text-muted"><?php echo $this->lang->line('qso_sig_info_helptext'); ?></small>
            </div>

            <div class="form-group">
              <label for="darc_dok"><?php echo $this->lang->line('gen_hamradio_dok'); ?></label>
              <input class="form-control" id="darc_dok" type="text" name="darc_dok" value="" />
              <small id="dokHelp" class="form-text text-muted"><?php echo $this->lang->line('qso_dok_helptext'); ?></small>
            </div>
          </div>

          <!-- Satellite Panel -->
          <div class="tab-pane fade" id="satellite" role="tabpanel" aria-labelledby="satellite-tab">
            <div class="form-group">
              <label for="sat_name"><?php echo $this->lang->line('gen_hamradio_satellite_name'); ?></label>

              <input list="satellite_names" id="sat_name" type="text" name="sat_name" class="form-control" value="<?php echo $this->session->userdata('sat_name'); ?>">

              <datalist id="satellite_names" class="satellite_names_list"></datalist>
            </div>

            <div class="form-group">
              <label for="sat_mode"><?php echo $this->lang->line('gen_hamradio_satellite_mode'); ?></label>

              <input list="satellite_modes" id="sat_mode" type="text" name="sat_mode" class="form-control" value="<?php echo $this->session->userdata('sat_mode'); ?>">

              <datalist id="satellite_modes" class="satellite_modes_list"></datalist>
            </div>
          </div>

          <!-- Notes Panel Contents -->
          <div class="tab-pane fade" id="nav-notes" role="tabpanel" aria-labelledby="notes-tab">
            <div class="alert alert-info" role="alert">
              <span class="badge badge-info"><?php echo $this->lang->line('general_word_info'); ?></span> <?php echo $this->lang->line('qso_notes_helptext'); ?>
            </div>
           <div class="form-group">
              <label for="notes"><?php echo $this->lang->line('general_word_notes'); ?></label>
              <textarea  type="text" class="form-control" id="notes" name="notes" rows="10"></textarea>
            </div>
          </div>

          <!-- QSL Tab -->
          <div class="tab-pane fade" id="qsl" role="tabpanel" aria-labelledby="qsl-tab">

            <div class="form-group row">
              <label for="sent" class="col-sm-3 col-form-label"><?php echo $this->lang->line('general_word_sent'); ?></label>
              <div class="col-sm-9">
                <select class="custom-select" id="sent" name="qsl_sent">
                  <option value="N" selected="selected"><?php echo $this->lang->line('general_word_no'); ?></option>
                  <option value="Y"><?php echo $this->lang->line('general_word_yes'); ?></option>
                  <option value="R"><?php echo $this->lang->line('general_word_requested'); ?></option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="sent-method" class="col-sm-3 col-form-label"><?php echo $this->lang->line('general_word_method'); ?></label>
              <div class="col-sm-9">
                <select class="custom-select" id="sent-method" name="qsl_sent_method">
                 <option value="" selected="selected"><?php echo $this->lang->line('general_word_method'); ?></option>
                 <option value="D"><?php echo $this->lang->line('general_word_qslcard_direct'); ?></option>
                 <option value="B"><?php echo $this->lang->line('general_word_qslcard_bureau'); ?></option>
                </select>
              </div>
            </div>

            <div class="form-group row">
              <label for="qsl_via" class="col-sm-2 col-form-label"><?php echo $this->lang->line('general_word_qslcard_via'); ?></label>
              <div class="col-sm-10">
                <input type="text" id="qsl_via" class="form-control" name="qsl_via" value="" />
              </div>
            </div>

          </div>
        </div>



        <div class="info">
          <input size="20" id="country" type="hidden" name="country" value="" />
        </div>

        <button type="reset" class="btn btn-light" onclick="reset_fields()"><?php echo $this->lang->line('qso_btn_reset_qso'); ?></button>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo $this->lang->line('qso_btn_save_qso'); ?></button>
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

    <!-- QSO Map -->
    <div class="card qso-map">
            <div id="qsomap" style="width: 100%; height: 200px;"></div>
    </div>

    <div class="card callsign-suggest">
        <div class="card-header"><h4 style="font-size: 16px; font-weight: bold;" class="card-title"><?php echo $this->lang->line('qso_title_suggestions'); ?></h4></div>

        <div class="card-body callsign-suggestions"></div>
    </div>

    <div class="card previous-qsos">
      <div class="card-header"><h4 class="card-title" style="font-size: 16px; font-weight: bold;"><?php echo $this->lang->line('qso_title_pervious_contacts'); ?></h4></div>

        <div id="partial_view" style="font-size: 0.95rem;"></div>

        <div id="qso-last-table">

          <div class="table-responsive" style="font-size: 0.95rem;">
            <table class="table">
              <tr class="log_title titles">
                <td><?php echo $this->lang->line('general_word_date'); ?>/<?php echo $this->lang->line('general_word_time'); ?></td>
                <td><?php echo $this->lang->line('gen_hamradio_call'); ?></td>
                <td><?php echo $this->lang->line('gen_hamradio_mode'); ?></td>
                <td><?php echo $this->lang->line('gen_hamradio_rsts'); ?></td>
                <td><?php echo $this->lang->line('gen_hamradio_rstr'); ?></td>
                <td><?php echo $this->lang->line('gen_hamradio_band'); ?></td>
              </tr>

              <?php

			  // Get Date format
			  if($this->session->userdata('user_date_format')) {
				  // If Logged in and session exists
				  $custom_date_format = $this->session->userdata('user_date_format');
			  } else {
				  // Get Default date format from /config/cloudlog.php
				  $custom_date_format = $this->config->item('qso_date_format');
			  }

			  $i = 0;
              foreach ($query->result() as $row) {
              	echo '<tr class="tr'.($i & 1).'">';
                    echo '<td>';
						$timestamp = strtotime($row->COL_TIME_ON);
						echo date($custom_date_format, $timestamp);
						echo date(' H:i',strtotime($row->COL_TIME_ON));
					?>
				  </td>
                  <td>
                      <a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a>
                  </td>
                    <td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
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
