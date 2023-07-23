<script>
	var dxcluster_provider="<?php echo base_url(); ?>index.php/dxcluster";
	var cat_timeout_interval="<?php echo $this->optionslib->get_option('cat_timeout_interval'); ?>";
	var custom_date_format = "<?php echo $custom_date_format ?>";
</script>


<div class="container">
<br>

<h2><?php echo $page_title; ?></h2>
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

          <div class="card log">
                <div class="card-header"><h5 class="card-title">DXCluster</h5></div>
                <p>

                <table style="width:100%" class="table-sm table spottable table-bordered table-hover table-striped table-condensed">
                    <thead>
                        <tr class="log_title titles">
                            <th><?php echo lang('general_word_date'); ?>/<?php echo lang('general_word_time'); ?></th>
			    <th><?php echo lang('gen_hamradio_frequency'); ?></th>
                            <th><?php echo lang('gen_hamradio_call'); ?></th>
			    <th>DXCC</th>
                        </tr>
                    </thead>

                    <tbody class="spots_table_contents">
                    </tbody>
                </table>
            </div>
        </div>

</div>
