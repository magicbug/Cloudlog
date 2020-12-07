<div class="container qso_panel">
    <h2><?php echo $page_title; ?></h2>
    <div class="row">

        <div class="col-sm-12 col-md-12">
            <div class="card">
                <div class="card-header"><h5 class="card-title">Logging form</h5></div>
                <div class="card-body">
                    <form id="qso_input" name="qsos">

                            <div class="form-group row">

                                    <label class="col-md-2 control-label" for="radio">Exchange type</label>
                                    <div class="col-md-9">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exchangeradio" id="serial" value="serial" checked>
                                            <label class="form-check-label" for="serial">
                                                Serial
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="exchangeradio" id="other" value="other">
                                            <label class="form-check-label" for="other">
                                                Other
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-2">
                                        <label for="start_date">Date</label>
                                        <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_date'); } else { echo date('d-m-Y');}?>">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="start_time">Time</label>
                                        <input type="text" class="form-control form-control-sm input_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_time'); } else {echo date('H:i'); } ?>" size="7">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="mode">Mode</label>
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

                                    <div class="form-group col-md-2">
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
                                                <option value="6cm" <?php if($this->session->userdata('band') == "6cm") { echo "selected=\"selected\""; } ?>>6cm</option>
                                                <option value="3cm" <?php if($this->session->userdata('band') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="frequency">Frequency</label>
                                        <input type="text" class="form-control form-control-sm" id="frequency" name="freq_display" value="<?php echo $this->session->userdata('freq'); ?>" />
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="inputRadio">Radio</label>
                                        <select class="form-control form-control-sm radios" id="radio" name="radio">
                                            <option value="0" selected="selected">None</option>
                                            <?php foreach ($radios->result() as $row) { ?>
                                                <option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="form-group col-md-3">
                                        <label for="callsign">Callsign</label>
                                        <input type="text" class="form-control form-control-sm" id="callsign" name="callsign" required>
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="rst_sent">RST (S)</label>
                                        <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="exch_sent">Exch (S)</label>
                                        <input type="text" class="form-control form-control-sm" name="exch_sent" id="exch_sent" value="">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="rst_recv">RST (R)</label>
                                        <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="59">
                                    </div>

                                    <div class="form-group col-md-1">
                                        <label for="exch_recv">Exch (R)</label>
                                        <input type="text" class="form-control form-control-sm" name="exch_recv" id="exch_recv" value="">
                                    </div>

                                </div>
                                <div class="form-row">
                                        <div class="form-group col-md-5">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                                        </div>

                                        <div class="form-group col-md-5">
                                            <label for="comment">Comment</label>
                                            <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                                        </div>
                                </div>
                            <button type="reset" class="btn btn-sm btn-warning" onclick="reset_log_fields()">Reset</button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="logQso();"><i class="fas fa-save"></i> Log QSO</button>
                            </div>

                        </div>

                        </div>
                    </form>
            </div>

            <br/>
            <div class="card callsign-suggest">
                <div class="card-header"><h5 class="card-title">Callsign Suggestions</h5></div>

                <div class="card-body callsign-suggestions"></div>
            </div>

            <div class="card log">
                <div class="card-header"><h5 class="card-title">Logbook (for this logging session)</h5></div>

                        <table class="table-sm table qsotable table-bordered table-hover table-striped table-condensed text-center">
                            <thead>
                            <tr class="log_title titles">
                                <th>Date/Time</th>
                                <th>Call</th>
                                <th>Band</th>
                                <th>Mode</th>
                                <th>RST (S)</th>
                                <th>RST (R)</th>
                                <th>Exch S</th>
                                <th>Exch R</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
            </div>
        </div>
    </div>


</div>

</div>
