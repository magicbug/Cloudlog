<div class="container">
    <h2><?php echo $page_title; ?></h2>

    <?php if ($sig_all) { ?>

    <table class="table table-sm table-striped table-hover">

        <tr>
            <td>Reference</td>
            <td>Date/Time</td>
            <td>Callsign</td>
            <td>Mode</td>
            <td>Band</td>
            <td>RST Sent</td>
            <td>RST Received</td>
        </tr>
        <?php foreach ($sig_all->result() as $row) { ?>
            <tr>
                <td>
                    <?php echo $row->COL_SIG_INFO; ?>
                </td>
                <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date('d/m/y', $timestamp); ?> - <?php $timestamp = strtotime($row->COL_TIME_ON); echo date('H:i', $timestamp); ?></td>
                <td><?php echo $row->COL_CALL; ?></td>
                <td><?php echo $row->COL_MODE; ?></td>
                <td><?php echo $row->COL_BAND; ?></td>
                <td><?php echo $row->COL_RST_SENT; ?></td>
                <td><?php echo $row->COL_RST_RCVD; ?></td>
            </tr>
                <?php } ?>

    </table>
    <?php } ?>
    <p><a href="<?php echo site_url('/awards/sigexportadif/' . $type); ?>" title="Export QSOs to ADIF" target="_blank" class="btn btn-primary">Export QSOs to ADIF</a></p>
</div>
