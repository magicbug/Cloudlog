<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="table-responsive">
    <table class="table table-striped table-hover border-top">

        <thead>
            <tr class="titles">
                <th><?php echo lang('general_word_date'); ?></th>

                <?php if (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                    <th><?php echo lang('general_word_time'); ?></th>
                <?php } ?>
                <th><?php echo lang('gen_hamradio_call'); ?></th>
                <?php
                echo_table_header_col($this, $this->session->userdata('user_column1') == "" ? 'Mode' : $this->session->userdata('user_column1'));
                echo_table_header_col($this, $this->session->userdata('user_column2') == "" ? 'RSTS' : $this->session->userdata('user_column2'));
                echo_table_header_col($this, $this->session->userdata('user_column3') == "" ? 'RSTR' : $this->session->userdata('user_column3'));
                echo_table_header_col($this, $this->session->userdata('user_column4') == "" ? 'Band' : $this->session->userdata('user_column4'));
                ?>
            </tr>
        </thead>

        <?php
        $i = 0;
        if (!empty($last_five_qsos) > 0) {
            foreach ($last_five_qsos->result() as $row) { ?>
                <?php echo '<tr id="qso_' . $row->COL_PRIMARY_KEY . '" class="tr' . ($i & 1) . '">'; ?>

                <?php

                // Get Date format
                if ($this->session->userdata('user_date_format')) {
                    // If Logged in and session exists
                    $custom_date_format = $this->session->userdata('user_date_format');
                } else {
                    // Get Default date format from /config/cloudlog.php
                    $custom_date_format = $this->config->item('qso_date_format');
                }

                ?>

                <td><?php $timestamp = strtotime($row->COL_TIME_ON);
                    echo date($custom_date_format, $timestamp); ?></td>
                <?php if (($this->config->item('use_auth') && ($this->session->userdata('user_type') >= 2)) || $this->config->item('use_auth') === FALSE || ($this->config->item('show_time'))) { ?>
                    <td><?php $timestamp = strtotime($row->COL_TIME_ON);
                        echo date('H:i', $timestamp); ?></td>

                <?php } ?>
                <td>
                    <a id="edit_qso" href="javascript:displayQso(<?php echo $row->COL_PRIMARY_KEY; ?>)"><?php echo str_replace("0", "&Oslash;", strtoupper($row->COL_CALL)); ?></a>
                </td>
                <?php
                echo_table_col($row, $this->session->userdata('user_column1') == "" ? 'Mode' : $this->session->userdata('user_column1'));
                echo_table_col($row, $this->session->userdata('user_column2') == "" ? 'RSTS' : $this->session->userdata('user_column2'));
                echo_table_col($row, $this->session->userdata('user_column3') == "" ? 'RSTR' : $this->session->userdata('user_column3'));
                echo_table_col($row, $this->session->userdata('user_column4') == "" ? 'Band' : $this->session->userdata('user_column4'));
                ?>
                </tr>
        <?php $i++;
            }
        } ?>
    </table>