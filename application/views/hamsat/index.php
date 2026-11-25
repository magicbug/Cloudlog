<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => 'Hamsat - Satellite Rovers',
        'subtitle' => 'Live satellite rover activation feed from hams.at (refreshes every 60s)',
        'icon' => 'fas fa-satellite',
        'actions' => array(
            array(
                'label' => 'Open hams.at',
                'url' => 'https://hams.at/',
                'icon' => 'fas fa-external-link-alt',
            ),
        ),
    )); ?>
    <div id="hamsat_display" hx-get="<?php echo site_url('components'); ?>" hx-trigger="load, every 60s"></div>
</div>
