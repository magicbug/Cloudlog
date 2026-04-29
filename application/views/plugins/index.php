<div class="container">
    <h2>Plugin Manager</h2>
    <p class="text-muted">Upload a plugin zip package, install it, and control whether it is enabled.</p>

    <div class="card mb-3">
        <div class="card-header">
            <strong>Upload Plugin</strong>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo site_url('plugins/upload'); ?>" enctype="multipart/form-data" class="row g-3 align-items-end">
                <input type="hidden" name="plugins_csrf_token" value="<?php echo htmlspecialchars($plugins_csrf_token); ?>">
                <div class="col-md-8">
                    <label for="plugin_zip" class="form-label">Plugin Zip File</label>
                    <input type="file" class="form-control" id="plugin_zip" name="plugin_zip" accept=".zip" required>
                    <div class="form-text">Plugin packages must include a manifest.json at the plugin root.</div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> Upload and Install</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Installed Plugins</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Files</th>
                        <th>Installed</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($plugins)) { ?>
                        <?php foreach ($plugins as $plugin) { ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($plugin->plugin_name); ?></strong>
                                    <?php if (!empty($plugin->plugin_description)) { ?>
                                        <div class="text-muted small"><?php echo htmlspecialchars($plugin->plugin_description); ?></div>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars($plugin->plugin_slug); ?></td>
                                <td><?php echo htmlspecialchars($plugin->plugin_version); ?></td>
                                <td>
                                    <?php if ($plugin->plugin_status === 'enabled') { ?>
                                        <span class="badge text-bg-success">Enabled</span>
                                    <?php } else { ?>
                                        <span class="badge text-bg-secondary">Disabled</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php if ($plugin->path_exists) { ?>
                                        <span class="badge text-bg-success">Present</span>
                                    <?php } else { ?>
                                        <span class="badge text-bg-danger">Missing</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars($plugin->installed_at); ?></td>
                                <td>
                                    <?php if ($plugin->plugin_status === 'enabled') { ?>
                                        <form method="post" action="<?php echo site_url('plugins/disable'); ?>" class="d-inline">
                                            <input type="hidden" name="plugin_slug" value="<?php echo htmlspecialchars($plugin->plugin_slug); ?>">
                                            <input type="hidden" name="plugins_csrf_token" value="<?php echo htmlspecialchars($plugins_csrf_token); ?>">
                                            <button type="submit" class="btn btn-sm btn-warning">Disable</button>
                                        </form>
                                    <?php } else { ?>
                                        <form method="post" action="<?php echo site_url('plugins/enable'); ?>" class="d-inline">
                                            <input type="hidden" name="plugin_slug" value="<?php echo htmlspecialchars($plugin->plugin_slug); ?>">
                                            <input type="hidden" name="plugins_csrf_token" value="<?php echo htmlspecialchars($plugins_csrf_token); ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">Enable</button>
                                        </form>
                                    <?php } ?>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-danger ms-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deletePluginModal"
                                        data-plugin-slug="<?php echo htmlspecialchars($plugin->plugin_slug); ?>"
                                        data-plugin-name="<?php echo htmlspecialchars($plugin->plugin_name); ?>"
                                    >Delete</button>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7" class="text-center p-3 text-muted">No plugins installed yet.</td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deletePluginModal" tabindex="-1" aria-labelledby="deletePluginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePluginModalLabel">Delete Plugin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Are you sure you want to delete this plugin?</p>
                <p class="mb-0"><strong id="deletePluginName"></strong></p>
                <p class="text-muted mb-0">This removes plugin files and metadata.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="post" action="<?php echo site_url('plugins/delete'); ?>" class="d-inline">
                    <input type="hidden" name="plugin_slug" id="deletePluginSlug" value="">
                    <input type="hidden" name="plugins_csrf_token" value="<?php echo htmlspecialchars($plugins_csrf_token); ?>">
                    <button type="submit" class="btn btn-danger">Delete Plugin</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteModal = document.getElementById('deletePluginModal');
    if (!deleteModal) {
        return;
    }

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const triggerButton = event.relatedTarget;
        if (!triggerButton) {
            return;
        }

        const pluginSlug = triggerButton.getAttribute('data-plugin-slug') || '';
        const pluginName = triggerButton.getAttribute('data-plugin-name') || pluginSlug;

        const slugInput = deleteModal.querySelector('#deletePluginSlug');
        const nameLabel = deleteModal.querySelector('#deletePluginName');

        if (slugInput) {
            slugInput.value = pluginSlug;
        }

        if (nameLabel) {
            nameLabel.textContent = pluginName + ' (' + pluginSlug + ')';
        }
    });
});
</script>
