<?php if($radio_status->num_rows()) { ?>

<table class="table table-striped">
        <tr class="titles">
            <td colspan="2"><i class="fas fa-broadcast-tower"></i> Radio Status</td>
        </tr>

        <?php foreach ($radio_status->result_array() as $row) { ?>
        <tr>
            <td><?php echo $row['radio']; ?></td>
            <td>
                <?php if($row['prop_mode'] == 'SAT') { ?>
                    <?php echo $row['sat_name']; ?>
                <?php } else { ?>
                    <?php echo $this->frequency->hz_to_mhz($row['frequency']); ?> (<?php echo $row['mode']; ?>)
                <?php } ?>
            </td>
        </tr>
        <?php } ?>

    </table>

<?php } ?>