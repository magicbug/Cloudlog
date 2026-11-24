<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$show_time = (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time')));
$col1 = $this->session->userdata('user_column1') == "" ? 'Mode' : $this->session->userdata('user_column1');
$col2 = $this->session->userdata('user_column2') == "" ? 'RSTS' : $this->session->userdata('user_column2');
$col3 = $this->session->userdata('user_column3') == "" ? 'RSTR' : $this->session->userdata('user_column3');
$col4 = $this->session->userdata('user_column4') == "" ? 'Band' : $this->session->userdata('user_column4');

$custom_date_format = $this->session->userdata('user_date_format')
	? $this->session->userdata('user_date_format')
	: $this->config->item('qso_date_format');

$rows = (!empty($last_five_qsos) && $last_five_qsos->num_rows() > 0) ? $last_five_qsos->result() : [];
?>
<div class="table-responsive">
	<table class="table table-striped table-hover dashboard-qso-table mb-0">
		<thead>
			<tr class="titles">
				<th><?php echo lang('general_word_date'); ?></th>
				<?php if ($show_time) { ?>
					<th><?php echo lang('general_word_time'); ?></th>
				<?php } ?>
				<th><?php echo lang('gen_hamradio_call'); ?></th>
				<?php
				echo_table_header_col($this, $col1);
				echo_table_header_col($this, $col2);
				echo_table_header_col($this, $col3);
				echo_table_header_col($this, $col4);
				?>
			</tr>
		</thead>
		<tbody>
			<?php if (empty($rows)) { ?>
				<tr>
					<td colspan="<?php echo $show_time ? 7 : 6; ?>" class="text-center text-muted py-4">
						<i class="fas fa-broadcast-tower me-1"></i> No QSOs logged yet in this logbook.
					</td>
				</tr>
			<?php } else { ?>
				<?php foreach ($rows as $i => $row) {
					$timestamp = strtotime($row->COL_TIME_ON);
				?>
					<tr id="qso_<?php echo $row->COL_PRIMARY_KEY; ?>" class="tr<?php echo ($i & 1); ?>">
						<td><?php echo date($custom_date_format, $timestamp); ?></td>
						<?php if ($show_time) { ?>
							<td class="text-muted"><?php echo date('H:i', $timestamp); ?></td>
						<?php } ?>
						<td>
							<a id="edit_qso" class="qso-callsign-link" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0", "&Oslash;", strtoupper($row->COL_CALL)); ?></a>
						</td>
						<?php
						echo_table_col($row, $col1);
						echo_table_col($row, $col2);
						echo_table_col($row, $col3);
						echo_table_col($row, $col4);
						?>
					</tr>
				<?php } ?>
			<?php } ?>
		</tbody>
	</table>
</div>
