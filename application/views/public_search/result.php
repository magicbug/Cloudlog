<div class="container publicsearch">
<h1>Results <small class="text-muted">Searching for <?php echo str_replace("0","&Oslash;",strtoupper($callsign)); ?></small></h1>
<div class="card text-center">
<div class="card-body">
<?php

if ($results) { ?>

<div class="table-responsive">
    <table style="width:100%" id="publicsearchtable" class="publicsearchtable table table-sm table-striped table-hover">
        <thead>
            <tr class="titles">
                <th><?php echo lang('general_word_date'); ?></th>
                <th><?php echo lang('gen_hamradio_call'); ?></th>
                <th><?php echo lang('gen_hamradio_mode'); ?></th>
                <th><?php echo lang('gen_hamradio_band'); ?></th>
                <th>Station Callsign</th>
            </tr>
        </thead>
        <tbody>

        <?php  $i = 0;
            foreach ($results->result() as $row) {
                echo '<tr class="tr'.($i & 1).'">'; ?>
            <td><?php $timestamp = strtotime($row->COL_TIME_ON); echo date($this->config->item('qso_date_format'), $timestamp); ?></td>
            <td>
                <?php echo str_replace("0","&Oslash;",strtoupper($row->COL_CALL)); ?>
            </td>
            <td>
                <?php echo $row->COL_SUBMODE==null ? $row->COL_MODE : $row->COL_SUBMODE; ?>
            </td>
            <td>
                <?php if($row->COL_SAT_NAME != null) { echo $row->COL_SAT_NAME; } else { echo strtolower($row->COL_BAND); } ?>
            </td>
            <td>
                <?php echo $row->station_callsign; ?>
            </td>
            </tr>
            <?php $i++; } ?>
        </tbody>
    </table></div>
    <?php } ?>

    <?php if (isset($this->pagination)){ ?>
        <?php
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = false;
        $config['last_link'] = false;
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        ?>

        <?php echo $this->pagination->create_links(); ?>

    <?php } ?>

</div>
</div>
</div>
</div>
</div>
