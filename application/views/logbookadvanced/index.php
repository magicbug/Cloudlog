<script type="text/javascript">
/*
 *
 * Define custom date format
 *
 */
var custom_date_format = "<?php echo $custom_date_format ?>";
<?php
if (!isset($options)) {
   $options = "{\"datetime\":{\"show\":\"true\"},\"de\":{\"show\":\"true\"},\"dx\":{\"show\":\"true\"},\"mode\":{\"show\":\"true\"},\"rstr\":{\"show\":\"true\"},\"rsts\":{\"show\":\"true\"},\"band\":{\"show\":\"true\"},\"myrefs\":{\"show\":\"true\"},\"refs\":{\"show\":\"true\"},\"name\":{\"show\":\"true\"},\"qslvia\":{\"show\":\"true\"},\"qsl\":{\"show\":\"true\"},\"lotw\":{\"show\":\"true\"},\"eqsl\":{\"show\":\"true\"},\"qslmsg\":{\"show\":\"true\"},\"dxcc\":{\"show\":\"true\"},\"state\":{\"show\":\"true\"},\"cqzone\":{\"show\":\"true\"},\"iota\":{\"show\":\"true\"}}";
}
echo "var user_options = $options;";
?>
</script>
<style>
/*Legend specific*/
.legend {
  padding: 6px 8px;
  font: 14px Arial, Helvetica, sans-serif;
  background: white;
  line-height: 24px;
  color: #555;
  border-radius: 10px;
}
.legend h4 {
  text-align: center;
  font-size: 16px;
  margin: 2px 12px 8px;
  color: #777;
}
.legend span {
  position: relative;
  bottom: 3px;
}
.legend i {
  width: 18px;
  height: 18px;
  float: left;
  margin: 0 8px 0 0;
}
</style>
<?php
$options = json_decode($options);
?>
<div class="container-fluid qso_manager pt-3 pl-4 pr-4">
    <?php if ($this->session->flashdata('message')) { ?>
    <!-- Display Message -->
    <div class="alert-message error">
        <p><?php echo $this->session->flashdata('message'); ?></p>
    </div>
    <?php } ?>
    <div class="row">

        <form id="searchForm" name="searchForm" action="<?php echo base_url()."index.php/logbookadvanced/search";?>"
            method="post">
            <input type="hidden" id="dupes" name="dupes" value="">
            <div class="filterbody collapse">
                <div class="form-row">
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="dateFrom"><?php echo lang('filter_general_from'); ?></label>
                        <div class="input-group input-group-sm date" id="dateFrom" data-target-input="nearest">
                            <input name="dateFrom" type="text" placeholder="<?php echo $datePlaceholder;?>"
                                class="form-control" data-target="#dateFrom" />
                            <div class="input-group-append" data-target="#dateFrom" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label for="dateTo"><?php echo lang('filter_general_to'); ?></label>
                        <div class="input-group input-group-sm date" id="dateTo" data-target-input="nearest">
                            <input name="dateTo" type="text" placeholder="<?php echo $datePlaceholder;?>"
                                class="form-control" data-target="#dateTo" />
                            <div class="input-group-append" data-target="#dateTo" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="de"><?php echo lang('gen_hamradio_de'); ?></label>
                        <select id="de" name="de" class="form-control form-control-sm">
                            <option value=""><?php echo lang('general_word_all'); ?></option>
                            <?php
					foreach($deOptions as $deOption){
						?><option value="<?php echo htmlentities($deOption);?>"><?php echo htmlspecialchars($deOption);?></option><?php
					}
					?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="dx"><?php echo lang('gen_hamradio_dx'); ?></label>
                        <input type="text" name="dx" id="dx" class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="dxcc"><?php echo lang('gen_hamradio_dxcc'); ?></label>
                        <select class="form-control form-control-sm" id="dxcc" name="dxcc">
                            <option value="">-</option>
                            <option value="0"><?php echo lang('filter_general_none'); ?></option>
                            <?php
					foreach($dxccarray as $dxcc){
						echo '<option value=' . $dxcc->adif;
						echo '>' . $dxcc->prefix . ' - ' . ucwords(strtolower($dxcc->name), "- (/");
						if ($dxcc->Enddate != null) {
							echo ' - (Deleted DXCC)';
						}
						echo '</option>';
					}
					?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="iota"><?php echo lang('gen_hamradio_iota'); ?></label>
                        <select class="form-control form-control-sm" id="iota" name="iota">
                            <option value="">-</option>
                            <?php
					foreach($iotaarray as $iota){
						echo '<option value=' . $iota->tag;
						echo '>' . $iota->tag . ' - ' . $iota->name . '</option>';
					}
					?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="state"><?php echo lang('gen_hamradio_state'); ?></label>
                        <input type="text" name="state" id="state" class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="gridsquare"><?php echo lang('gen_hamradio_gridsquare'); ?></label>
                        <input type="text" name="gridsquare" id="gridsquare" class="form-control form-control-sm"
                            value="">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="mode"><?php echo lang('gen_hamradio_mode'); ?></label>
                        <select id="mode" name="mode" class="form-control form-control-sm">
                            <option value=""><?php echo lang('general_word_all'); ?></option>
                            <?php
					foreach($modes as $modeId => $mode){
						?><option value="<?php echo htmlspecialchars($mode);?>"><?php echo htmlspecialchars($mode);?></option><?php
					}
					?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="band"><?php echo lang('gen_hamradio_band'); ?></label>
                        <select id="band" name="band" class="form-control form-control-sm">
                            <option value=""><?php echo lang('general_word_all'); ?></option>
                            <?php
					foreach($bands as $band){
						?><option value="<?php echo htmlentities($band);?>"><?php echo htmlspecialchars($band);?></option><?php
					}
					?>
                        </select>
                    </div>
                    <div hidden class="sats_dropdown form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="sats"><?php echo lang('general_word_satellite'); ?></label>
                        <select class="form-control form-control-sm" id="sats">
                            <option value="All"><?php echo lang('general_word_all'); ?></option>
                            <?php foreach($sats as $sat) {
					echo '<option value="' . htmlentities($sat) . '"' . '>' . htmlentities($sat) . '</option>'."\n";
				} ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="selectPropagation"><?php echo lang('filter_general_propagation'); ?></label>
                        <select id="selectPropagation" name="selectPropagation" class="form-control form-control-sm">
                            <option value=""><?php echo lang('general_word_all'); ?></option>
                            <option value="AS">Aircraft Scatter</option>
                            <option value="AUR">Aurora</option>
                            <option value="AUE">Aurora-E</option>
                            <option value="BS">Back scatter</option>
                            <option value="ECH">EchoLink</option>
                            <option value="EME">Earth-Moon-Earth</option>
                            <option value="ES">Sporadic E</option>
                            <option value="FAI">Field Aligned Irregularities</option>
                            <option value="F2">F2 Reflection</option>
                            <option value="INTERNET">Internet-assisted</option>
                            <option value="ION">Ionoscatter</option>
                            <option value="IRL">IRLP</option>
                            <option value="MS">Meteor scatter</option>
                            <option value="RPT">Terrestrial or atmospheric repeater or transponder</option>
                            <option value="RS">Rain scatter</option>
                            <option value="SAT">Satellite</option>
                            <option value="TEP">Trans-equatorial</option>
                            <option value="TR">Tropospheric ducting</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="cqzone">CQ Zone</label>
                        <select id="cqzone" name="cqzone" class="form-control form-control-sm">
                            <option value=""><?php echo lang('general_word_all'); ?></option>
                            <?php
                      for ($i = 1; $i<=40; $i++) {
                          echo '<option value="'. $i . '">'. $i .'</option>';
                      }
                      ?>
                            ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="sota"><?php echo lang('gen_hamradio_sota'); ?></label>
                        <input type="text" name="sota" id="sota" class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="wwff"><?php echo lang('gen_hamradio_wwff'); ?></label>
                        <input type="text" name="wwff" id="wwff" class="form-control form-control-sm" value="">
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                        <label class="form-label" for="pota"><?php echo lang('gen_hamradio_pota'); ?></label>
                        <input type="text" name="pota" id="pota" class="form-control form-control-sm" value="">
                    </div>
                </div>
            </div>
    </div>
    <div class="qslfilterbody collapse">
        <div class="form-row">
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="qslSent"><?php echo lang('filter_qsl_sent'); ?></label>
                <select id="qslSent" name="qslSent" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                    <option value="R"><?php echo lang('general_word_requested'); ?></option>
                    <option value="Q"><?php echo lang('general_word_queued'); ?></option>
                    <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="qslReceived"><?php echo lang('filter_qsl_recv'); ?></label>
                <select id="qslReceived" name="qslReceived" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                    <option value="R"><?php echo lang('general_word_requested'); ?></option>
                    <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                    <option value="V"><?php echo lang('filter_qsl_verified'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="qslSentMethod"><?php echo lang('filter_qsl_sent_method'); ?></label>
                <select id="qslSentMethod" name="qslSentMethod" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="B"><?php echo lang('general_word_qslcard_bureau'); ?></option>
                    <option value="D"><?php echo lang('general_word_qslcard_direct'); ?></option>
                    <option value="E"><?php echo lang('general_word_qslcard_electronic'); ?></option>
                    <option value="M"><?php echo lang('general_word_qslcard_manager'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="qslReceivedMethod"><?php echo lang('filter_qsl_recv_method'); ?></label>
                <select id="qslReceivedMethod" name="qslReceivedMethod" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="B"><?php echo lang('general_word_qslcard_bureau'); ?></option>
                    <option value="D"><?php echo lang('general_word_qslcard_direct'); ?></option>
                    <option value="E"><?php echo lang('general_word_qslcard_electronic'); ?></option>
                    <option value="M"><?php echo lang('general_word_qslcard_manager'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="lotwSent"><?php echo lang('filter_lotw_sent'); ?></label>
                <select id="lotwSent" name="lotwSent" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                    <option value="R"><?php echo lang('general_word_requested'); ?></option>
                    <option value="Q"><?php echo lang('general_word_queued'); ?></option>
                    <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="lotwReceived"><?php echo lang('filter_lotw_recv'); ?></label>
                <select id="lotwReceived" name="lotwReceived" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                    <option value="R"><?php echo lang('general_word_requested'); ?></option>
                    <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                    <option value="V"><?php echo lang('filter_qsl_verified'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="eqslSent"><?php echo lang('filter_eqsl_sent'); ?></label>
                <select id="eqslSent" name="eqslSent" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                    <option value="R"><?php echo lang('general_word_requested'); ?></option>
                    <option value="Q"><?php echo lang('general_word_queued'); ?></option>
                    <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="eqslReceived"><?php echo lang('filter_eqsl_recv'); ?></label>
                <select id="eqslReceived" name="eqslReceived" class="form-control form-control-sm">
                    <option value=""><?php echo lang('general_word_all'); ?></option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                    <option value="R"><?php echo lang('general_word_requested'); ?></option>
                    <option value="I"><?php echo lang('general_word_invalid_ignore'); ?></option>
                    <option value="V"><?php echo lang('filter_qsl_verified'); ?></option>
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="qslvia"><?php echo lang('filter_qsl_via'); ?></label>
                <input type="search" name="qslviainput" class="form-control form-control-sm">
            </div>
            <div class="form-group col-lg-2 col-md-2 col-sm-3 col-xl">
                <label for="qslimages"><?php echo lang('filter_qsl_images'); ?></label>
                <select class="form-control form-control-sm" id="qslimages" name="qslimages">
                    <option value="">-</option>
                    <option value="Y"><?php echo lang('general_word_yes'); ?></option>
                    <option value="N"><?php echo lang('general_word_no'); ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="actionbody collapse">
        <script>
            var lang_filter_actions_delete_warning = '<?php echo lang('filter_actions_delete_warning'); ?>';
        </script>
        <div class="mb-2 btn-group">
            <span class="h6 mr-1"><?php echo lang('filter_actions_w_selected'); ?></span>
            <button type="button" class="btn btn-sm btn-primary mr-1" id="btnUpdateFromCallbook"><?php echo lang('filter_actions_update_f_callbook'); ?></button>
            <button type="button" class="btn btn-sm btn-primary mr-1" id="queueBureau"><?php echo lang('filter_actions_queue_bureau'); ?></button>
            <button type="button" class="btn btn-sm btn-primary mr-1" id="queueDirect"><?php echo lang('filter_actions_queue_direct'); ?></button>
            <button type="button" class="btn btn-sm btn-primary mr-1" id="queueElectronic"><?php echo lang('filter_actions_queue_electronic'); ?></button>
            <button type="button" class="btn btn-sm btn-success mr-1" id="sentBureau"><?php echo lang('filter_actions_sent_bureau'); ?></button>
            <button type="button" class="btn btn-sm btn-success mr-1" id="sentDirect"><?php echo lang('filter_actions_sent_direct'); ?></button>
            <button type="button" class="btn btn-sm btn-success mr-1" id="sentElectronic"><?php echo lang('filter_actions_sent_electronic'); ?></button>
            <button type="button" class="btn btn-sm btn-warning mr-1" id="dontSend"><?php echo lang('filter_actions_not_sent'); ?></button>
            <button type="button" class="btn btn-sm btn-warning mr-1" id="notRequired"><?php echo lang('filter_actions_qsl_n_required'); ?></button>
            <button type="button" class="btn btn-sm btn-warning mr-1" id="receivedBureau"><?php echo lang('filter_actions_recv_bureau'); ?></button>
            <button type="button" class="btn btn-sm btn-warning mr-1" id="receivedDirect"><?php echo lang('filter_actions_recv_direct'); ?></button>
            <button type="button" class="btn btn-sm btn-warning mr-1" id="receivedElectronic"><?php echo lang('filter_actions_recv_electronic'); ?></button>
            <button type="button" class="btn btn-sm btn-info mr-1" id="exportAdif"><?php echo lang('filter_actions_create_adif'); ?></button>
            <button type="button" class="btn btn-sm btn-info mr-1" id="printLabel"><?php echo lang('filter_actions_print_label'); ?></button>
            <button type="button" class="btn btn-sm btn-info mr-1" id="qslSlideshow"><?php echo lang('filter_actions_qsl_slideshow'); ?></button>
            <button type="button" class="btn btn-sm btn-danger mr-1" id="deleteQsos"><?php echo lang('filter_actions_delete'); ?></button>
        </div>
    </div>
    <div class="quickfilterbody collapse">
        <div class="mb-2 btn-group">
            <span class="h6 mr-1"><?php echo lang('filter_quicksearch_w_sel'); ?></span>
			<?php if (($options->dx->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchCallsign"><?php echo lang('filter_search_callsign'); ?></button><?php
            } ?>
			<?php if (($options->dxcc->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchDxcc"><?php echo lang('filter_search_dxcc'); ?></button><?php
            } ?>
			<?php if (($options->state->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchState"><?php echo lang('filter_search_state'); ?></button><?php
            } ?>
			<?php if (($options->refs->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchGridsquare"><?php echo lang('filter_search_gridsquare'); ?></button><?php
            } ?>
			<?php if (($options->cqzone->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchCqZone"><?php echo lang('filter_search_cq_zone'); ?></button><?php
            } ?>
			<?php if (($options->mode->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchMode"><?php echo lang('filter_search_mode'); ?></button><?php
            } ?>
			<?php if (($options->band->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchBand"><?php echo lang('filter_search_band'); ?></button><?php
            } ?>
            <?php if (($options->iota->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchIota"><?php echo lang('filter_search_iota'); ?></button><?php
            } ?>
			<?php if (($options->refs->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchSota"><?php echo lang('filter_search_sota'); ?></button><?php
            } ?>
            <?php if (($options->refs->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchPota"><?php echo lang('filter_search_pota'); ?></button><?php
            } ?>
            <?php if (($options->refs->show ?? "true") == "true") { ?>
                <button type="button" class="btn btn-sm btn-primary mr-1" id="searchWwff"><?php echo lang('filter_search_wwff'); ?></button><?php
            } ?>
        </div>
    </div>
<div class="form-row pt-2">
    <div class="form-group form-inline col-lg d-flex flex-row justify-content-center align-items-center">
        <button type="button" class="btn btn-sm btn-primary mr-1" data-toggle="collapse"
            data-target=".quickfilterbody"><?php echo lang('filter_quickfilters'); ?></button>
        <button type="button" class="btn btn-sm btn-primary mr-1" data-toggle="collapse"
            data-target=".qslfilterbody"><?php echo lang('filter_qsl_filters'); ?></button>
        <button type="button" class="btn btn-sm btn-primary mr-1" data-toggle="collapse"
            data-target=".filterbody"><?php echo lang('filter_filters'); ?></button>
        <button type="button" class="btn btn-sm btn-primary mr-1" data-toggle="collapse"
            data-target=".actionbody"><?php echo lang('filter_actions'); ?></button>
        <label for="qsoResults" class="mr-2"><?php echo lang('filter_results'); ?></label>
        <select id="qsoResults" name="qsoResults" class="form-control form-control-sm mr-2">
            <option value="250">250</option>
            <option value="1000">1000</option>
            <option value="2500">2500</option>
            <option value="5000">5000</option>
        </select>
        <button type="submit" class="btn btn-sm btn-primary mr-1" id="searchButton"><?php echo lang('filter_search'); ?></button>
        <button type="button" class="btn btn-sm btn-primary mr-1" id="dupeButton"><?php echo lang('filter_dupes'); ?></button>
        <button type="button" class="btn btn-sm btn-primary mr-1" id="mapButton" onclick="mapQsos(this.form);"><?php echo lang('filter_map'); ?></button>
		<button type="options" class="btn btn-sm btn-primary mr-1" id="optionButton"><?php echo lang('filter_options'); ?></button>
		<button type="reset" class="btn btn-sm btn-danger mr-1" id="resetButton"><?php echo lang('filter_reset'); ?></button>

    </div>
</div>
</form>
<table style="width:100%" class="table-sm table table-bordered table-hover table-striped table-condensed text-center" id="qsoList">
    <thead>
        <tr>
            <th>
                <div class="form-check" style="margin-top: -1.5em"><input class="form-check-input" type="checkbox" id="checkBoxAll" /></div>
            </th>
			<?php if (($options->datetime->show ?? "true") == "true") {
				echo '<th>' . lang('general_word_datetime') . '</th>';
			} ?>
			<?php if (($options->de->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_de') . '</th>';
			} ?>
			<?php if (($options->dx->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_dx') . '</th>';
			} ?>
			<?php if (($options->mode->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_mode') . '</th>';
			} ?>
			<?php if (($options->rsts->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_rsts') . '</th>';
			} ?>
			<?php if (($options->rstr->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_rstr') . '</th>';
			} ?>
            <?php if (($options->band->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_band') . '</th>';
			} ?>
			<?php if (($options->myrefs->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_myrefs') . '</th>';
			} ?>
			<?php if (($options->refs->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_refs') . '</th>';
			} ?>
			<?php if (($options->name->show ?? "true") == "true") {
				echo '<th>' . lang('general_word_name') . '</th>';
			} ?>
			<?php if (($options->qslvia->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_qslvia') . '</th>';
			} ?>
			<?php if (($options->qsl->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_qsl') . '</th>';
			} ?>
            <?php if ($this->session->userdata('user_eqsl_name') != ""  && ($options->eqsl->show ?? "true") == "true"){
					echo '<th class="eqslconfirmation">eQSL</th>';
				} ?>
            <?php if ($this->session->userdata('user_lotw_name') != "" && ($options->lotw->show ?? "true") == "true"){
					echo '<th class="lotwconfirmation">LoTW</th>';
				} ?>
			<?php if (($options->qslmsg->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_qslmsg') . '</th>';
			} ?>
			<?php if (($options->dxcc->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_dxcc') . '</th>';
			} ?>
			<?php if (($options->state->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_state') . '</th>';
			} ?>
			<?php if (($options->cqzone->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_cq_zone') . '</th>';
			} ?>
			<?php if (($options->iota->show ?? "true") == "true") {
				echo '<th>' . lang('gen_hamradio_iota') . '</th>';
			} ?>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>
