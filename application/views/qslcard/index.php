<div class="container">

    <br>

    <h2><?php echo $this->lang->line('general_word_qslcards'); ?></h2>

    <div class="alert alert-info" role="alert">
    <?php echo $this->lang->line('qslcard_string_your_are_using'); ?> <?php echo $storage_used; ?> <?php echo $this->lang->line('qslcard_string_disk_space'); ?>
    </div>

    <?php
    if (is_array($qslarray->result())) {
        echo '<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">
        <thead>
        <tr>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_callsign').'</th>
        <th style=\'text-align: center\'>'.$this->lang->line('gen_hamradio_qsl').'</th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        <th style=\'text-align: center\'></th>
        </tr>
        </thead><tbody>';

        foreach ($qslarray->result() as $qsl) {
            echo '<tr>';
            echo '<td style=\'text-align: center\'>' . $qsl->COL_CALL . '</td>';
            echo '<td style=\'text-align: center\'>' . $qsl->filename . '</td>';
            echo '<td id="'.$qsl->id.'" style=\'text-align: center\'><button onclick="deleteQsl(\''.$qsl->id.'\')" class="btn btn-sm btn-danger">Delete</button></td>';
            echo '<td style=\'text-align: center\'><button onclick="viewQsl(\''.$qsl->filename.'\', \''. $qsl->COL_CALL . '\')" class="btn btn-sm btn-success">View</button></td>';
			echo '<td style=\'text-align: center\'><button onclick="addQsosToQsl(\''.$qsl->filename.'\')" class="btn btn-sm btn-success">Add Qsos</button></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }
    ?>

</div>
