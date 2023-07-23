<script>
	var dxcluster_provider="<?php echo base_url(); ?>index.php/dxcluster";
	var cat_timeout_interval="<?php echo $this->optionslib->get_option('cat_timeout_interval'); ?>";
</script>


<div class="container">
<br>

<h2><?php echo $page_title; ?></h2>
<div class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" href="index">DXMap</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="list">DXList</a>
			</li>
		</ul>
</div>
<div class="tab-content" id="myTabContent">
<div class="messages"></div>
<div class="form-inline">
		<label class="my-1 mr-2" for="radio"><?php echo lang('gen_hamradio_radio'); ?></label>
		<select class="form-control-sm radios my-1 mr-sm-2" id="radio" name="radio">
			<option value="0" selected="selected"><?php echo lang('general_word_none'); ?></option>
			<?php foreach ($radios->result() as $row) { ?>
			<option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
			<?php } ?>
		</select>

		<label class="my-1 mr-2" for="band"><?php echo lang('gen_hamradio_band'); ?></label>
		<select id="band" class="form-control-sm my-1 mr-sm-2" name="band">
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

<figure class="highcharts-figure">
    <div id="bandmap"></div>
    <p class="highcharts-description">
    </p>
</figure>
</div>
</div>
