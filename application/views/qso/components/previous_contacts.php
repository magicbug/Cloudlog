<div id="qso-last-table">

<div class="table-responsive" style="font-size: 0.95rem;">
  <table class="table">
    <tr class="log_title titles">
      <td><?php echo lang('general_word_date'); ?>/<?php echo lang('general_word_time'); ?></td>
      <td><?php echo lang('gen_hamradio_call'); ?></td>
      <td><?php echo lang('gen_hamradio_mode'); ?></td>
      <td><?php echo lang('gen_hamradio_rsts'); ?></td>
      <td><?php echo lang('gen_hamradio_rstr'); ?></td>
      <?php if ($this->session->userdata('user_column1')=='Frequency' || $this->session->userdata('user_column2')=='Frequency' || $this->session->userdata('user_column3')=='Frequency' || $this->session->userdata('user_column4')=='Frequency' || $this->session->userdata('user_column5')=='Frequency') {
               echo '<td>'.lang('gen_hamradio_frequency').'</td>';
            } else {
               echo '<td>'.lang('gen_hamradio_band').'</td>';
            }
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
          <td><?php echo $row->COL_SUBMODE==null?$row->COL_MODE:$row->COL_SUBMODE; ?></td>
          <td><?php echo $row->COL_RST_SENT; ?></td>
          <td><?php echo $row->COL_RST_RCVD; ?></td>
          <?php if($row->COL_SAT_NAME != null) { ?>
          <td><?php echo $row->COL_SAT_NAME; ?></td>
          <?php } else {
                    if ($this->session->userdata('user_column1')=='Frequency' || $this->session->userdata('user_column2')=='Frequency' || $this->session->userdata('user_column3')=='Frequency' || $this->session->userdata('user_column4')=='Frequency' || $this->session->userdata('user_column5')=='Frequency') {
                       echo '<td>';
                       if ($row->COL_FREQ != null) {
                          echo $this->frequency->hz_to_mhz($row->COL_FREQ);
                       } else {
                          echo $row->COL_BAND;
                       }
                       echo '</td>';
                    } else {
                       echo '<td>'.$row->COL_BAND.'</td>';
                    }
                } ?>
        </tr>
    <?php $i++; } } ?>
  </table>
</div>
</div>