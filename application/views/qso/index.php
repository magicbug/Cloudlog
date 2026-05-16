<?php $manual = (int)($this->input->get('manual') ?? 0); ?>
<div class="container qso_panel">
  <script language="javascript">
    var qso_manual = "<?php echo $manual; ?>";
    var station_gridsquares = <?php
        $sq = [];
        foreach ($stations->result() as $st) { $sq[(int)$st->station_id] = $st->station_gridsquare; }
        echo json_encode($sq);
    ?>;
    var qso_measurement_base = "<?php echo htmlspecialchars($measurement_base ?? 'K', ENT_QUOTES, 'UTF-8'); ?>";
    var text_error_timeoff_less_timeon = "<?php echo lang('qso_error_timeoff_less_timeon'); ?>";
    var lang_qso_title_previous_contacts = "<?php echo lang('qso_title_previous_contacts'); ?>";
    var lang_qso_title_times_worked_before = "<?php echo lang('qso_title_times_worked_before'); ?>";
    
    // Function to switch between LIVE and POST mode without the beforeunload warning
    function switchMode(url) {
      // Temporarily unbind the beforeunload event to prevent the "Leave site?" popup
      $(window).unbind('beforeunload');
      // Navigate to the new URL
      window.location.href = url;
    }
  </script>

  <style>
    @media (max-width: 991.98px) {
      .qso_panel {
        overflow-x: hidden;
      }

      .qso_panel .card-header {
        overflow: hidden;
      }

      .qso_panel #myTab,
      .qso_panel #qsoRightTabs {
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        max-width: 100%;
      }

      .qso_panel #myTab::-webkit-scrollbar,
      .qso_panel #qsoRightTabs::-webkit-scrollbar {
        display: none;
      }

      .qso_panel #myTab .nav-item,
      .qso_panel #qsoRightTabs .nav-item {
        flex: 0 0 auto;
      }

      .qso_panel #myTab .nav-link,
      .qso_panel #qsoRightTabs .nav-link {
        padding: 0.35rem 0.55rem;
        font-size: 0.95rem;
      }

      .qso_panel .card-body {
        padding: 0.9rem;
      }

      .qso_panel .radio_selection {
        flex-wrap: wrap;
        gap: 0.5rem;
      }

      .qso_panel .radio_selection .form-label {
        margin-bottom: 0;
      }

      .qso_panel .radio_selection .btn {
        width: 100%;
      }

      .qso_panel #qso-last-table-content .table th:nth-child(n+5),
      .qso_panel #qso-last-table-content .table td:nth-child(n+5) {
        display: none;
      }
    }
  </style>

  <div class="row qsopane">

    <div class="col-lg-5">
      <div class="card">

        <form id="qso_input" method="post" action="<?php echo site_url('qso') . "?manual=" . $manual; ?>" data-ajax-save-url="<?php echo site_url('qso/ajax_saveqso'); ?>" name="qsos" autocomplete="off" onReset="resetTimers(<?php echo $manual; ?>);">

          <div class="card-header">
            <ul style="font-size: 15px;" class="nav nav-tabs card-header-tabs pull-right" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="qsp-tab" data-bs-toggle="tab" href="#qso" role="tab" aria-controls="qso" aria-selected="true"><?php echo lang('gen_hamradio_qso'); ?></a>
              </li>

              <?php if ($qso_fields['station_tab']): ?>
              <li class="nav-item">
                <a class="nav-link" id="station-tab" data-bs-toggle="tab" href="#station" role="tab" aria-controls="station" aria-selected="false"><?php echo lang('gen_hamradio_station'); ?></a>
              </li>
              <?php endif; ?>

              <?php if ($qso_fields['general_tab']): ?>
              <li class="nav-item">
                <a class="nav-link" id="general-tab" data-bs-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="false"><?php echo lang('general_word_general'); ?></a>
              </li>
              <?php endif; ?>

              <?php if ($sat_active && $qso_fields['satellite_tab']): ?>
                <li class="nav-item">
                  <a class="nav-link" id="satellite-tab" data-bs-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false"><?php echo lang('general_word_satellite_short'); ?></a>
                </li>
              <?php endif; ?>

              <?php if ($qso_fields['notes_tab']): ?>
              <li class="nav-item">
                <a class="nav-link" id="notes-tab" data-bs-toggle="tab" href="#nav-notes" role="tab" aria-controls="notes" aria-selected="false"><?php echo lang('general_word_notes'); ?></a>
              </li>
              <?php endif; ?>

              <?php if ($qso_fields['qsl_tab']): ?>
              <li class="nav-item">
                <a class="nav-link" id="qsl-tab" data-bs-toggle="tab" href="#qsl" role="tab" aria-controls="qsl" aria-selected="false"><?php echo lang('gen_hamradio_qsl'); ?></a>
              </li>
              <?php endif; ?>

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="fav_item" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-star"></i></a>
                <div class="dropdown-menu">
                  <a class="dropdown-item" href="#" id="fav_add"><?php echo lang('fav_add'); ?></a>
                  <div class="dropdown-divider"></div>
                  <div id="fav_menu"></div>
                </div>
              </li>

              <li class="nav-item ms-auto d-flex align-items-center">
                <?php if ($manual == 0) {
                  echo " <span class=\"badge text-bg-success\" style=\"cursor: pointer; font-size: 0.9rem; padding: 0.4rem 0.9rem;\" onclick=\"switchMode('" . site_url('qso') . "?manual=1')\" title=\"Switch to Manual mode\">LIVE</span>";
                };
                if ($manual == 1) {
                  echo " <span class=\"badge text-bg-danger\" style=\"cursor: pointer; font-size: 0.9rem; padding: 0.4rem 0.9rem;\" onclick=\"switchMode('" . site_url('qso') . "?manual=0')\" title=\"Switch to LIVE mode\">POST</span>";
                } ?>
              </li>
              
            </ul>
          </div>

          <div class="card-body">
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="qso" role="tabpanel" aria-labelledby="qso-tab">
                <!-- HTML for Date/Time -->
                <?php if ($this->session->userdata('user_qso_end_times')  == 1) { ?>
                  <div class="row">
                    <div class="mb-3 col-6 col-md-3">
                      <label for="start_date"><?php echo lang('general_word_date'); ?></label>
                      <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) {
                                                                                                                                    echo $this->session->userdata('start_date');
                                                                                                                                  } else {
                                                                                                                                    echo date($user_date_format);
                                                                                                                                  } ?>" <?php echo ($manual == 0 ? "disabled" : "");  ?> required>
                    </div>

                    <div class="mb-3 col-6 col-md-3">
                      <label for="start_time"><?php echo lang('general_word_time_on'); ?></label>
                      <?php if ($manual != 1) { ?>
                        <i id="reset_time" data-bs-toggle="tooltip" title="Reset start time" class="fas fa-stopwatch"></i>
                      <?php } else { ?>
                        <i id="reset_start_time" data-bs-toggle="tooltip" title="Reset start time" class="fas fa-stopwatch"></i>
                      <?php } ?>
                      <input type="text" class="form-control form-control-sm input_start_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) {
                                                                                                                                          echo substr($this->session->userdata('start_time'), 0, 5);
                                                                                                                                        } else {
                                                                                                                                          echo $manual == 0 ? date('H:i:s') : date('H:i');
                                                                                                                                        } ?>" size="7" <?php echo ($manual == 0 ? "disabled" : "");  ?> required pattern="[0-2][0-9]:[0-5][0-9]">
                    </div>

                    <div class="mb-3 col-6 col-md-3">
                      <label for="end_time"><?php echo lang('general_word_time_off'); ?></label>
                      <?php if ($manual == 1) { ?>
                        <i id="reset_end_time" data-bs-toggle="tooltip" title="Reset end time" class="fas fa-stopwatch"></i>
                      <?php } ?>
                      <input type="text" class="form-control form-control-sm input_end_time" name="end_time" id="end_time" value="<?php if (($this->session->userdata('end_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) {
                                                                                                                                    echo substr($this->session->userdata('end_time'), 0, 5);
                                                                                                                                  } else {
                                                                                                                                    echo $manual == 0 ? date('H:i:s') : date('H:i');
                                                                                                                                  } ?>" size="7" <?php echo ($manual == 0 ? "disabled" : "");  ?> required pattern="[0-2][0-9]:[0-5][0-9]">
                    </div>

                    <?php if ($manual == 0) { ?>
                      <input class="input_start_time" type="hidden" id="start_time" name="start_time" value="<?php echo date('H:i:s'); ?>" />
                      <input class="input_end_time" type="hidden" id="end_time" name="end_time" value="<?php echo date('H:i:s'); ?>" />
                      <input class="input_date" type="hidden" id="start_date" name="start_date" value="<?php echo date($user_date_format); ?>" />
                    <?php } ?>
                  </div>

                <?php } else { ?>
                  <div class="row">
                    <div class="mb-3 col-6 col-md-6">
                      <label for="start_date"><?php echo lang('general_word_date'); ?></label>
                      <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) {
                                                                                                                                    echo $this->session->userdata('start_date');
                                                                                                                                  } else {
                                                                                                                                    echo date($user_date_format);
                                                                                                                                  } ?>" <?php echo ($manual == 0 ? "disabled" : "");  ?> required>
                    </div>

                    <div class="mb-3 col-6 col-md-6">
                      <label for="start_time"><?php echo lang('general_word_time'); ?></label>
                      <?php if ($manual == 1) { ?>
                        <i id="reset_start_time" data-bs-toggle="tooltip" title="Reset start time" class="fas fa-stopwatch"></i>
                      <?php } ?>
                      <input type="text" class="form-control form-control-sm input_start_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) {
                                                                                                                                          echo substr($this->session->userdata('start_time'), 0, 5);
                                                                                                                                        } else {
                                                                                                                                          echo $manual == 0 ? date('H:i:s') : date('H:i');
                                                                                                                                        } ?>" size="7" <?php echo ($manual == 0 ? "disabled" : "");  ?> required pattern="[0-2][0-9]:[0-5][0-9]">
                    </div>

                    <?php if ($manual == 0) { ?>
                      <input class="input_start_time" type="hidden" id="start_time" name="start_time" value="<?php echo date('H:i:s'); ?>" />
                      <input class="input_date" type="hidden" id="start_date" name="start_date" value="<?php echo date($user_date_format); ?>" />
                    <?php } ?>
                  </div>
                <?php } ?>

                <!-- Callsign Input -->
                <div class="row">
                  <div class="mb-3 col-md-9">
                    <label for="callsign"><?php echo lang('gen_hamradio_callsign'); ?></label><?php if ($this->optionslib->get_option('dxcache_url') != '') { ?>&nbsp;<i id="check_cluster" data-bs-toggle="tooltip" title="Search DXCluster for latest Spot" class="fas fa-search"></i> <?php } ?>
                  <input type="text" class="form-control" id="callsign" name="callsign" required>
                  <small id="callsign_info" class="badge text-bg-secondary"></small> <a id="lotw_link"><small id="lotw_info" class="badge text-bg-success"></small></a>
                  </div>
                  <div class="mb-3 col-md-3 align-self-center">
                    <small id="qrz_info" class="text-bg-secondary me-1"></small>
                    <small id="hamqth_info" class="text-bg-secondary me-1"></small>
                  </div>
                </div>

                <div class="row">
                  <div class="mb-3 col-6">
                    <label for="mode"><?php echo lang('gen_hamradio_mode'); ?></label>
                    <select id="mode" class="form-select mode form-select-sm" name="mode">
                      <?php
                      foreach ($modes->result() as $mode) {
                        if ($mode->submode == null) {
                          printf("<option value=\"%s\" %s>%s</option>", $mode->mode, $this->session->userdata('mode') == $mode->mode ? "selected=\"selected\"" : "", $mode->mode);
                        } else {
                          printf("<option value=\"%s\" %s>&rArr; %s</option>", $mode->submode, $this->session->userdata('mode') == $mode->submode ? "selected=\"selected\"" : "", $mode->submode);
                        }
                      }
                      ?>
                    </select>
                  </div>

                  <div class="mb-3 col-6">
                    <label for="band"><?php echo lang('gen_hamradio_band'); ?></label>

                    <select id="band" class="form-select form-select-sm" name="band">
                      <?php foreach ($bands as $key => $bandgroup) {
                        echo '<optgroup label="' . strtoupper($key) . '">';
                        foreach ($bandgroup as $band) {
                          echo '<option value="' . $band . '"';
                          if ($this->session->userdata('band') == $band || $user_default_band == $band) {
                            echo ' selected';
                          }
                          echo '>' . $band . '</option>' . "\n";
                        }
                        echo '</optgroup>';
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <!-- Signal Report Information -->
                <?php if ($qso_fields['rst']): ?>
                <div class="row">
                  <div class="mb-3 col-6">
                    <label for="rst_sent"><?php echo lang('gen_hamradio_rsts'); ?></label>
                    <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                  </div>

                  <div class="mb-3 col-6">
                    <label for="rst_rcvd"><?php echo lang('gen_hamradio_rstr'); ?></label>
                    <input type="text" class="form-control form-control-sm" name="rst_rcvd" id="rst_rcvd" value="59">
                  </div>
                </div>
                <?php else: ?>
                <input type="hidden" name="rst_sent" value="59">
                <input type="hidden" name="rst_rcvd" value="59">
                <?php endif; ?>

                <?php if ($qso_fields['name']): ?>
                <div class="mb-3 row">
                  <label for="name" class="col-sm-3 col-form-label"><?php echo lang('general_word_name'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                  </div>
                </div>
                <?php endif; ?>

                <?php if ($qso_fields['qth'] || $qso_fields['locator']): ?>
                <div class="d-lg-none mb-2">
                  <button class="btn btn-outline-secondary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#qsoMobileOptionalFields" aria-expanded="false" aria-controls="qsoMobileOptionalFields">
                    More QSO Fields
                  </button>
                </div>
                <div id="qsoMobileOptionalFields" class="collapse d-lg-block">
                <?php endif; ?>

                <?php if ($qso_fields['qth']): ?>
                <div class="mb-3 row">
                  <label for="qth" class="col-sm-3 col-form-label"><?php echo lang('general_word_location'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="qth" id="qth" value="">
                  </div>
                </div>
                <?php endif; ?>

                <?php if ($qso_fields['locator']): ?>
                <div class="mb-3 row">
                  <label for="locator" class="col-sm-3 col-form-label"><?php echo lang('gen_hamradio_gridsquare'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="locator" id="locator" value="">
                    <small id="locator_info" class="form-text text-muted"></small>
                  </div>
                </div>
                <?php endif; ?>

                <?php if ($qso_fields['qth'] || $qso_fields['locator']): ?>
                </div>
                <?php endif; ?>

                <input type="hidden" name="distance" id="distance" value="0">

                <?php if ($qso_fields['comment']): ?>
                <div class="mb-3 row">
                  <label for="comment" class="col-sm-3 col-form-label"><?php echo lang('general_word_comment'); ?></label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                  </div>
                </div>
                <?php endif; ?>

              </div>

              <?php if ($qso_fields['station_tab']): ?>
              <!-- Station Panel Data -->
              <div class="tab-pane fade" id="station" role="tabpanel" aria-labelledby="station-tab">
                <div class="mb-3">
                  <label for="stationProfile"><?php echo lang('cloudlog_station_profile'); ?></label>
                  <select id="stationProfile" class="form-select" name="station_profile">
                    <?php
                    $power = '';
                    foreach ($stations->result() as $stationrow) {
                    ?>
                      <option value="<?php echo $stationrow->station_id; ?>" <?php if ($active_station_profile == $stationrow->station_id) {
                                                                                echo "selected=\"selected\"";
                                                                                $power = $stationrow->station_power;
                                                                              } ?>><?php echo $stationrow->station_profile_name; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="radio"><?php echo lang('gen_hamradio_radio'); ?></label>
                  <select class="form-select radios" id="radio" name="radio">
                    <option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
                    <?php foreach ($radios->result() as $row) { ?>
                      <option value="<?php echo $row->id; ?>" <?php if ($this->session->userdata('radio') == $row->id) {
                                                                echo "selected=\"selected\"";
                                                              } ?>><?php echo $row->radio; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <?php if ($qso_fields['freq_tx']): ?>
                <div class="mb-3">
                  <label for="frequency"><?php echo lang('gen_hamradio_frequency'); ?></label>
                  <input type="text" class="form-control" id="frequency" name="freq_display" value="<?php echo $this->session->userdata('freq'); ?>" />
                </div>
                <?php endif; // freq_tx ?>

                <?php if ($qso_fields['freq_rx']): ?>
                <div class="mb-3">
                  <label for="frequency_rx"><?php echo lang('gen_hamradio_frequency_rx'); ?></label>
                  <input type="text" class="form-control" id="frequency_rx" name="freq_display_rx" value="<?php echo $this->session->userdata('freq_rx'); ?>" />
                </div>
                <?php endif; // freq_rx ?>

                <?php if ($qso_fields['band_rx']): ?>
                <div class="mb-3">
                  <label for="band_rx"><?php echo lang('gen_hamradio_band_rx'); ?></label>

                  <select id="band_rx" class="form-select" name="band_rx">
                    <option value="" <?php if ($this->session->userdata('band_rx') == "") {
                                        echo "selected=\"selected\"";
                                      } ?>></option>

                    <?php foreach ($bands as $key => $bandgroup) {
                      echo '<optgroup label="' . strtoupper($key) . '">';
                      foreach ($bandgroup as $band) {
                        echo '<option value="' . $band . '"';
                        if ($this->session->userdata('band_rx') == $band) echo ' selected';
                        echo '>' . $band . '</option>' . "\n";
                      }
                      echo '</optgroup>';
                    }
                    ?>
                  </select>
                </div>
                <?php endif; // band_rx ?>

                <?php if ($qso_fields['transmit_power']): ?>
                <div class="mb-3">
                  <label for="transmit_power"><?php echo lang('gen_hamradio_transmit_power'); ?></label>
                  <input type="number" step="0.001" class="form-control" id="transmit_power" name="transmit_power" value="<?php if ($this->session->userdata('transmit_power')) {
                                                                                                                            echo $this->session->userdata('transmit_power');
                                                                                                                          } else {
                                                                                                                            echo $power;
                                                                                                                          } ?>" />
                  <small id="powerHelp" class="form-text text-muted"><?php echo lang('qso_transmit_power_helptext'); ?></small>
                </div>
                <?php endif; // transmit_power ?>

                <?php if ($qso_fields['operator_callsign']): ?>
                <div class="mb-3">
                  <label for="operator_callsign"><?php echo lang('qso_operator_callsign'); ?></label>
                  <input type="text" class="form-control" id="operator_callsign" name="operator_callsign" value="<?php if ($this->session->userdata('operator_callsign')) {
                                                                                                                    echo $this->session->userdata('operator_callsign');
                                                                                                                  } ?>" />
                </div>
                <?php endif; // operator_callsign ?>

              </div>
              <?php endif; // station_tab ?>

              <?php if ($qso_fields['general_tab']): ?>
              <!-- General Items -->
              <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="mb-3">
                  <label for="dxcc_id"><?php echo lang('gen_hamradio_dxcc'); ?></label>
                  <select class="form-select" id="dxcc_id" name="dxcc_id" required>
                    <option value="0">- NONE -</option>
                    <?php
                    foreach ($dxcc as $d) {
                      echo '<option value=' . $d->adif . '>' . $d->prefix . ' - ' . ucwords(strtolower(($d->name)));
                      if ($d->Enddate != null) {
                        echo ' (' . lang('gen_hamradio_deleted_dxcc') . ')';
                      }
                      echo '</option>';
                    }
                    ?>

                  </select>
                </div>
                <div class="mb-3">
                  <label for="continent"><?php echo lang('gen_hamradio_continent'); ?></label>
                  <select class="form-select" id="continent" name="continent">
                    <option value=""></option>
                    <option value="AF"><?php echo lang('africa'); ?></option>
                    <option value="AN"><?php echo lang('antarctica'); ?></option>
                    <option value="AS"><?php echo lang('asia'); ?></option>
                    <option value="EU"><?php echo lang('europe'); ?></option>
                    <option value="NA"><?php echo lang('northamerica'); ?></option>
                    <option value="OC"><?php echo lang('oceania'); ?></option>
                    <option value="SA"><?php echo lang('southamerica'); ?></option>
                  </select>
                </div>
                <div class="mb-3">
                  <label for="cqz"><?php echo lang('gen_hamradio_cq_zone'); ?></label>
                  <select class="form-select" id="cqz" name="cqz" required>
                    <?php
                    for ($i = 0; $i <= 40; $i++) {
                      echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label for="selectPropagation"><?php echo lang('gen_hamradio_propagation_mode'); ?></label>
                  <select class="form-select" id="selectPropagation" name="prop_mode">
                    <option value="" <?php if (!empty($this->session->userdata('prop_mode'))) {
                                        echo "selected=\"selected\"";
                                      } ?>></option>
                    <option value="AS" <?php if ($this->session->userdata('prop_mode') == "AS") {
                                          echo "selected=\"selected\"";
                                        } ?>>Aircraft Scatter</option>
                    <option value="AUR" <?php if ($this->session->userdata('prop_mode') == "AUR") {
                                          echo "selected=\"selected\"";
                                        } ?>>Aurora</option>
                    <option value="AUE" <?php if ($this->session->userdata('prop_mode') == "AUE") {
                                          echo "selected=\"selected\"";
                                        } ?>>Aurora-E</option>
                    <option value="BS" <?php if ($this->session->userdata('prop_mode') == "BS") {
                                          echo "selected=\"selected\"";
                                        } ?>>Back scatter</option>
                    <option value="ECH" <?php if ($this->session->userdata('prop_mode') == "ECH") {
                                          echo "selected=\"selected\"";
                                        } ?>>EchoLink</option>
                    <option value="EME" <?php if ($this->session->userdata('prop_mode') == "EME") {
                                          echo "selected=\"selected\"";
                                        } ?>>Earth-Moon-Earth</option>
                    <option value="ES" <?php if ($this->session->userdata('prop_mode') == "ES") {
                                          echo "selected=\"selected\"";
                                        } ?>>Sporadic E</option>
                    <option value="FAI" <?php if ($this->session->userdata('prop_mode') == "FAI") {
                                          echo "selected=\"selected\"";
                                        } ?>>Field Aligned Irregularities</option>
                    <option value="F2" <?php if ($this->session->userdata('prop_mode') == "F2") {
                                          echo "selected=\"selected\"";
                                        } ?>>F2 Reflection</option>
                    <option value="INTERNET" <?php if ($this->session->userdata('prop_mode') == "INTERNET") {
                                                echo "selected=\"selected\"";
                                              } ?>>Internet-assisted</option>
                    <option value="ION" <?php if ($this->session->userdata('prop_mode') == "ION") {
                                          echo "selected=\"selected\"";
                                        } ?>>Ionoscatter</option>
                    <option value="IRL" <?php if ($this->session->userdata('prop_mode') == "IRL") {
                                          echo "selected=\"selected\"";
                                        } ?>>IRLP</option>
                    <option value="MS" <?php if ($this->session->userdata('prop_mode') == "MS") {
                                          echo "selected=\"selected\"";
                                        } ?>>Meteor scatter</option>
                    <option value="RPT" <?php if ($this->session->userdata('prop_mode') == "RPT") {
                                          echo "selected=\"selected\"";
                                        } ?>>Terrestrial or atmospheric repeater or transponder</option>
                    <option value="RS" <?php if ($this->session->userdata('prop_mode') == "RS") {
                                          echo "selected=\"selected\"";
                                        } ?>>Rain scatter</option>
                    <option value="SAT" <?php if ($this->session->userdata('prop_mode') == "SAT") {
                                          echo "selected=\"selected\"";
                                        } ?>>Satellite</option>
                    <option value="TEP" <?php if ($this->session->userdata('prop_mode') == "TEP") {
                                          echo "selected=\"selected\"";
                                        } ?>>Trans-equatorial</option>
                    <option value="TR" <?php if ($this->session->userdata('prop_mode') == "TR") {
                                          echo "selected=\"selected\"";
                                        } ?>>Tropospheric ducting</option>
                  </select>
                </div>

                <?php if ($qso_fields['usa_state']): ?>
                <div class="mb-3" id="usa_state_field_wrapper" style="display:none">
                  <label for="input_usa_state"><?php echo lang('gen_hamradio_usa_state'); ?></label>
                  <select class="form-select" id="input_usa_state" name="usa_state">
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

                <div class="mb-3" id="usa_county_field_wrapper" style="display:none">
                  <label for="stationCntyInput"><?php echo lang('gen_hamradio_county_reference'); ?></label>
                  <input disabled="disabled" class="form-control" id="stationCntyInput" type="text" name="county" value="" />
                </div>
                <?php endif; // usa_state ?>

                <?php if ($qso_fields['iota']): ?>
                <div class="mb-3">
                  <label for="iota_ref"><?php echo lang('gen_hamradio_iota_reference'); ?></label>
                  <select class="form-select" id="iota_ref" name="iota_ref">
                    <option value=""></option>

                    <?php
                    foreach ($iota as $i) {
                      echo '<option value=' . $i->tag . '>' . $i->tag . ' - ' . $i->name . '</option>';
                    }
                    ?>

                  </select>
                </div>
                <?php endif; // iota ?>

                <?php if ($qso_fields['sota']): ?>
                <div class="row">
                  <div class="mb-3 col-md-9">
                    <label for="sota_ref"><?php echo lang('gen_hamradio_sota_reference'); ?></label>
                    <input class="form-control" id="sota_ref" type="text" name="sota_ref" value="" />
                    <small id="sotaRefHelp" class="form-text text-muted"><?php echo lang('qso_sota_ref_helptext'); ?></small>
                  </div>
                  <div class="mb-3 col-md-3 align-self-center">
                    <small id="sota_info" class="badge text-bg-secondary"></small>
                  </div>
                </div>
                <?php endif; // sota ?>

                <?php if ($qso_fields['wwff']): ?>
                <div class="row">
                  <div class="mb-3 col-md-9">
                    <label for="wwff_ref"><?php echo lang('gen_hamradio_wwff_reference'); ?></label>
                    <input class="form-control" id="wwff_ref" type="text" name="wwff_ref" value="" />
                    <small id="wwffRefHelp" class="form-text text-muted"><?php echo lang('qso_wwff_ref_helptext'); ?></small>
                  </div>
                  <div class="mb-3 col-md-3 align-self-center">
                    <small id="wwff_info" class="badge text-bg-secondary"></small>
                  </div>
                </div>
                <?php endif; // wwff ?>

                <?php if ($qso_fields['pota']): ?>
                <div class="row">
                  <div class="mb-3 col-md-9">
                    <label for="pota_ref"><?php echo lang('gen_hamradio_pota_reference'); ?></label>
                    <input class="form-control" id="pota_ref" type="text" name="pota_ref" value="" />
                    <small id="potaRefHelp" class="form-text text-muted"><?php echo lang('qso_pota_ref_helptext'); ?></small>
                  </div>
                  <div class="mb-3 col-md-3 align-self-center">
                    <small id="pota_info" class="badge text-bg-secondary"></small>
                  </div>
                </div>
                <?php endif; // pota ?>

                <?php if ($qso_fields['sig']): ?>
                <div class="mb-3">
                  <label for="sig"><?php echo lang('gen_hamradio_sig'); ?></label>
                  <input class="form-control" id="sig" type="text" name="sig" value="" />
                  <small id="sigHelp" class="form-text text-muted"><?php echo lang('qso_sig_helptext'); ?></small>
                </div>

                <div class="mb-3">
                  <label for="sig_info"><?php echo lang('gen_hamradio_sig_info'); ?></label>
                  <input class="form-control" id="sig_info" type="text" name="sig_info" value="" />
                  <small id="sigInfoHelp" class="form-text text-muted"><?php echo lang('qso_sig_info_helptext'); ?></small>
                </div>
                <?php endif; // sig ?>

                <div class="mb-3" id="dok_field_wrapper" style="display:none"<?php if (!$qso_fields['dok']): ?> data-user-hidden="true"<?php endif; ?>>
                  <label for="darc_dok"><?php echo lang('gen_hamradio_dok'); ?></label>
                  <input class="form-control" id="darc_dok" type="text" name="darc_dok" value="" />
                  <small id="dokHelp" class="form-text text-muted"><?php echo lang('qso_dok_helptext'); ?></small>
                </div>
              </div>
              <?php endif; // general_tab ?>

              <?php if ($sat_active && $qso_fields['satellite_tab']): ?>
              <!-- Satellite Panel -->
              <div class="tab-pane fade" id="satellite" role="tabpanel" aria-labelledby="satellite-tab">
                <div class="mb-3">
                  <label for="sat_name"><?php echo lang('gen_hamradio_satellite_name'); ?></label>

                  <input list="satellite_names" id="sat_name" type="text" name="sat_name" class="form-control" value="<?php echo $this->session->userdata('sat_name'); ?>">

                  <datalist id="satellite_names" class="satellite_names_list"></datalist>
                </div>

                <div class="mb-3">
                  <label for="sat_mode"><?php echo lang('gen_hamradio_satellite_mode'); ?></label>

                  <input list="satellite_modes" id="sat_mode" type="text" name="sat_mode" class="form-control" value="<?php echo $this->session->userdata('sat_mode'); ?>">

                  <datalist id="satellite_modes" class="satellite_modes_list"></datalist>
                </div>
              </div>
              <?php endif; // satellite_tab ?>

              <?php if ($qso_fields['notes_tab']): ?>
              <!-- Notes Panel Contents -->
              <div class="tab-pane fade" id="nav-notes" role="tabpanel" aria-labelledby="notes-tab">
                <div class="alert alert-info" role="alert">
                  <span class="badge text-bg-info"><?php echo lang('general_word_info'); ?></span> <?php echo lang('qso_notes_helptext'); ?>
                </div>
                <div class="mb-3">
                  <label for="notes"><?php echo lang('general_word_notes'); ?></label>
                  <textarea type="text" class="form-control" id="notes" name="notes" rows="10"></textarea>
                </div>
              </div>
              <?php endif; // notes_tab ?>

              <?php if ($qso_fields['qsl_tab']): ?>
              <!-- QSL Tab -->
              <div class="tab-pane fade" id="qsl" role="tabpanel" aria-labelledby="qsl-tab">

                <div class="mb-3 row">
                  <label for="sent" class="col-sm-3 col-form-label"><?php echo lang('general_word_sent'); ?></label>
                  <div class="col-sm-9">
                    <select class="form-select" id="sent" name="qsl_sent">
                      <option value="N" selected="selected"><?php echo lang('general_word_no'); ?></option>
                      <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                      <option value="R"><?php echo lang('general_word_requested'); ?></option>
                      <option value="Q"><?php echo lang('general_word_queued'); ?></option>
                      <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="sent-method" class="col-sm-3 col-form-label"><?php echo lang('general_word_method'); ?></label>
                  <div class="col-sm-9">
                    <select class="form-select" id="sent-method" name="qsl_sent_method">
                      <option value="" selected="selected"><?php echo lang('general_word_method'); ?></option>
                      <option value="D"><?php echo lang('general_word_qslcard_direct'); ?></option>
                      <option value="B"><?php echo lang('general_word_qslcard_bureau'); ?></option>
                      <option value="E"><?php echo lang('general_word_qslcard_electronic'); ?></option>
                      <option value="M"><?php echo lang('general_word_qslcard_manager'); ?></option>
                    </select>
                  </div>
                </div>

                <div class="mb-3 row">
                  <label for="qsl_via" class="col-sm-2 col-form-label"><?php echo lang('general_word_qslcard_via'); ?></label>
                  <div class="col-sm-10">
                    <input type="text" id="qsl_via" class="form-control" name="qsl_via" value="" />
                  </div>
                </div>

                <div class="alert alert-info" role="alert">
                  <span class="badge text-bg-info"><?php echo lang('general_word_info'); ?></span> <?php echo lang('qsl_notes_helptext'); ?>
                </div>
                <div class="mb-3">
                  <label for="qslmsg"><?php echo lang('general_word_notes'); ?><span class="qso_eqsl_qslmsg_update" title="<?php echo lang('qso_eqsl_qslmsg_helptext'); ?>"><i class="fas fa-redo-alt"></i></span></label>
                  <label class="position-absolute end-0 mb-2 me-3" for="qslmsg" id="charsLeft"> </label>
                  <textarea type="text" class="form-control" id="qslmsg" name="qslmsg" rows="5" maxlength="240"><?php echo $qslmsg; ?></textarea>
                  <div id="qslmsg_hide" style="display:none;"><?php echo $qslmsg; ?></div>
                </div>
              </div>
              <?php endif; // qsl_tab ?>
            </div>



            <div class="info">
              <input size="20" id="country" type="hidden" name="country" value="" />
            </div>

            <button type="reset" class="btn btn-secondary" onclick="reset_fields()"><?php echo lang('qso_btn_reset_qso'); ?></button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> <?php echo lang('qso_btn_save_qso'); ?></button>
            <div class="alert alert-danger warningOnSubmit mt-3" style="display:none;"><span><i class="fas fa-times-circle"></i></span> <span class="warningOnSubmit_txt ms-1">Error</span></div>
          </div>
        </form>
      </div>
    </div>


    <div class="col-lg-7">

      <div id="notice-alerts-container">

      <?php if ($notice) { ?>
        <div id="notice-alerts" class="alert alert-info" role="alert">
          <?php echo $notice; ?>
        </div>
      <?php } ?>

      <?php if (validation_errors()) { ?>
        <div id="notice-alerts" class="alert alert-warning" role="alert">
          <?php echo validation_errors(); ?>
        </div>
      <?php } ?>

      </div>

      <!-- QSO Map -->
      <div class="card qso-map d-none d-md-block">
        <div id="qsomap" class="map-leaflet" style="width: 100%; height: 200px;"></div>
      </div>

      <div id="radio_status"></div>

      <div class="radio_selection d-flex align-items-center mb-3">
        <label for="radio" class="form-label me-3">Select Radio</label>
        <select class="form-select radios" id="radio" name="radio" style="flex: 1;">
          <option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
          <?php foreach ($radios->result() as $row) { ?>
            <option value="<?php echo $row->id; ?>" <?php if ($this->session->userdata('radio') == $row->id) {
                                                      echo "selected=\"selected\"";
                                                    } ?>><?php echo $row->radio; ?></option>
          <?php } ?>
        </select>

        <button class="btn btn-primary" onclick="openBandmap()">Open Bandmap</button>
      </div>

      <?php if (isset($isRemoteOperationEnabled) ? $isRemoteOperationEnabled : $this->session->userdata('isRemoteOperationEnabled')) { ?>
        <div id="remote_operation" class="card remote-operation-settings" style="margin-bottom: 10px;">
          <div class="card-header">
            <h4 style="font-size: 16px; font-weight: bold;" class="card-title">Remote Operation

              <span class="badge text-bg-danger ms-2"><?php echo lang('admin_experimental'); ?></span>

              <div id="remote_operation_status" class="badge text-bg-secondary ms-2">Status: Disconnected</div>

              <button type="button" class="btn btn-sm btn-primary ms-2" id="remoteOperationConnectButton">Connect</button>

              <button type="button" class="btn btn-sm btn-secondary"
                id="remoteOperationSettingsButton"
                hx-get="<?php echo base_url(); ?>index.php/qso/remoteoperationsettings"
                hx-target="#remote-modals-here"
                hx-trigger="click"
                _="on htmx:afterOnLoad wait 10ms then add .show to #modal then add .show to #modal-backdrop"><i class="fas fa-sliders-h"></i> Settings</button>
            </h4>
          </div>

          <audio id="remoteOperationRx" autoplay playsinline style="display:none;"></audio>

          <div id="remote-modals-here"></div>

          <div class="card-body">
            <p class="mb-2">Experimental browser-based shack audio bridge. Use Settings to configure the signalling server, link and audio devices.</p>
            <div id="remoteOperationDeviceWarning" class="alert alert-warning py-2 px-3 small mb-2 d-none" role="alert"></div>
            <div class="row g-2 align-items-center mb-2">
              <div class="col-12 col-md-6">
                <div class="small text-muted mb-1">Mic level</div>
                <div class="progress" role="progressbar" aria-label="Remote operation microphone level" aria-valuemin="0" aria-valuemax="100">
                  <div id="remoteOperationMicLevel" class="progress-bar bg-primary" style="width: 0%">0%</div>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <div class="small text-muted mb-1">Receive level</div>
                <div class="progress" role="progressbar" aria-label="Remote operation receive level" aria-valuemin="0" aria-valuemax="100">
                  <div id="remoteOperationRxLevel" class="progress-bar bg-success" style="width: 0%">0%</div>
                </div>
              </div>
            </div>
            <div class="row g-2 align-items-center mb-2">
              <div class="col-12 col-md-6">
                <label for="remoteOperationPlaybackVolume" class="form-label small text-muted mb-1">Playback volume</label>
                <input id="remoteOperationPlaybackVolume" type="range" class="form-range" min="0" max="100" step="1" value="50">
              </div>
              <div class="col-12 col-md-6 d-flex align-items-center gap-3">
                <div class="form-check mb-0 mt-3">
                  <input class="form-check-input" type="checkbox" id="remoteOperationMuteToggle">
                  <label class="form-check-label small" for="remoteOperationMuteToggle">Mute playback</label>
                </div>
                <button type="button" id="remoteOperationEnableAudioButton" class="btn btn-sm btn-outline-warning mt-2 d-none">Enable Audio</button>
              </div>
            </div>
            <div id="remoteOperationAudioUnlockHint" class="small text-warning mb-2 d-none"></div>
            <div id="remoteOperationQuickDiagnostics" class="small text-muted mb-2">State: Disconnected | speaker: default | inbound packets: 0 | jitter: n/a | reconnects: 0</div>
            <small class="text-muted d-block">The card stays hidden unless you enable Remote Operation in your account settings.</small>
          </div>
        </div>
      <?php } ?>


      <!-- Winkey Starts -->

      <?php if ($this->session->userdata('isWinkeyEnabled') && $this->session->userdata('isWinkeyWebsocketEnabled')) { ?>
        <div id="winkey" class="card winkey-settings" style="margin-bottom: 10px; display: none;">
          <div class="card-header">
            <h4 style="font-size: 16px; font-weight: bold;" class="card-title">Winkey Web Sockets

              <div id="cw_socket_status" class="badge text-bg-danger">
                Status: Disconnected
              </div>

              <button id="toggleLogButton" onclick="toggleMessageLog()" class="btn btn-sm btn-outline-secondary" title="Toggle Message Log">
                <i class="fas fa-list"></i>
              </button>

              <button type="button" class="btn btn-sm btn-secondary"
                hx-get="<?php echo base_url(); ?>index.php/qso/winkeysettings"
                hx-target="#modals-here"
                hx-trigger="click"
                _="on htmx:afterOnLoad wait 10ms then add .show to #modal then add .show to #modal-backdrop"><i class="fas fa-keyboard"></i> Macros</button>

              <button type="button" class="btn btn-sm btn-secondary" onclick="openWinkeyRelaySettings()"><i class="fas fa-network-wired"></i> Relay</button>
            </h4>
          </div>

          <div id="modals-here"></div>

          <div class="modal fade" id="winkeyRelaySettingsModal" tabindex="-1" aria-labelledby="winkeyRelaySettingsLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="winkeyRelaySettingsLabel">Winkey Relay Settings</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="winkeyRelayEnabled">
                    <label class="form-check-label" for="winkeyRelayEnabled">Use Relay Server</label>
                  </div>

                  <div class="mb-3">
                    <label for="winkeyRelayUrl" class="form-label">Relay WebSocket URL</label>
                    <input type="text" class="form-control" id="winkeyRelayUrl" placeholder="wss://relay.cloudlog.org/">
                  </div>

                  <div class="mb-3">
                    <label for="winkeyRelayToken" class="form-label">Relay Token</label>
                    <input type="password" class="form-control" id="winkeyRelayToken" autocomplete="off" placeholder="Shared secret (min 8 chars)">
                  </div>

                  <div class="mb-2">
                    <label for="winkeyRelayRoom" class="form-label">Relay Room</label>
                    <input type="text" class="form-control" id="winkeyRelayRoom" placeholder="cw_room">
                  </div>

                  <small class="text-muted">Relay settings are stored in your user account and follow your login across devices.</small>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="saveWinkeyRelaySettings">Save</button>
                </div>
              </div>
            </div>
          </div>

          <div id="winkey_buttons" class="card-body">
            <!-- Function Keys Row -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="btn-group" role="group" aria-label="CW Function Keys">
                  <button id="morsekey_func1" onclick="morsekey_func1()" class="btn btn-warning">F1</button>
                  <button id="morsekey_func2" onclick="morsekey_func2()" class="btn btn-warning">F2</button>
                  <button id="morsekey_func3" onclick="morsekey_func3()" class="btn btn-warning">F3</button>
                  <button id="morsekey_func4" onclick="morsekey_func4()" class="btn btn-warning">F4</button>
                  <button id="morsekey_func5" onclick="morsekey_func5()" class="btn btn-warning">F5</button>
                </div>
              </div>
            </div>

            <div class="row g-2 mb-3 align-items-center">
              <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-2 justify-content-lg-start justify-content-center">
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="cwSidetoneEnabled">
                    <label class="form-check-label" for="cwSidetoneEnabled">Speaker Sidetone</label>
                  </div>
                  <span id="cwSidetoneStatus" class="badge bg-secondary">Off</span>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                  <label for="cwSidetoneFrequency" class="mb-0 fw-bold text-nowrap">Tone</label>
                  <input id="cwSidetoneFrequency" type="range" class="form-range flex-grow-1" min="300" max="1000" step="10">
                  <span id="cwSidetoneFrequencyValue" class="badge bg-secondary text-nowrap">600 Hz</span>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-lg-end">
                  <label for="cwSidetoneVolume" class="mb-0 fw-bold text-nowrap">Volume</label>
                  <input id="cwSidetoneVolume" type="range" class="form-range flex-grow-1" min="0.01" max="0.2" step="0.01">
                  <span id="cwSidetoneVolumeValue" class="badge bg-secondary text-nowrap">5%</span>
                </div>
              </div>
            </div>

            <!-- CW Speed Control Row -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="d-flex align-items-center justify-content-center">
                  <span class="me-3 fw-bold">CW Speed:</span>
                  <div class="btn-group me-2" role="group" aria-label="Speed Control">
                    <button id="cwSpeedDown" onclick="adjustCwSpeed(-1)" class="btn btn-outline-secondary btn-sm" type="button">-</button>
                    <button id="cwSpeedUp" onclick="adjustCwSpeed(1)" class="btn btn-outline-secondary btn-sm" type="button">+</button>
                  </div>
                  <span id="cwSpeedDisplay" class="badge bg-info fs-6 px-3 py-2">-- WPM</span>
                </div>
              </div>
            </div>

            <!-- Send Message Row -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="input-group">
                  <input id="sendText" type="text" class="form-control" placeholder="Enter CW message..." aria-label="CW Message">
                  <button onclick="sendMyMessage()" id="sendButton" type="button" class="btn btn-success">Send</button>
                </div>
              </div>
            </div>

            <!-- Message Log Container -->
            <div id="messageLogContainer" style="display: none;">
              <div class="row">
                <div class="col-12">
                  <label class="form-label fw-bold">Message Log:</label>
                  <textarea id="messageLog" class="form-control" rows="4" readonly placeholder="WebSocket messages will appear here..."></textarea>
                </div>
              </div>
            </div>

          </div>
        </div>
      <?php } ?>


      <?php
      // if isWinkeyEnabled in session data is true and isWinkeyWebsocketEnabled  is false

      if ($this->session->userdata('isWinkeyEnabled') && !$this->session->userdata('isWinkeyWebsocketEnabled')) { ?>

        <div id="winkey" class="card winkey-settings" style="margin-bottom: 10px; display: none;">
          <div class="card-header">
            <h4 style="font-size: 16px; font-weight: bold;" class="card-title">Winkey

              <button id="connectButton" class="btn btn-primary">Connect</button>

              <button type="button" class="btn btn-sm btn-secondary"
                hx-get="<?php echo base_url(); ?>index.php/qso/winkeysettings"
                hx-target="#modals-here"
                hx-trigger="click"
                _="on htmx:afterOnLoad wait 10ms then add .show to #modal then add .show to #modal-backdrop"><i class="fas fa-cog"></i> Settings</button>
            </h4>
          </div>

          <div id="modals-here"></div>

          <div id="winkey_buttons" class="card-body">
            <!-- Function Keys Row -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="btn-group" role="group" aria-label="CW Function Keys">
                  <button id="morsekey_func1" onclick="morsekey_func1()" class="btn btn-warning">F1</button>
                  <button id="morsekey_func2" onclick="morsekey_func2()" class="btn btn-warning">F2</button>
                  <button id="morsekey_func3" onclick="morsekey_func3()" class="btn btn-warning">F3</button>
                  <button id="morsekey_func4" onclick="morsekey_func4()" class="btn btn-warning">F4</button>
                  <button id="morsekey_func5" onclick="morsekey_func5()" class="btn btn-warning">F5</button>
                </div>
              </div>
            </div>

            <div class="row g-2 mb-3 align-items-center">
              <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-2 justify-content-lg-start justify-content-center">
                  <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="cwSidetoneEnabled">
                    <label class="form-check-label" for="cwSidetoneEnabled">Speaker Sidetone</label>
                  </div>
                  <span id="cwSidetoneStatus" class="badge bg-secondary">Off</span>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-2 justify-content-center">
                  <label for="cwSidetoneFrequency" class="mb-0 fw-bold text-nowrap">Tone</label>
                  <input id="cwSidetoneFrequency" type="range" class="form-range flex-grow-1" min="300" max="1000" step="10">
                  <span id="cwSidetoneFrequencyValue" class="badge bg-secondary text-nowrap">600 Hz</span>
                </div>
              </div>
              <div class="col-12 col-lg-4">
                <div class="d-flex align-items-center gap-2 justify-content-center justify-content-lg-end">
                  <label for="cwSidetoneVolume" class="mb-0 fw-bold text-nowrap">Volume</label>
                  <input id="cwSidetoneVolume" type="range" class="form-range flex-grow-1" min="0.01" max="0.2" step="0.01">
                  <span id="cwSidetoneVolumeValue" class="badge bg-secondary text-nowrap">5%</span>
                </div>
              </div>
            </div>

            <!-- Send Message Row -->
            <div class="row mb-3">
              <div class="col-12">
                <div class="input-group">
                  <input id="sendText" type="text" class="form-control" placeholder="Enter CW message..." aria-label="CW Message">
                  <button id="sendButton" type="button" class="btn btn-success">Send</button>
                </div>
              </div>
            </div>

            <!-- Status Bar Row -->
            <div class="row">
              <div class="col-12">
                <div class="alert alert-secondary py-2 mb-0" role="status">
                  <span id="statusBar" class="mb-0">Ready</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      <?php } // end of isWinkeyEnabled if statement 
      ?>
      <!-- Winkey Ends -->

      <div class="card callsign-suggest">
        <div class="card-header">
          <h4 style="font-size: 16px; font-weight: bold;" class="card-title"><?php echo lang('qso_title_suggestions'); ?></h4>
        </div>

        <div class="card-body callsign-suggestions"></div>
      </div>

      <?php if ($this->session->userdata('user_show_profile_image')) { ?>
        <div class="card callsign-image" id="callsign-image" style="display: none;">
          <div class="card-header">
            <h4 style="font-size: 16px; font-weight: bold;" class="card-title"><?php echo lang('qso_title_image'); ?></h4>
          </div>

          <div class="card-body callsign-image">
            <div class="callsign-image-content" id="callsign-image-content">
            </div>
          </div>
        </div>
      <?php } ?>

      <div class="card previous-qsos">
        <div class="card-header">
          <ul class="nav nav-tabs card-header-tabs" id="qsoRightTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="previous-contacts-tab" data-bs-toggle="tab" data-bs-target="#previous-contacts-pane" type="button" role="tab" aria-controls="previous-contacts-pane" aria-selected="true">
                <?php echo lang('qso_title_previous_contacts'); ?>
              </button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="dxcc-summary-tab" data-bs-toggle="tab" data-bs-target="#dxcc-summary-pane" type="button" role="tab" aria-controls="dxcc-summary-pane" aria-selected="false">
                DXCC Summary
              </button>
            </li>
            <?php if ($qso_fields['dxcluster_tab']): ?>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="dx-cluster-tab" data-bs-toggle="tab" data-bs-target="#dx-cluster-pane" type="button" role="tab" aria-controls="dx-cluster-pane" aria-selected="false">
                DX Cluster
              </button>
            </li>
            <?php endif; ?>
          </ul>
        </div>

        <div class="card-body">
          <div class="tab-content" id="qsoRightTabContent">
            <!-- Previous Contacts Tab -->
            <div class="tab-pane fade show active" id="previous-contacts-pane" role="tabpanel" aria-labelledby="previous-contacts-tab">
              <div id="qso-callhistory-inline" class="px-0 pt-2 pb-1 border-bottom" style="display: none;">
                <div id="qso-callhistory-results"></div>
              </div>

              <div id="partial_view" style="display: none; font-size: 0.95rem;"></div>

              <div id="qso-last-table" hx-get="<?php echo site_url('/qso/component_past_contacts'); ?>" hx-trigger="load">

              </div>
            </div>

            <!-- DXCC Summary Tab -->
            <div class="tab-pane fade" id="dxcc-summary-pane" role="tabpanel" aria-labelledby="dxcc-summary-tab">
              <div id="dxcc-summary-content">
                <!-- DXCC Summary content will be loaded here -->
              </div>
            </div>

            <!-- DX Cluster Tab -->
            <?php if ($qso_fields['dxcluster_tab']): ?>
            <div class="tab-pane fade" id="dx-cluster-pane" role="tabpanel" aria-labelledby="dx-cluster-tab">
              <div class="d-flex align-items-center gap-2 flex-wrap pt-2 pb-1 border-bottom mb-1">
                <span id="qso-cluster-status" class="badge bg-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Disconnected from DX Cluster" style="font-size:0.85rem; cursor:default;"><i id="qso-cluster-status-icon" class="fas fa-circle"></i></span>
                <select id="qso-cluster-band" class="form-select form-select-sm" style="width:auto;font-size:0.8rem;">
                  <option value="all">All Bands</option>
                  <option value="160m">160m</option>
                  <option value="80m">80m</option>
                  <option value="60m">60m</option>
                  <option value="40m">40m</option>
                  <option value="30m">30m</option>
                  <option value="20m">20m</option>
                  <option value="17m">17m</option>
                  <option value="15m">15m</option>
                  <option value="12m">12m</option>
                  <option value="10m">10m</option>
                  <option value="6m">6m</option>
                  <option value="4m">4m</option>
                  <option value="2m">2m</option>
                  <option value="70cm">70cm</option>
                  <option value="23cm">23cm</option>
                  <option value="ghz">GHz+</option>
                </select>
                <select id="qso-cluster-mode" class="form-select form-select-sm" style="width:auto;font-size:0.8rem;">
                  <option value="all">All Modes</option>
                  <option value="cw">CW</option>
                  <option value="ssb">SSB</option>
                  <option value="digi">Digital</option>
                </select>
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="qso-cluster-hide-rbn" checked>
                  <label class="form-check-label" for="qso-cluster-hide-rbn" style="font-size:0.8rem;">Hide RBN</label>
                </div>
                <select id="qso-cluster-new-dxcc" class="form-select form-select-sm" style="width:auto;font-size:0.8rem;">
                  <option value="all">All Spots</option>
                  <option value="new_any">New DXCC or Band</option>
                  <option value="new_dxcc">New DXCC only</option>
                  <option value="new_band">New Band only</option>
                </select>
                <div class="form-check mb-0">
                  <input class="form-check-input" type="checkbox" id="qso-cluster-track-band">
                  <label class="form-check-label" for="qso-cluster-track-band" style="font-size:0.8rem;">Track Band</label>
                </div>
              </div>
              <div>
                <table class="table table-sm table-striped table-hover mb-0" id="qso-cluster-table">
                  <thead>
                    <tr>
                      <th>Time</th>
                      <th>DX Call</th>
                      <th>Freq</th>
                      <th>Comment</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
              <small class="text-muted d-block pt-1"><i class="fas fa-mouse-pointer"></i> Click a spot to fill callsign &amp; frequency</small>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Custom Leave QSO Entry Modal -->
<div class="modal fade" id="leaveQsoModal" tabindex="-1" aria-labelledby="leaveQsoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title" id="leaveQsoModalLabel">
          <i class="fas fa-exclamation-triangle me-2"></i>Leave QSO Entry?
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Are you sure you want to leave QSO entry? Any unsaved changes will be lost.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-1"></i>Stay on Page
        </button>
        <button type="button" class="btn btn-warning" id="confirmLeaveQso">
          <i class="fas fa-sign-out-alt me-1"></i>Leave Page
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Callsign Lookup Overwrite Approval Modal -->
<div class="modal fade" id="callsignOverwriteModal" tabindex="-1" aria-labelledby="callsignOverwriteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="callsignOverwriteModalLabel">
          <i class="fas fa-user-edit me-2"></i>Update Existing Fields?
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">The corrected callsign returned different details from lookup sources and your previous QSO history.</p>
        <p class="mb-3">Choose which existing fields you want to replace:</p>
        <div id="callsignOverwriteConflicts"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="callsignOverwriteKeepExisting">Keep Existing</button>
        <button type="button" class="btn btn-primary" id="callsignOverwriteReplaceSelected">Replace Selected</button>
        <button type="button" class="btn btn-success" id="callsignOverwriteReplaceAll">Replace All</button>
      </div>
    </div>
  </div>
</div>

</div>

<script>
  // Show USA State & County fields only when DXCC is USA (291) or Alaska (6)
  function toggleUsaFields() {
    var stateWrapper = document.getElementById('usa_state_field_wrapper');
    var countyWrapper = document.getElementById('usa_county_field_wrapper');
    if (!stateWrapper) return;
    var dxcc = document.getElementById('dxcc_id');
    var isUsa = dxcc && (dxcc.value == '291' || dxcc.value == '6');
    stateWrapper.style.display = isUsa ? '' : 'none';
    countyWrapper.style.display = isUsa ? '' : 'none';
    if (!isUsa) {
      var stateSelect = document.getElementById('input_usa_state');
      if (stateSelect) stateSelect.value = '';
    }
  }

  // Show DOK field only when DXCC is Germany (230)
  function toggleDokField() {
    var wrapper = document.getElementById('dok_field_wrapper');
    if (!wrapper || wrapper.dataset.userHidden === 'true') return;
    var dxcc = document.getElementById('dxcc_id');
    if (dxcc && dxcc.value == '230') {
      wrapper.style.display = '';
    } else {
      wrapper.style.display = 'none';
      var dokInput = document.getElementById('darc_dok');
      if (dokInput) dokInput.value = '';
    }
  }

  // Initialise on page load once DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    toggleDokField();
    toggleUsaFields();
  });


  // Handle the confirm leave button for the custom modal (vanilla JS to avoid jQuery dependency)
  document.addEventListener('DOMContentLoaded', function() {
    var confirmBtn = document.getElementById('confirmLeaveQso');
    if (confirmBtn) {
      confirmBtn.addEventListener('click', function() {
        if (window.pendingNavigation) {
          // Unbind beforeunload to allow navigation
          window.jQuery && window.jQuery(window).unbind('beforeunload');
          // Navigate to the pending URL
          window.location.href = window.pendingNavigation;
        }
      });
    }
  });

  function openBandmap() {
    // Open bandmap in a new window without URL bar, toolbars, etc.
    const width = 500;
    const height = 800;
    const left = (screen.width - width) / 2;
    const top = (screen.height - height) / 2;

    // Note: Modern browsers may still show address bar due to security restrictions
    // For Chrome, you can use: chrome.exe --app=http://localhost/index.php/dxcluster/bandmap
    const features = `width=${width},height=${height},left=${left},top=${top},` +
      `toolbar=no,location=no,directories=no,status=no,menubar=no,` +
      `scrollbars=yes,resizable=yes,copyhistory=no`;

    const popup = window.open('<?php echo site_url('dxcluster/bandmap'); ?>', 'bandmap', features);

    // Try to make it fullscreen (user will need to allow this)
    if (popup) {
      popup.focus();
    }
  }

  function openBandmapFullscreen() {
    // Alternative: Open in current window and go fullscreen
    window.location.href = '<?php echo site_url('dxcluster/bandmap'); ?>';
  }
</script>

<?php if ($qso_fields['dxcluster_tab']): ?>
<script src="<?php echo base_url(); ?>assets/js/dxcluster-utils.js"></script>
<script>
(function() {
  'use strict';

  var qsoCluster = {
    ws: null,
    spots: new Map(),
    workedStatus: {},
    checkWorkedTimeout: null,
    initialized: false,
    hideRbn: true,
    trackBand: false,
    selectedBand: 'all',
    selectedMode: 'all',
    selectedNewDxcc: 'all',
    dataTable: null,

    init: function() {
      if (this.initialized) return;
      this.initialized = true;

      // Load saved filter preferences (shared with main DX Cluster page)
      var savedRbn = localStorage.getItem('cloudlog_hideRbnSpots');
      if (savedRbn !== null) {
        this.hideRbn = savedRbn === 'true';
      }
      var rbnChk = document.getElementById('qso-cluster-hide-rbn');
      if (rbnChk) rbnChk.checked = this.hideRbn;

      var savedTrack = localStorage.getItem('cloudlog_clusterTrackBand');
      if (savedTrack !== null) {
        this.trackBand = savedTrack === 'true';
      }
      var trackChk = document.getElementById('qso-cluster-track-band');
      if (trackChk) trackChk.checked = this.trackBand;

      var savedBand = localStorage.getItem('cloudlog_bandFilter');
      if (savedBand !== null) {
        this.selectedBand = savedBand;
        var bandSel = document.getElementById('qso-cluster-band');
        if (bandSel) bandSel.value = savedBand;
      }

      var savedMode = localStorage.getItem('cloudlog_modeFilter');
      if (savedMode !== null) {
        this.selectedMode = savedMode;
        var modeSel = document.getElementById('qso-cluster-mode');
        if (modeSel) modeSel.value = savedMode;
      }

      var savedNewDxcc = localStorage.getItem('cloudlog_newDxccFilter');
      if (savedNewDxcc !== null) {
        this.selectedNewDxcc = savedNewDxcc;
        var newDxccSel = document.getElementById('qso-cluster-new-dxcc');
        if (newDxccSel) newDxccSel.value = savedNewDxcc;
      }

      // Apply CW lock after all filters are restored
      this.applyCwRbnLock();

      var self = this;
      var rbnEl = document.getElementById('qso-cluster-hide-rbn');
      if (rbnEl) {
        rbnEl.addEventListener('change', function(e) {
          self.hideRbn = e.target.checked;
          localStorage.setItem('cloudlog_hideRbnSpots', self.hideRbn.toString());
          self.renderTable();
        });
      }

      var bandEl = document.getElementById('qso-cluster-band');
      if (bandEl) {
        bandEl.addEventListener('change', function(e) {
          // Manual band change while Track Band is on — disengage tracking
          if (self.trackBand) {
            self.trackBand = false;
            var trackChk = document.getElementById('qso-cluster-track-band');
            if (trackChk) trackChk.checked = false;
            localStorage.setItem('cloudlog_clusterTrackBand', 'false');
          }
          self.selectedBand = e.target.value;
          localStorage.setItem('cloudlog_bandFilter', self.selectedBand);
          self.renderTable();
        });
      }

      var modeEl = document.getElementById('qso-cluster-mode');
      if (modeEl) {
        modeEl.addEventListener('change', function(e) {
          self.selectedMode = e.target.value;
          localStorage.setItem('cloudlog_modeFilter', self.selectedMode);
          self.applyCwRbnLock();
          self.renderTable();
        });
      }

      var newDxccEl = document.getElementById('qso-cluster-new-dxcc');
      if (newDxccEl) {
        newDxccEl.addEventListener('change', function(e) {
          self.selectedNewDxcc = e.target.value;
          localStorage.setItem('cloudlog_newDxccFilter', self.selectedNewDxcc);
          self.renderTable();
        });
      }

      var trackEl = document.getElementById('qso-cluster-track-band');
      if (trackEl) {
        trackEl.addEventListener('change', function(e) {
          self.trackBand = e.target.checked;
          localStorage.setItem('cloudlog_clusterTrackBand', self.trackBand.toString());
          if (!self.trackBand) { self.setBandFilter('all'); }
          else { self.syncBandFromRadio(); }
        });
      }

      // Sync cluster band filter to follow the QSO form's band
      this.syncBandFromRadio();

      // Follow band changes on the QSO form
      $('#band').on('change.qsoCluster', function() {
        self.syncBandFromRadio();
      });

      // When radio selection changes, re-sync
      $('#radio').on('change.qsoCluster', function() {
        self.syncBandFromRadio();
      });

      // CAT sets #band via .val() which doesn't fire the change event,
      // so poll every second to catch programmatic band updates
      setInterval(function() {
        self.syncBandFromRadio();
      }, 1000);

      // Init DataTable — dom:'t' shows only the table (no search/length/info/pagination chrome)
      this.dataTable = $('#qso-cluster-table').DataTable({
        dom: 'tp',
        paging: true,
        pageLength: 5,
        searching: false,
        info: false,
        ordering: false,
        language: { url: getDataTablesLanguageUrl(), paginate: { previous: '&lsaquo;', next: '&rsaquo;' } },
        columns: [
          { title: 'Time',    width: '52px'  },
          { title: 'DX Call'                 },
          { title: 'Freq',    width: '78px'  },
          { title: 'Comment'                 },
          { title: '', visible: false },   // col[4]: raw callsign for click handler
          { title: '', visible: false },   // col[5]: raw freq MHz for click handler
          { title: '', visible: false },   // col[6]: raw spotter for RBN detection
          { title: '', visible: false }    // col[7]: raw comment for mode detection
        ],
        createdRow: function(row) {
          $(row).css('cursor', 'pointer');
        }
      });

      // Row click: fill callsign + frequency and trigger lookup
      $('#qso-cluster-table tbody').on('click', 'tr', function() {
        var data = self.dataTable.row(this).data();
        if (!data) return;
        var dx      = data[4];
        var freq    = data[5];
        var spotter = data[6];
        var comment = data[7];
        // Reset the form first (same as the reset button)
        if (typeof reset_fields === 'function') { reset_fields(); }
        // Set frequency and band
        $('#frequency').val(freq);
        if (typeof frequencyToBand === 'function') {
          $('#band').val(frequencyToBand(freq));
        }
        // If the spot is from the RBN and comments indicate CW, set mode accordingly
        // Don't trigger('change') — that would call band_to_freq and overwrite the spot frequency
        if (self.isRbn(spotter) && /\bCW\b/i.test(comment)) {
          $('#mode').val('CW');
        }
        // QSY the radio if one is selected (same as main DX Cluster page)
        var radioId = $('#radio').val();
        if (radioId && radioId !== '0') {
          var freqMHz = (freq / 1000000).toFixed(3);
          var qsyMode = (self.isRbn(spotter) && /\bCW\b/i.test(comment)) ? 'CW' : null;
          var body = { radio_id: radioId, frequency: freqMHz };
          if (qsyMode) body.mode = qsyMode;
          fetch('<?php echo site_url('dxcluster/qsy'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
          }).catch(function() {});
        }
        // Set callsign and trigger lookup
        $('#callsign').val(dx);
        $('#callsign').focusout();
        $('#callsign').blur();
      });

      this.connect();
    },

    connect: function() {
      this.setStatus('Connecting...', 'warning');
      var self = this;
      this.ws = new WebSocket('wss://dxc.cloudlog.org');

      this.ws.onopen = function() {
        self.setStatus('Connected', 'success');
      };

      this.ws.onmessage = function(event) {
        try {
          var data = JSON.parse(event.data);
          if (data.type === 'spot') {
            self.addSpot(data);
          }
        } catch(e) {}
      };

      this.ws.onclose = function() {
        self.setStatus('Reconnecting...', 'secondary');
        setTimeout(function() { self.connect(); }, 5000);
      };

      this.ws.onerror = function() {
        self.setStatus('Error', 'danger');
      };
    },

    setStatus: function(text, type) {
      var el   = document.getElementById('qso-cluster-status');
      var icon = document.getElementById('qso-cluster-status-icon');
      if (!el || !icon) return;
      var stateMap = {
        'Connected':      { icon: 'fas fa-circle',               tip: 'Connected to DX Cluster' },
        'Connecting...':  { icon: 'fas fa-circle-notch fa-spin', tip: 'Connecting to DX Cluster...' },
        'Reconnecting...':{ icon: 'fas fa-circle-notch fa-spin', tip: 'Reconnecting to DX Cluster...' },
        'Error':          { icon: 'fas fa-circle-exclamation',   tip: 'DX Cluster connection error' },
        'Disconnected':   { icon: 'fas fa-circle',               tip: 'Disconnected from DX Cluster' },
      };
      var state = stateMap[text] || { icon: 'fas fa-circle', tip: text };
      el.className = 'badge bg-' + type + (type === 'warning' ? ' text-dark' : '');
      icon.className = state.icon;
      el.setAttribute('title', state.tip);
      el.setAttribute('data-bs-original-title', state.tip);
    },

    addSpot: function(spot) {
      // Deduplicate by dx+frequency key; newer spot overwrites
      var key = spot.dx + '|' + spot.frequency;
      spot.receivedAt = Date.now();
      this.spots.set(key, spot);

      // Prune to newest 100
      if (this.spots.size > 100) {
        var entries = Array.from(this.spots.entries());
        entries.sort(function(a, b) { return a[1].receivedAt - b[1].receivedAt; });
        this.spots.delete(entries[0][0]);
      }

      this.renderTable();

      clearTimeout(this.checkWorkedTimeout);
      var self = this;
      this.checkWorkedTimeout = setTimeout(function() { self.checkWorkedStatus(); }, 500);
    },

    isRbn: function(spotter) {
      return DXCluster.isRbnSpot(spotter);
    },

    detectModeFromFrequency: function(freqKhz) {
      return DXCluster.detectModeFromFrequency(freqKhz);
    },

    detectMode: function(spot) {
      return DXCluster.detectMode(spot);
    },

    getBand: function(freqKhz) {
      return DXCluster.getBandFromFrequency(freqKhz);
    },

    formatTime: function(t) {
      if (!t || t === '0' || t === 'null' || t === 'undefined') return '';
      var s = t.toString().trim();
      return s.endsWith('Z') ? s : s + 'Z';
    },

    calcAge: function(receivedAt) {
      var mins = Math.floor((Date.now() - receivedAt) / 60000);
      if (mins < 1) return 'now';
      if (mins < 60) return mins + 'm';
      return Math.floor(mins / 60) + 'h';
    },

    // When CW mode is active, Hide RBN must be off (most CW spots ARE RBN).
    // Disables the checkbox so the user cannot re-hide RBN while on CW.
    applyCwRbnLock: function() {
      var rbn = document.getElementById('qso-cluster-hide-rbn');
      if (!rbn) return;
      if (this.selectedMode === 'cw') {
        // Force off and disable
        if (this.hideRbn) {
          this.hideRbn = false;
          rbn.checked = false;
          localStorage.setItem('cloudlog_hideRbnSpots', 'false');
        }
        rbn.disabled = true;
        rbn.title = 'Cannot hide RBN while CW mode is selected';
      } else {
        rbn.disabled = false;
        rbn.title = '';
      }
    },

    syncBandFromRadio: function() {
      if (!this.trackBand) return;
      var band = $('#band').val();
      if (band) { this.setBandFilter(band); }
    },

    setBandFilter: function(band) {
      if (band === this.selectedBand) return;
      this.selectedBand = band;
      var sel = document.getElementById('qso-cluster-band');
      if (sel) sel.value = band;
      localStorage.setItem('cloudlog_bandFilter', band);
      this.renderTable();
    },

    escHtml: function(s) {
      if (!s) return '';
      return s.toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
    },

    renderTable: function() {
      if (!this.dataTable) return;
      // Skip the DOM redraw if the tab pane is not currently visible
      var pane = document.getElementById('dx-cluster-pane');
      if (pane && !pane.classList.contains('active')) return;

      var self = this;
      var sorted = Array.from(this.spots.values())
        .filter(function(s) {
          if (self.hideRbn && self.isRbn(s.spotter)) return false;
          if (self.selectedBand !== 'all' && self.getBand(s.frequency) !== self.selectedBand) return false;
          if (self.selectedMode !== 'all' && self.detectMode(s) !== self.selectedMode) return false;
          if (self.selectedNewDxcc !== 'all') {
            var st = self.workedStatus[s.dx];
            if (!st) return false; // Hold back until status is known
            if (!st.dxcc) return false;
            if (self.selectedNewDxcc === 'new_any')  return !st.dxcc_worked_overall || !st.dxcc_worked_on_band;
            if (self.selectedNewDxcc === 'new_dxcc') return !st.dxcc_worked_overall;
            if (self.selectedNewDxcc === 'new_band')  return st.dxcc_worked_overall && !st.dxcc_worked_on_band;
          }
          return true;
        })
        .sort(function(a, b) { return b.receivedAt - a.receivedAt; })
        .slice(0, 50);

      this.dataTable.clear();

      sorted.forEach(function(spot) {
        var status      = self.workedStatus[spot.dx];
        var freqHz      = Math.round(parseFloat(spot.frequency) * 1000); // kHz → Hz
        var freqDisplay = (freqHz / 1000000).toFixed(3);                 // Hz → MHz for display
        var comment     = self.escHtml((spot.comment || '').substring(0, 35));
        var time        = self.formatTime(spot.time);

        var workedIcon = '';
        var newBadge   = '';
        if (status) {
          if (status.worked_on_band) {
            workedIcon = '<i class="fas fa-check text-success ms-1" title="Worked on band"></i>';
          } else if (status.worked_overall) {
            workedIcon = '<i class="fas fa-check text-info ms-1" title="Worked another band"></i>';
          } else {
            workedIcon = '<i class="fas fa-times text-danger ms-1" title="Not worked"></i>';
          }
          if (status.dxcc && !status.dxcc_worked_overall) {
            // Never worked this DXCC entity on any band
            newBadge = ' <span class="badge bg-danger" style="font-size:0.6rem;">New DXCC</span>';
          } else if (status.dxcc && !status.dxcc_worked_on_band) {
            // Worked on another band but not this one
            newBadge = ' <span class="badge bg-warning text-dark" style="font-size:0.6rem;">New Band</span>';
          }
        }

        self.dataTable.row.add([
          '<span class="text-muted">' + time + '</span>',
          '<strong>' + self.escHtml(spot.dx) + '</strong>' + workedIcon + newBadge,
          '<span style="font-family:monospace;font-weight:600;color:#0d6efd;">' + freqDisplay + '</span>',
          '<span class="text-muted">' + comment + '</span>',
          spot.dx,              // hidden col[4]: raw callsign
          freqHz,               // hidden col[5]: freq in Hz (Cloudlog standard)
          spot.spotter || '',   // hidden col[6]: raw spotter
          spot.comment || ''    // hidden col[7]: raw comment
        ]);
      });

      this.dataTable.draw(false);
    },

    checkWorkedStatus: async function() {
      var toCheck = [];
      var seen = new Set();

      for (var spot of this.spots.values()) {
        if (this.hideRbn && this.isRbn(spot.spotter)) continue;
        if (this.selectedBand !== 'all' && this.getBand(spot.frequency) !== this.selectedBand) continue;
        if (this.selectedMode !== 'all' && this.detectMode(spot) !== this.selectedMode) continue;
        if (this.workedStatus[spot.dx]) continue;
        if (seen.has(spot.dx)) continue;
        seen.add(spot.dx);
        toCheck.push({ callsign: spot.dx, band: this.getBand(spot.frequency) });
        if (toCheck.length >= 30) break;
      }

      if (!toCheck.length) return;

      var self = this;
      try {
        var resp = await fetch('<?php echo site_url('dxcluster/check_worked'); ?>', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ callsigns: toCheck })
        });
        var data = await resp.json();
        if (data.success) {
          Object.assign(self.workedStatus, data.results);
          self.renderTable();
        }
      } catch(e) {}
    }
  };

  // Lazy init: only connect when the DX Cluster tab is first clicked
  document.addEventListener('DOMContentLoaded', function() {
    var tab = document.getElementById('dx-cluster-tab');
    if (!tab) return;

    tab.addEventListener('shown.bs.tab', function() {
      if (!qsoCluster.initialized) {
        qsoCluster.init();
      } else {
        // Re-render with any spots that arrived while the tab was hidden,
        // then re-sync band filter and fix column widths
        qsoCluster.renderTable();
        qsoCluster.syncBandFromRadio();
        if (qsoCluster.dataTable) { qsoCluster.dataTable.columns.adjust(); }
      }
    });

    // Update spot ages every minute while active
    setInterval(function() {
      if (qsoCluster.initialized && qsoCluster.spots.size > 0) {
        qsoCluster.renderTable();
      }
    }, 60000);
  });

}());
</script>
<?php endif; ?>
