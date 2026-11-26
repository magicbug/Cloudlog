<div class="container awards-page plugin-award-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => htmlspecialchars($plugin_award_title, ENT_QUOTES, 'UTF-8'),
        'subtitle' => 'Provided by plugin: ' . htmlspecialchars($plugin_award_slug, ENT_QUOTES, 'UTF-8'),
        'icon' => 'fas fa-puzzle-piece',
    )); ?>

    <div class="card">
        <div class="card-body">
            <?php echo $plugin_award_content; ?>
        </div>
    </div>
</div>
