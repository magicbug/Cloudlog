<div class="container logbook-sharing">

<br>

<!-- Page Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2><i class="fas fa-users me-2"></i>Manage Logbook Sharing</h2>
                <p class="text-muted mb-0">Share "<?php echo $logbook->logbook_name; ?>" with other users</p>
            </div>
            <a href="<?php echo site_url('logbooks'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Logbooks
            </a>
        </div>
    </div>
</div>

<!-- Information Card -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>About Sharing</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>Permission Levels:</strong></p>
                <ul class="mb-0">
                    <li><strong>Read:</strong> View logbook data and QSOs only</li>
                    <li><strong>Write:</strong> Add, edit, and delete QSOs, manage station locations</li>
                    <li><strong>Admin:</strong> All write permissions plus the ability to manage other users' access</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Add User Form -->
<?php if ($is_owner) { ?>
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-user-plus me-2"></i>Add User</h5>
            </div>
            <div class="card-body">
                <form id="addUserForm" hx-post="<?php echo site_url('logbooks/add_user'); ?>" 
                      hx-target="#collaboratorsContainer" 
                      hx-swap="innerHTML">
                    <input type="hidden" name="logbook_id" value="<?php echo $logbook->logbook_id; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="user_identifier" class="form-label">Email or Callsign</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="user_identifier" 
                                   name="user_identifier" 
                                   placeholder="Enter user's email or callsign"
                                   hx-post="<?php echo site_url('logbooks/validate_user'); ?>"
                                   hx-trigger="keyup changed delay:500ms"
                                   hx-target="#userValidationFeedback"
                                   hx-include="[name='user_identifier']"
                                   required>
                            <div id="userValidationFeedback" class="mt-1"></div>
                            <small class="text-muted">User must have an account in Cloudlog</small>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="permission_level" class="form-label">Permission Level</label>
                            <select class="form-select" id="permission_level" name="permission_level" required>
                                <option value="read">Read Only</option>
                                <option value="write" selected>Write</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-user-plus me-2"></i>Add User
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<!-- Current Access List -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Current Access (<?php echo count($collaborators); ?>)</h5>
            </div>
            <div class="card-body p-0">
                <div id="collaboratorsContainer">
                    <?php $this->load->view('logbooks/components/collaborators_table', array('collaborators' => $collaborators, 'is_owner' => $is_owner)); ?>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
