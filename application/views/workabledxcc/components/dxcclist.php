<table class="table table-striped table-hover">

    <tr>
        <td>Start Date</td>
        <td>End Date</td>
        <td>Country</td>
        <td>Callsign</td>
        <td></td>
        <td>Notes</td>
    </tr>



    <?php foreach($dxcclist as $dxcc): ?>
    <tr>
        <td><?php echo $dxcc['start_date']; ?></td>
        <td><?php echo $dxcc['end_date']; ?></td>
        <td><?php echo $dxcc['country']; ?></td>
        <td>
            <?php echo $dxcc['callsign']; ?>

        </td>
        <td>

        <?php if (!$dxcc['workedBefore']) { ?>
            <span class="badge bg-danger">Not Worked Before</span>
<?php } else { ?>
    <span class="badge bg-success">Worked Before</span>
<?php } ?>

        <?php if ($dxcc['confirmed']): ?>
        <span class="badge bg-primary">Confirmed</span>
        <?php endif; ?>
    </td>
        <td><?php echo $dxcc['notes']; ?></td>
    </tr>
    <?php endforeach; ?>

</table>