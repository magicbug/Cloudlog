

<style>
/*Legend specific*/
.legend {
  padding: 6px 8px;
  font: 14px Arial, Helvetica, sans-serif;
  background: white;
  background: rgba(255, 255, 255, 0.8);
  line-height: 24px;
  color: #555;
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
  opacity: 0.7;
}
</style>
<div class="container">

	<br>

	<h2><?php echo $page_title; ?></h2>

<form class="form-inline">
            <label class="my-1 mr-2" for="band"><?php echo lang('gridsquares_band'); ?></label>
            <select class="custom-select my-1 mr-sm-2"  id="band">
                <option value="All">All</option>
				<?php foreach($bands as $band) {
					echo '<option value="' . $band . '"' . '>' . $band . '</option>'."\n";
                } ?>
            </select>
            <?php if (count($sats_available) != 0) { ?>
                <label class="my-1 mr-2" for="distplot_sats"><?php echo lang('gridsquares_sat'); ?></label>
                <select class="custom-select my-1 mr-sm-2"  id="sats" disabled>
                    <option value="All">All</option>
                    <?php foreach($sats_available as $sat) {
                        echo '<option value="' . $sat . '"' . '>' . $sat . '</option>'."\n";
                    } ?>
                </select>
            <?php } else { ?>
                <input id="sats" type="hidden" value="All"></input>
            <?php } ?>
			<label class="my-1 mr-2" for="mode"><?php echo lang('gridsquares_mode'); ?></label>
            <select class="custom-select my-1 mr-sm-2"  id="mode">
			<option value="All">All</option>
                    <?php
                    foreach($modes as $mode){
                        if ($mode->submode == null) {
                            echo '<option value="' . $mode . '">' . strtoupper($mode) . '</option>'."\n";
                        }
                    }
                    ?>
            </select>
			<label class="my-1 mr-2"><?php echo lang('gridsquares_confirmation'); ?></label>
                <div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="qsl" id="qsl" checked>
                        <label class="form-check-label" for="qsl">QSL</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="lotw" id="lotw" checked>
                        <label class="form-check-label" for="lotw">LoTW</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="eqsl" id="eqsl">
                        <label class="form-check-label" for="eqsl">eQSL</label>
                    </div>
                </div>

            <button id="plot" type="button" name="plot" class="btn btn-primary  ld-ext-right" onclick="gridPlot(this.form)"><?php echo lang('gridsquares_button_plot'); ?><div class="ld ld-ring ld-spin"></div></button>
</form>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>
</div>

<div id="gridmapcontainer">
	<div id="gridsquare_map" style="width: 100%; height: 800px"></div>
</div>
<script>var gridsquaremap = true;
<?php
    echo 'var jslayer ="' . $layer .'";';
    echo "var jsattribution ='" . $attribution . "';";

    echo 'var gridsquares_gridsquares = "' . $gridsquares_gridsquares . '";';
    echo 'var gridsquares_gridsquares_confirmed = "' . $gridsquares_gridsquares_confirmed . '";';
    echo 'var gridsquares_gridsquares_not_confirmed = "' . $gridsquares_gridsquares_not_confirmed . '";';
    echo 'var gridsquares_gridsquares_total_worked = "' . $gridsquares_gridsquares_total_worked . '";';
?>
</script>