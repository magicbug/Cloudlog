<div id="qso-last-table-content" hx-get="<?php echo site_url('/qso/component_past_contacts?page=' . (int)$current_page); ?>" hx-trigger="every 5s" hx-target="this" hx-swap="outerHTML" hx-vals='js:{_t: Date.now()}'>

<div class="table-responsive" style="font-size: 0.95rem;">
  <table class="table">
    <tr class="log_title titles">
      <th><?php echo lang('general_word_date'); ?>/<?php echo lang('general_word_time'); ?></th>
	<th><?php echo lang('gen_hamradio_call'); ?></th>
	<?php
	echo_table_header_col($this, $this->session->userdata('user_column1')==""?'Mode':$this->session->userdata('user_column1'));
	echo_table_header_col($this, $this->session->userdata('user_column2')==""?'RSTS':$this->session->userdata('user_column2'));
	echo_table_header_col($this, $this->session->userdata('user_column3')==""?'RSTR':$this->session->userdata('user_column3'));
	echo_table_header_col($this, $this->session->userdata('user_column4')==""?'Band':$this->session->userdata('user_column4'));
       ?>
      </tr>

    <?php

    // Get Date format
    if($this->session->userdata('user_date_format')) {
        // If Logged in and session exists
        $custom_date_format = $this->session->userdata('user_date_format');
    } else {
        // Get Default date format from /config/cloudlog.php
        $custom_date_format = $this->config->item('qso_date_format');
    }

    $i = 0;
  if($query != false) {
  foreach ($query->result() as $row) {
        echo '<tr class="tr'.($i & 1).'">';
          echo '<td>';
              $timestamp = strtotime($row->COL_TIME_ON);
              echo date($custom_date_format, $timestamp);
              echo date(' H:i',strtotime($row->COL_TIME_ON));
          ?>
        </td>
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
    <?php $i++; } } ?>
  </table>
</div>

<!-- Pagination Controls -->
<?php if (isset($total_pages) && $total_pages > 1): ?>
<?php
  $prev_label = $this->lang->line('general_word_previous', false) ?: 'Previous';
  $next_label = $this->lang->line('general_word_next', false) ?: 'Next';
?>
<nav aria-label="Previous contacts pagination">
  <ul class="pagination pagination-sm mb-0">
    <!-- Previous Button -->
    <li class="page-item <?php echo ($current_page == 0) ? 'disabled' : ''; ?>">
      <?php if ($current_page > 0): ?>
        <a class="page-link" href="#" hx-get="<?php echo site_url('/qso/component_past_contacts?page=' . ($current_page - 1)); ?>" hx-target="#qso-last-table-content" hx-swap="outerHTML">
          <?php echo $prev_label; ?>
        </a>
      <?php else: ?>
        <span class="page-link"><?php echo $prev_label; ?></span>
      <?php endif; ?>
    </li>

    <!-- Page Numbers -->
    <?php
    // Keep pagination compact: only show a sliding window near the current page.
    $visible_pages = 7;
    $half_window = (int) floor($visible_pages / 2);
    $start_page = max(0, $current_page - $half_window);
    $end_page = min($total_pages - 1, $start_page + $visible_pages - 1);
    $start_page = max(0, $end_page - $visible_pages + 1);

    if ($start_page > 0): ?>
      <li class="page-item disabled"><span class="page-link">...</span></li>
    <?php endif;

    for ($i = $start_page; $i <= $end_page; $i++):
      $page_num = $i + 1;
    ?>
      <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>">
        <?php if ($current_page == $i): ?>
          <span class="page-link"><?php echo $page_num; ?></span>
        <?php else: ?>
          <a class="page-link" href="#" hx-get="<?php echo site_url('/qso/component_past_contacts?page=' . $i); ?>" hx-target="#qso-last-table-content" hx-swap="outerHTML">
            <?php echo $page_num; ?>
          </a>
        <?php endif; ?>
      </li>
    <?php endfor;

    if ($end_page < $total_pages - 1): ?>
      <li class="page-item disabled"><span class="page-link">...</span></li>
    <?php endif; ?>

    <!-- Next Button -->
    <li class="page-item <?php echo ($current_page >= $total_pages - 1) ? 'disabled' : ''; ?>">
      <?php if ($current_page < $total_pages - 1): ?>
        <a class="page-link" href="#" hx-get="<?php echo site_url('/qso/component_past_contacts?page=' . ($current_page + 1)); ?>" hx-target="#qso-last-table-content" hx-swap="outerHTML">
          <?php echo $next_label; ?>
        </a>
      <?php else: ?>
        <span class="page-link"><?php echo $next_label; ?></span>
      <?php endif; ?>
    </li>
  </ul>
</nav>
<?php endif; ?>
</div>

<?php
function echo_table_col($row, $name) {
	$ci =& get_instance();
	switch($name) {
		case 'Mode':    echo '<td>'; echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE . '</td>'; break;
      case 'RSTS':    echo '<td class="d-none d-sm-table-cell">' . $row->COL_RST_SENT; if ($row->COL_STX) { echo ' <span data-bs-toggle="tooltip" title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge text-bg-light">'; printf("%03d", $row->COL_STX); echo '</span>';} if ($row->COL_STX_STRING) { echo ' <span data-bs-toggle="tooltip" title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge text-bg-light">' . $row->COL_STX_STRING . '</span>';} echo '</td>'; break;
      case 'RSTR':    echo '<td class="d-none d-sm-table-cell">' . $row->COL_RST_RCVD; if ($row->COL_SRX) { echo ' <span data-bs-toggle="tooltip" title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge text-bg-light">'; printf("%03d", $row->COL_SRX); echo '</span>';} if ($row->COL_SRX_STRING) { echo ' <span data-bs-toggle="tooltip" title="'.($row->COL_CONTEST_ID!=""?$row->COL_CONTEST_ID:"n/a").'" class="badge text-bg-light">' . $row->COL_SRX_STRING . '</span>';} echo '</td>'; break;
		case 'Country': echo '<td>' . ucwords(strtolower(($row->COL_COUNTRY))); if ($row->end != NULL) echo ' <span class="badge text-bg-danger">'.$ci->lang->line('gen_hamradio_deleted_dxcc').'</span>'  . '</td>'; break;
		case 'IOTA':    echo '<td>' . ($row->COL_IOTA) . '</td>'; break;
		case 'SOTA':    echo '<td>' . ($row->COL_SOTA_REF) . '</td>'; break;
		case 'WWFF':    echo '<td>' . ($row->COL_WWFF_REF) . '</td>'; break;
		case 'POTA':    echo '<td>' . ($row->COL_POTA_REF) . '</td>'; break;
		case 'Grid':    echo '<td>'; echoQrbCalcLink($row->COL_MY_GRIDSQUARE, $row->COL_VUCC_GRIDS, $row->COL_GRIDSQUARE); echo '</td>'; break;
		case 'Distance':    echo '<td>' . ($row->COL_DISTANCE ? $row->COL_DISTANCE . '&nbsp;km' : '') . '</td>'; break;
		case 'Band':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'.$row->COL_SAT_NAME.'</a></td>'; } else { echo strtolower($row->COL_BAND); } echo '</td>'; break;
		case 'Frequency':    echo '<td>'; if($row->COL_SAT_NAME != null) { echo '<a href="https://db.satnogs.org/search/?q='.$row->COL_SAT_NAME.'" target="_blank">'.$row->COL_SAT_NAME.'</a></td>'; } else { if($row->COL_FREQ != null) { echo $ci->frequency->hz_to_mhz($row->COL_FREQ); } else { echo strtolower($row->COL_BAND); } } echo '</td>'; break;
		case 'State':   echo '<td>' . ($row->COL_STATE) . '</td>'; break;
		case 'Operator': echo '<td>' . ($row->COL_OPERATOR) . '</td>'; break;
	}
}

function echo_table_header_col($ctx, $name) {
	switch($name) {
		case 'Mode': echo '<th>'.$ctx->lang->line('gen_hamradio_mode').'</th>'; break;
		case 'RSTS': echo '<th class="d-none d-sm-table-cell">'.$ctx->lang->line('gen_hamradio_rsts').'</th>'; break;
		case 'RSTR': echo '<th class="d-none d-sm-table-cell">'.$ctx->lang->line('gen_hamradio_rstr').'</th>'; break;
		case 'Country': echo '<th>'.$ctx->lang->line('general_word_country').'</th>'; break;
		case 'IOTA': echo '<th>'.$ctx->lang->line('gen_hamradio_iota').'</th>'; break;
		case 'SOTA': echo '<th>'.$ctx->lang->line('gen_hamradio_sota').'</th>'; break;
		case 'WWFF': echo '<th>'.$ctx->lang->line('gen_hamradio_wwff').'</th>'; break;
		case 'POTA': echo '<th>'.$ctx->lang->line('gen_hamradio_pota').'</th>'; break;
		case 'State': echo '<th>'.$ctx->lang->line('gen_hamradio_state').'</th>'; break;
		case 'Grid': echo '<th>'.$ctx->lang->line('gen_hamradio_gridsquare').'</th>'; break;
		case 'Distance': echo '<th>'.$ctx->lang->line('gen_hamradio_distance').'</th>'; break;
		case 'Band': echo '<th>'.$ctx->lang->line('gen_hamradio_band').'</th>'; break;
		case 'Frequency': echo '<th>'.$ctx->lang->line('gen_hamradio_frequency').'</th>'; break;
		case 'Operator': echo '<th>'.$ctx->lang->line('gen_hamradio_operator').'</th>'; break;
	}
}

function echoQrbCalcLink($mygrid, $grid, $vucc) {
	if (!empty($grid)) {
		echo $grid . ' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $grid . '\')"><i class="fas fa-globe"></i></a>';
	} else if (!empty($vucc)) {
		echo $vucc .' <a href="javascript:spawnQrbCalculator(\'' . $mygrid . '\',\'' . $vucc . '\')"><i class="fas fa-globe"></i></a>';
	}
}
?>
