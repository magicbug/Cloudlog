<script>
	var dxcluster_provider="<?php echo base_url(); ?>index.php/dxcluster";
	var cat_timeout_interval="<?php echo $this->optionslib->get_option('cat_timeout_interval'); ?>";
	var dxcluster_maxage=<?php echo $this->optionslib->get_option('dxcluster_maxage'); ?>;
	var custom_date_format = "<?php echo $custom_date_format ?>";
</script>

<style>
.spotted_call {
	cursor: alias;
}

.kHz::after {
	content: " kHz";
}
.fresh{
    -webkit-transition: all 15s ease;
    -moz-transition: all 15s ease;
    -o-transition: all 15s ease;
    transition: all 15s ease;
}
</style>


<div class="container">
<br>
<center><button type="button" class="btn" id="menutoggle"><i class="fa fa-arrow-up" id="menutoggle_i"></i></button></center>

<h2 id="dxtitle"><?php echo $page_title; ?></h2>

<div id="dxtabs" class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link" href="index">BandMap</a>
			</li>
			<li class="nav-item">
				<a class="nav-link active" href="list">BandList</a>
			</li>
		</ul>
</div>

<div class="tab-content" id="myTabContent">
<div class="messages my-1 mr-2"></div>
<div class="form-inline">
		<label class="my-1 mr-2" for="radio"><?php echo lang('gen_hamradio_radio'); ?></label>
		<select class="form-control-sm radios my-1 mr-sm-2" id="radio" name="radio">
			<option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
			<?php foreach ($radios->result() as $row) { ?>
			<option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
			<?php } ?>
		</select>
		<label class="my-1 mr-2" for="decontSelect">Spots de</label>
		<select class="form-control-sm my-1 mr-sm-2" id="decontSelect" name="dxcluster_decont" aria-describedby="dxcluster_decontHelp" required>
				<option value="Any">*</option>
				<option value="AF"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'AF') { echo " selected"; } ?>>Africa</option>
				<option value="AN"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'AN') { echo " selected"; } ?>>Antarctica</option>
				<option value="AS"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'AS') { echo " selected"; } ?>>Asia</option>
				<option value="EU"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'EU') { echo " selected"; } ?>>Europe</option>
				<option value="NA"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'NA') { echo " selected"; } ?>>North America</option>
				<option value="OC"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'OC') { echo " selected"; } ?>>Oceania</option>
				<option value="SA"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'SA') { echo " selected"; } ?>>South America</option>
                </select>

		<label class="my-1 mr-2" for="band"><?php echo lang('gen_hamradio_band'); ?></label>
		<select id="band" class="form-control-sm my-1 mr-sm-2" name="band">
			<option value="All">All</option>
			<?php foreach($bands as $key=>$bandgroup) {
					echo '<optgroup label="' . strtoupper($key) . '">';
					foreach($bandgroup as $band) {
					echo '<option value="' . $band . '"';
						if ($band == "20m") echo ' selected';
						echo '>' . $band . '</option>'."\n";
					}
					echo '</optgroup>';
				}
			?>
		</select>
	</div>

                <p>

                <table style="width:100%" class="table-sm table spottable table-bordered table-hover table-striped table-condensed">
                    <thead>
                        <tr class="log_title titles">
                            <th><?php echo lang('general_word_date'); ?>/<?php echo lang('general_word_time'); ?></th>
			    <th><?php echo lang('gen_hamradio_frequency'); ?></th>
                            <th><?php echo lang('gen_hamradio_call'); ?></th>
			    <th>DXCC</th>
                            <th><?php echo lang('gen_hamradio_call'); ?> Spotter</th>
                        </tr>
                    </thead>

                    <tbody class="spots_table_contents">
                    </tbody>
                </table>
            </div>

</div>
</div>
