<div class="container qso_panel">
    <h2><?php echo $page_title; ?></h2>
    <div class="row">

        <div class="col-sm-12 col-md-12">
            <div class="card">

                <form id="qso_input" name="qsos">

                                <!-- HTML for Date/Time -->
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label for="start_date">Date</label>
                                        <input type="text" class="form-control form-control-sm input_date" name="start_date" id="start_date" value="<?php if (($this->session->userdata('start_date') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_date'); } else { echo date('d-m-Y');}?>">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="start_time">Time</label>
                                        <input type="text" class="form-control form-control-sm input_time" name="start_time" id="start_time" value="<?php if (($this->session->userdata('start_time') != NULL && ((time() - $this->session->userdata('time_stamp')) < 24 * 60 * 60))) { echo $this->session->userdata('start_time'); } else {echo date('H:i'); } ?>" size="7">
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label for="callsign">Callsign</label>
                                        <input type="text" class="form-control form-control-sm" id="callsign" name="callsign" required>
                                        <small id="callsign_info" class="badge badge-secondary"></small> <small id="lotw_info" class="badge badge-light"></small>
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
                                        <label for="rst_sent">RST (S)</label>
                                        <input type="text" class="form-control form-control-sm" name="rst_sent" id="rst_sent" value="59">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="rst_recv">RST (R)</label>
                                        <input type="text" class="form-control form-control-sm" name="rst_recv" id="rst_recv" value="59">
                                    </div>
                                </div>

                                <!-- Signal Report Information -->
                                <div class="form-row">

                                </div>
                                    <div class="form-group row">
                                        <label for="name" class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" name="name" id="name" value="">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="comment" class="col-sm-3 col-form-label">Comment</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control form-control-sm" name="comment" id="comment" value="">
                                        </div>
                                    </div>



                            </div>

                        <div class="info">
                            <input size="20" id="country" type="hidden" name="country" value="" />
                        </div>
<br/>
                        <button type="reset" class="btn btn-sm btn-light" onclick="reset_fields()">Reset</button>
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Log QSO</button>
                    </div>
                </form>
            </div>

<br/>
            <div class="card callsign-suggest">
                <div class="card-header"><h4 class="card-title">Callsign Suggestions</h4></div>

                <div class="card-body callsign-suggestions"></div>
            </div>

            <div class="card previous-qsos">
                <div class="card-header"><h4 class="card-title">Logbook (for this session)</h4></div>

                <div id="partial_view"></div>

                <div id="qso-last-table">

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <tr class="log_title titles">
                                <td>Date/Time</td>
                                <td>Call</td>
                                <td>Mode</td>
                                <td>RST s</td>
                                <td>RST r</td>
                                <td>Exch S</td>
                                <td>Exch R</td>
                                <td>Band</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>
