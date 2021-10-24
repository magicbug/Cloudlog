<div class="container qso_panel contesting">
    <button type="button" class="btn btn-sm btn-warning float-right" onclick="reset_contest_session()"><i class="fas fa-sync-alt"></i> <?php echo $this->lang->line('contesting_button_reset_contest_session'); ?></button>
    <h2><?php echo $this->lang->line('contesting_page_title'); ?></h2>
    <div class="row">

        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="qso_input" name="qsos">
                        <div class="form-group row">
							<label class="col-auto control-label" for="radio"><?php echo $this->lang->line('contesting_exchange_type'); ?></label>

							<div class="col-auto">
								<select class="form-control-sm" id="exchangetype" name="exchangetype">
									<option value='None'>None</option>
									<option value='Exchange'>Exchange</option>
									<option value='Gridsquare'>Gridsquare</option>
									<option value='Serial'>Serial</option>
									<option value='Serialexchange'>Serial + Exchange</option>
									<option value='Serialgridsquare'>Serial + Gridsquare</option>
								</select>
							</div>

                            <label class="col-auto control-label" for="contestname"><?php echo $this->lang->line('contesting_contest_name'); ?></label>

                            <div class="col-auto">
                                <select class="form-control-sm" id="contestname" name="contestname">
									<?php foreach($contestnames as $contest) {
										echo "<option value='" . $contest['adifname'] . "'>" . $contest['name'] . "</option>";
									} ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="start_date"><?php echo $this->lang->line('general_word_date'); ?></label>
                                <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_date'); } else { echo date('d-m-Y');}?>">
                            </div>

                            <div class="form-group col-md-1">
                                <label for="start_time"><?php echo $this->lang->line('general_word_time'); ?></label>
                                <input type="text" class="form-control form-control-sm input_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_time'); } else {echo date('H:i'); } ?>" size="7">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="mode"><?php echo $this->lang->line('gen_hamradio_mode'); ?></label>
                                <select id="mode" class="form-control mode form-control-sm" name="mode">
                                    <?php foreach($modes->result() as $mode) {
                                            if ($mode->submode == null) {
                                                printf("<option value=\"%s\" %s>%s</option>", $mode->mode, $this->session->userdata('mode')==$mode->mode?"selected=\"selected\"":"",$mode->mode);
                                            } else {
                                                printf("<option value=\"%s\" %s>&rArr; %s</option>", $mode->submode, $this->session->userdata('mode')==$mode->submode?"selected=\"selected\"":"",$mode->submode);
                                            }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group col-md-2">
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

                            <div class="form-group col-md-2">
                                <label for="frequency"><?php echo $this->lang->line('gen_hamradio_frequency'); ?></label>
                                <input type="text" class="form-control form-control-sm" id="frequency" name="freq_display" value="<?php echo $this->session->userdata('freq'); ?>" />
                            </div>

                            <div class="form-group col-md-2">
                                <label for="inputRadio"><?php echo $this->lang->line('gen_hamradio_radio'); ?></label>
                                <select class="form-control form-control-sm radios" id="radio" name="radio">
                                    <option value="0" selected="selected"><?php echo $this->lang->line('general_word_none'); ?></option>
                                        <?php foreach ($radios->result() as $row) { ?>
                                        <option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="callsign"><?php echo $this->lang->line('gen_hamradio_callsign'); ?></label>
                                <input type="text" class="form-control form-control-sm" id="callsign" name="callsign" required>
                                <small id="callsign_info" class="badge badge-danger"></small>
                            </div>

                            <div class="form-group col-md-1">
                                <label for="rst_sent"><?php echo $this->lang->line('gen_hamradio_rsts'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                            </div>

                            <div style="display:none" class="form-group col-md-1 serials">
								<label for="exch_serial_s">Serial (S)</label>
								<input type="number" class="form-control form-control-sm" name="exch_serial_s" id="exch_serial_s" value="">
							</div>
                            
                            <div style="display:none" class="form-group col-md-1 exchanges">
                                <label for="exch_sent"><?php echo $this->lang->line('gen_hamradio_exchange_sent_short'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="exch_sent" id="exch_sent" value="">
                            </div>

							<div style="display:none" class="form-group col-md-2 gridsquares">
								<label for="exch_gridsquare_s">Gridsquare (S)</label>
								<input disabled type="text" class="form-control form-control-sm" name="exch_gridsquare_s" id="exch_gridsquare_s" value="<?php echo $my_gridsquare;?>">
							</div>

                            <div class="form-group col-md-1">
                                <label for="rst_recv"><?php echo $this->lang->line('gen_hamradio_rstr'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="59">
                            </div>

                            <div style="display:none" class="form-group col-md-1 serialr">
								<label for="exch_serial_r">Serial (R)</label>
								<input type="number" class="form-control form-control-sm" name="exch_serial_r" id="exch_serial_r" value="">
							</div>
							
							<div style="display:none" class="form-group col-md-1 exchanger">
								<label for="exch_recv"><?php echo $this->lang->line('gen_hamradio_exchange_recv_short'); ?></label>
								<input type="text" class="form-control form-control-sm" name="exch_recv" id="exch_recv" value="">
							</div>

							<div style="display:none" class="form-group col-md-2 gridsquarer">
								<label for="exch_gridsquare_r">Gridsquare (R)</label>
								<input type="text" class="form-control form-control-sm" name="locator" id="exch_gridsquare_r" value="">
							</div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label for="name"><?php echo $this->lang->line('general_word_name'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                            </div>

                            <div class="form-group col-md-5">
                                <label for="comment"><?php echo $this->lang->line('general_word_comment'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                            </div>
                        </div>

                        <button type="button" class="btn btn-sm btn-light" onclick="reset_log_fields()"><i class="fas fa-sync-alt"></i> <?php echo $this->lang->line('contesting_btn_reset_qso'); ?></button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="logQso();"><i class="fas fa-save"></i> <?php echo $this->lang->line('contesting_btn_save_qso'); ?></button>
                        <div class="form-group row">
                          <div class="col-md-12">
                              <div class="form-check-inline">
                                  <input class="form-check-input" type="checkbox" name="copyexchangetodok" value="1" id="copyexchangetodok">
                                  <label class="form-check-label" for="copyexchangetodok">Copy received exchange to DOK field in the database!</label>
                              </div>
                          </div>
                      </div>
                    </form>
                </div>
            </div>

            <br/>

            <!-- Callsign SCP Box -->
            <div class="card callsign-suggest">
                <div class="card-header"><h5 class="card-title"><?php echo $this->lang->line('contesting_title_callsign_suggestions'); ?></h5></div>

                <div class="card-body callsign-suggestions"></div>
            </div>

            <!-- Past QSO Box -->
            <div class="card log">
                <div class="card-header"><h5 class="card-title"><?php echo $this->lang->line('contesting_title_contest_logbook'); ?></h5></div>

                <table style="width:100%" class="table-sm table qsotable table-bordered table-hover table-striped table-condensed text-center">
                    <thead>
                        <tr class="log_title titles">
                            <th><?php echo $this->lang->line('general_word_date'); ?>/<?php echo $this->lang->line('general_word_time'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_call'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_band'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_mode'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_rsts'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_rstr'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_exchange_sent_short'); ?></th>
                            <th><?php echo $this->lang->line('gen_hamradio_exchange_recv_short'); ?></th>
							<th>Serial (S)</th>
							<th>Serial (R)</th>
							<th>Gridsquare</th>
							<th>Vucc Gridsquare</th>
                        </tr>
                    </thead>

                    <tbody class="contest_qso_table_contents">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
?>
