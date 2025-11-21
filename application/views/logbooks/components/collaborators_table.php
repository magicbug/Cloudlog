<?php if (count($collaborators) > 0) { ?>
<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col"><i class="fas fa-user me-1"></i>User</th>
                <th scope="col"><i class="fas fa-envelope me-1"></i>Email</th>
                <th scope="col" class="text-center"><i class="fas fa-shield-alt me-1"></i>Permission</th>
                <th scope="col" class="text-center"><i class="fas fa-calendar me-1"></i>Added</th>
                <?php if ($is_owner) { ?>
                <th scope="col" class="text-center"><i class="fas fa-tools me-1"></i>Actions</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($collaborators as $user) { ?>
            <tr>
                <td class="align-middle">
                    <strong><?php echo htmlspecialchars($user->user_callsign); ?></strong>
                    <?php if ($user->permission_level == 'owner') { ?>
                        <span class="badge bg-primary ms-2">
                            <i class="fas fa-crown me-1"></i>Owner
                        </span>
                    <?php } ?>
                </td>
                <td class="align-middle">
                    <?php echo htmlspecialchars($user->user_email); ?>
                </td>
                <td class="align-middle text-center">
                    <?php if ($user->permission_level == 'owner') { ?>
                        <span class="badge bg-primary">Owner</span>
                    <?php } elseif ($user->permission_level == 'admin') { ?>
                        <span class="badge bg-danger">Admin</span>
                    <?php } elseif ($user->permission_level == 'write') { ?>
                        <span class="badge bg-warning">Write</span>
                    <?php } else { ?>
                        <span class="badge bg-secondary">Read</span>
                    <?php } ?>
                </td>
                <td class="align-middle text-center">
                    <?php if ($user->created_at && $user->created_at != '') { ?>
                        <?php echo date('Y-m-d', strtotime($user->created_at)); ?>
                    <?php } else { ?>
                        <span class="text-muted">-</span>
                    <?php } ?>
                </td>
                <?php if ($is_owner) { ?>
                <td class="align-middle text-center">
                    <?php if ($user->permission_level != 'owner') { ?>
                        <form hx-post="<?php echo site_url('logbooks/remove_user'); ?>" 
                              hx-target="#collaboratorsContainer" 
                              hx-swap="innerHTML"
                              hx-confirm="Are you sure you want to remove this user's access?"
                              style="display:inline;">
                            <input type="hidden" name="logbook_id" value="<?php echo $this->uri->segment(3); ?>">
                            <input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" title="Remove User">
                                <i class="fas fa-user-times"></i>
                            </button>
                        </form>
                    <?php } else { ?>
                        <span class="text-muted">-</span>
                    <?php } ?>
                </td>
                <?php } ?>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } else { ?>
<div class="text-center py-5">
    <i class="fas fa-users text-muted mb-3" style="font-size: 3rem;"></i>
    <h5 class="text-muted mb-3">No Collaborators</h5>
    <p class="text-muted">This logbook hasn't been shared with anyone yet.</p>
</div>
<?php } ?>
