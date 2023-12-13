<div class="table-responsive">
	<table class="table table-sm table-striped table-hover">
		<tr class="titles">
			<td><?php echo lang('general_word_date'); ?></td>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
				<td><?php echo lang('general_word_time'); ?></td>
			<?php } ?>
			<td><?php echo lang('gen_hamradio_call'); ?></td>
			<?php
			echo '<td>';
			switch($this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1')) {
				case 'Mode': echo lang('gen_hamradio_mode'); break;
				case 'RSTS': echo lang('gen_hamradio_rsts'); break;
				case 'RSTR': echo lang('gen_hamradio_rstr'); break;
				case 'Country': echo lang('general_word_country'); break;
				case 'IOTA': echo lang('gen_hamradio_iota'); break;
				case 'SOTA': echo lang('gen_hamradio_sota'); break;
				case 'State': echo lang('gen_hamradio_state'); break;
				case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
				case 'Distance': echo lang('gen_hamradio_distance'); break;
				case 'Band': echo lang('gen_hamradio_band'); break;
				case 'Frequency': echo lang('gen_hamradio_frequency'); break;
				case 'Operator': echo lang('gen_hamradio_operator'); break;
			}
			echo '</td>';
			echo '<td>';
			switch($this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2')) {
				case 'Mode': echo lang('gen_hamradio_mode'); break;
				case 'RSTS': echo lang('gen_hamradio_rsts'); break;
				case 'RSTR': echo lang('gen_hamradio_rstr'); break;
				case 'Country': echo lang('general_word_country'); break;
				case 'IOTA': echo lang('gen_hamradio_iota'); break;
				case 'SOTA': echo lang('gen_hamradio_sota'); break;
				case 'State': echo lang('gen_hamradio_state'); break;
				case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
				case 'Distance': echo lang('gen_hamradio_distance'); break;
				case 'Band': echo lang('gen_hamradio_band'); break;
				case 'Frequency': echo lang('gen_hamradio_frequency'); break;
				case 'Operator': echo lang('gen_hamradio_band'); break;
			}
			echo '</td>';
			echo '<td>';
			switch($this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3')) {
				case 'Mode': echo lang('gen_hamradio_mode'); break;
				case 'RSTS': echo lang('gen_hamradio_rsts'); break;
				case 'RSTR': echo lang('gen_hamradio_rstr'); break;
				case 'Country': echo lang('general_word_country'); break;
				case 'IOTA': echo lang('gen_hamradio_iota'); break;
				case 'SOTA': echo lang('gen_hamradio_sota'); break;
				case 'State': echo lang('gen_hamradio_state'); break;
				case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
				case 'Distance': echo lang('gen_hamradio_distance'); break;
				case 'Band': echo lang('gen_hamradio_band'); break;
				case 'Frequency': echo lang('gen_hamradio_frequency'); break;
				case 'Operator': echo lang('gen_hamradio_operator'); break;
			}
			echo '</td>';
			echo '<td>';
			switch($this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4')) {
				case 'Mode': echo lang('gen_hamradio_mode'); break;
				case 'RSTS': echo lang('gen_hamradio_rsts'); break;
				case 'RSTR': echo lang('gen_hamradio_rstr'); break;
				case 'Country': echo lang('general_word_country'); break;
				case 'IOTA': echo lang('gen_hamradio_iota'); break;
				case 'SOTA': echo lang('gen_hamradio_sota'); break;
				case 'State': echo lang('gen_hamradio_state'); break;
				case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
				case 'Distance': echo lang('gen_hamradio_distance'); break;
				case 'Band': echo lang('gen_hamradio_band'); break;
				case 'Frequency': echo lang('gen_hamradio_frequency'); break;
				case 'Operator': echo lang('gen_hamradio_operator'); break;
			}
			echo '</td>';
			echo '<td>';
			switch($this->session->userdata('user_column5')==""?'Country':$this->session->userdata('user_column5')) {
				case 'Mode': echo lang('gen_hamradio_mode'); break;
				case 'RSTS': echo lang('gen_hamradio_rsts'); break;
				case 'RSTR': echo lang('gen_hamradio_rstr'); break;
				case 'Country': echo lang('general_word_country'); break;
				case 'IOTA': echo lang('gen_hamradio_iota'); break;
				case 'SOTA': echo lang('gen_hamradio_sota'); break;
				case 'State': echo lang('gen_hamradio_state'); break;
				case 'Grid': echo lang('gen_hamradio_gridsquare'); break;
				case 'Distance': echo lang('gen_hamradio_distance'); break;
				case 'Band': echo lang('gen_hamradio_band'); break;
				case 'Frequency': echo lang('gen_hamradio_frequency'); break;
				case 'Operator': echo lang('gen_hamradio_operator'); break;
			}
			echo '</td><td></td></tr>';

			$i = 0;  foreach ($results->result() as $row) { ?>
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
			<?php  echo '<tr class="tr'.($i & 1).'" id ="qso_'. $row->COL_PRIMARY_KEY .'">'; ?>
			<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($custom_date_format, $timestamp); ?></td>
			<?php if(($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
				<td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
			<?php } ?>
			<td>
				<a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?></a>
			</td>
			<?php

			switch($this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
			}
			echo '</td>';

			switch($this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
			}
			echo '</td>';
			switch($this->session->userdata('user_column5')==""?'Country':$this->session->userdata('user_column5')) {
				case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; break;
				case 'RSTS':    echo '<td>' . $row->COL_RST_SENT; if ($row->COL_STX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';}; break;
				case 'RSTR':    echo '<td>' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo '<span class="badge text-bg-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo '<span class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';}; break;
				case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY)));; break;
				case 'IOTA':    echo '<td>' . ($row->COL_IOTA); break;
				case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF); break;
				case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF); break;
				case 'POTA':    echo '<td>' . ($row->COL_POTA_REF); break;
				case 'Grid':    echo '<td>'; echo strlen($row->COL_GRIDSQUARE)==0?$row->COL_VUCC_GRIDS:$row->COL_GRIDSQUARE; break;
				case 'Distance':echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : ''); break;
				case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); }; break;
				case 'State':   echo '<td>' . ($row->COL_STATE); break;
				case 'Operator':   echo '<td>' . ($row->COL_OPERATOR); break;
			}
			echo '</td>
				<td><button onclick="addQsoToQsl(' . $row->COL_PRIMARY_KEY . ', \'' . $filename . '\')" class="btn btn-sm btn-success" type="button"> Add to QSL</button></td>';
			echo '</tr>';
			$i++; } ?>

	</table>
</div>
</div>
