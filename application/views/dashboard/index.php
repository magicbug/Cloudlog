<?php
function echo_table_header_col($ctx, $name) {
	switch($name) {
		case 'Mode': echo '<th>'.$ctx->lang->line('gen_hamradio_mode').'</th>'; break;
		case 'RSTS': echo '<th class="d-none d-sm-table-cell">'.$ctx->lang->line('gen_hamradio_rsts').'</th>'; break;
		case 'RSTR': echo '<th class="d-none d-sm-table-cell">'.$ctx->lang->line('gen_hamradio_rstr').'</th>'; break;
		case 'Country': echo '<th>'.$ctx->lang->line('general_word_country').'</th>'; break;
		case 'IOTA': echo '<th>'.$ctx->lang->line('gen_hamradio_iota').'</th>'; break;
		case 'SOTA': echo '<th>'.$ctx->lang->line('gen_hamradio_sota').'</th>'; break;
		case 'State': echo '<th>'.$ctx->lang->line('gen_hamradio_state').'</th>'; break;
		case 'Grid': echo '<th>'.$ctx->lang->line('gen_hamradio_gridsquare').'</th>'; break;
		case 'Band': echo '<th>'.$ctx->lang->line('gen_hamradio_band').'</th>'; break;
	}
}

function echo_table_col($row, $name) {
	switch($name) {
		case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE . '</td>'; break;
		case 'RSTS':    echo '<td class="d-none d-sm-table-cell">' . $row->COL_RST_SENT; if ($row->COL_STX_STRING) { echo '<span class="label">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
		case 'RSTR':    echo '<td class="d-none d-sm-table-cell">' . $row->COL_RST_RCVD; if ($row->COL_SRX_STRING) { echo '<span class="label">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
		case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY))) . '</td>'; break;
		case 'IOTA':    echo '<td>' . ($row->COL_IOTA) . '</td>'; break;
		case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF) . '</td>'; break;
		case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE . '</td>'; break;
		case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); } echo '</td>'; break;
		case 'State':   echo '<td>' . ($row->COL_STATE) . '</td>'; break;
	}
}
?>
<div class="container dashboard">
<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>

	<?php if($todays_qsos >= 1) { ?>
		<div class="alert alert-success" role="alert">
			  <?php echo $this->lang->line('dashboard_you_have_had'); ?> <strong><?php echo $todays_qsos; ?></strong> <?php echo $this->lang->line('dashboard_qsos_today'); ?>
		</div>
	<?php } else { ?>
		<div class="alert alert-warning" role="alert">
			  <span class="badge badge-info"><?php echo $this->lang->line('general_word_important'); ?></span> <i class="fas fa-broadcast-tower"></i> <?php echo $this->lang->line('notice_turn_the_radio_on'); ?>
		</div>
	<?php } ?>

	<?php if($current_active == 0) { ?>
		<div class="alert alert-danger" role="alert">
		  <?php echo $this->lang->line('error_no_active_station_profile'); ?>
		</div>
	<?php } ?>

<?php } ?>
</div>

<!-- Map -->
<div id="map" style="width: 100%; height: 350px"></div>

<div style="padding-top: 0px; margin-top: 5px;" class="container dashboard">

<!-- Log Data -->
<div class="row logdata">
  <div class="col-sm-8">

  	<div class="table-responsive">
    	<table class="table table-striped table-hover">

    		<thead>
				<tr class="titles">
					<th><?php echo $this->lang->line('general_word_date'); ?></th>

					<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
					<th><?php echo $this->lang->line('general_word_time'); ?></th>
					<?php } ?>
					<th><?php echo $this->lang->line('gen_hamradio_call'); ?></th>
					<?php
					echo_table_header_col($this, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
					echo_table_header_col($this, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
					echo_table_header_col($this, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
					echo_table_header_col($this, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
				?>
				</tr>
			</thead>

			<?php
			$i = 0;
			foreach ($last_five_qsos->result() as $row) { ?>
				<?php  echo '<tr class="tr'.($i & 1).'">'; ?>

					<?php

					// Get Date format
					if($this->session->userdata('user_date_format')) {
						// If Logged in and session exists
						$custom_date_format = $this->session->userdata('user_date_format');
					} else {
						// Get Default date format from /config/cloudlog.php
						$custom_date_format = $this->config->item('qso_date_format');
					}

					?>

					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
					<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
					<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>

					<?php } ?>
					<td>
                        <a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a>
					</td>
					<?php
						echo_table_col($row, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
						echo_table_col($row, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
						echo_table_col($row, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
						echo_table_col($row, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
					?>
				</tr>
			<?php $i++; } ?>
		</table>
	</div>
  </div>

  <div class="col-sm-4">
  	<div class="table-responsive">

		<?php if($radio_status->num_rows()) { ?>

			<table class="table table-striped">
					<tr class="titles">
						<td colspan="2"><i class="fas fa-broadcast-tower"></i> Radio Status</td>
					</tr>

					<?php foreach ($radio_status->result_array() as $row) { ?>
					<tr>
						<td><?php echo $row['radio']; ?></td>
						<td>
							<?php if($row['radio'] == "SatPC32") { ?>
								<?php echo $row['sat_name']; ?>
							<?php } else { ?>
								<?php echo $this->frequency->hz_to_mhz($row['frequency']); ?> (<?php echo $row['mode']; ?>)
							<?php } ?>
						</td>
					</tr>
					<?php } ?>

				</table>

		<?php } ?>

    	<table class="table table-striped">
			<tr class="titles">
				<td colspan="2"><i class="fas fa-chart-bar"></i> <?php echo $this->lang->line('dashboard_qso_breakdown'); ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_total'); ?></td>
				<td><?php echo $total_qsos; ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_year'); ?></td>
				<td><?php echo $year_qsos; ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_month'); ?></td>
				<td><?php echo $month_qsos; ?></td>
			</tr>
		</table>



		<table class="table table-striped">
			<tr class="titles">
				<td colspan="2"><i class="fas fa-globe-europe"></i> <?php echo $this->lang->line('dashboard_countries_breakdown'); ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_worked'); ?></td>
				<td><?php echo $total_countries; ?></td>
			</tr>
			<tr>
				<td><a href="#" onclick="return false" data-original-title="QSL Cards / eQSL / LoTW" data-toggle="tooltip"><?php echo $this->lang->line('general_word_confirmed'); ?></a></td>
				<td>
					<?php echo $total_countries_confirmed_paper; ?> /
					<?php echo $total_countries_confirmed_eqsl; ?> /
					<?php echo $total_countries_confirmed_lotw; ?>
				</td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_needed'); ?></td>
				<td><?php echo $total_countries_needed; ?></td>
			</tr>
		</table>

		<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE) { ?>
		<table class="table table-striped">
			<tr class="titles">
				<td colspan="2"><i class="fas fa-envelope"></i> <?php echo $this->lang->line('general_word_qslcards'); ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_sent'); ?></td>
				<td><?php echo $total_qsl_sent; ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_received'); ?></td>
				<td><?php echo $total_qsl_recv; ?></td>
			</tr>

			<tr>
				<td><?php echo $this->lang->line('general_word_requested'); ?></td>
				<td><?php echo $total_qsl_requested; ?></td>
			</tr>
		</table>
		<?php } ?>
	</div>
  </div>
</div>

</div>
