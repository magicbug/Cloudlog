<div class="container">
    <h2><?php echo htmlspecialchars($plugin_award_title); ?></h2>
    <p class="text-muted">Provided by plugin: <?php echo htmlspecialchars($plugin_award_slug); ?></p>

    <div class="card">
        <div class="card-body">
            <?php echo $plugin_award_content; ?>
        </div>
    </div>
</div>
