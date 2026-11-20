<?php
$counties_fix_csrf_token = isset($counties_fix_csrf_token) ? (string)$counties_fix_csrf_token : '';
$logbook_id = isset($logbook_id) ? (int)$logbook_id : 0;
$csrf_name = $this->security->get_csrf_token_name();
$csrf_hash = $this->security->get_csrf_hash();
?>
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3 mb-3">
        <div>
            <h2 class="mb-1"><?php echo $page_title; ?></h2>
            <p class="mb-0 text-muted">Logged county names that do not match a USA-CA award county. Choose the correct county to rewrite every matching QSO you own in the active logbook.</p>
        </div>
        <div>
            <a class="btn btn-sm btn-outline-secondary" href="<?php echo site_url('awards/counties'); ?>">Back to US Counties</a>
        </div>
    </div>

    <?php
    $fix_success = $this->session->flashdata('counties_fix_success');
    $fix_error = $this->session->flashdata('counties_fix_error');
    if ($fix_success) { ?>
        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($fix_success); ?></div>
    <?php }
    if ($fix_error) { ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($fix_error); ?></div>
    <?php } ?>

    <?php if (empty($unknown)) { ?>
        <div class="alert alert-success" role="alert">No invalid county names found that you can fix in the active logbook.</div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover align-middle counties-fix-table">
                <thead>
                    <tr>
                        <th scope="col">State</th>
                        <th scope="col">Logged county</th>
                        <th scope="col">QSOs</th>
                        <th scope="col">Suggested</th>
                        <th scope="col">Correct county</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($unknown as $row) {
                        $state = $row['state'];
                        $county = $row['county'];
                        $suggested = !empty($row['suggestions']) ? $row['suggestions'][0] : '';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($state); ?></td>
                            <td>
                                <a href="#" class="county-contacts-link" data-state="<?php echo htmlspecialchars($state, ENT_QUOTES, 'UTF-8'); ?>" data-county="<?php echo htmlspecialchars($county, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($county); ?>
                                </a>
                            </td>
                            <td><?php echo (int)$row['qsos']; ?></td>
                            <td>
                                <?php if (!empty($row['suggestions'])) {
                                    echo htmlspecialchars(implode(', ', $row['suggestions']));
                                } else {
                                    echo '<span class="text-muted">-</span>';
                                } ?>
                            </td>
                            <td>
                                <form method="post" action="<?php echo site_url('awards/counties_fix_apply'); ?>" class="counties-fix-form d-flex flex-wrap gap-2 align-items-center mb-0">
                                    <input type="hidden" name="counties_fix_csrf_token" value="<?php echo htmlspecialchars($counties_fix_csrf_token); ?>">
                                    <input type="hidden" name="<?php echo htmlspecialchars($csrf_name); ?>" value="<?php echo htmlspecialchars($csrf_hash); ?>">
                                    <input type="hidden" name="logbook_id" value="<?php echo $logbook_id; ?>">
                                    <input type="hidden" name="state" value="<?php echo htmlspecialchars($state); ?>">
                                    <input type="hidden" name="old_county" value="<?php echo htmlspecialchars($county); ?>">
                                    <select name="new_county" class="form-select form-select-sm" required style="min-width: 12rem;">
                                        <option value="">Select county…</option>
                                        <?php foreach ($row['counties'] as $name) { ?>
                                            <option value="<?php echo htmlspecialchars($name); ?>"<?php echo strcasecmp($name, $suggested) === 0 ? ' selected' : ''; ?>>
                                                <?php echo htmlspecialchars($name); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary counties-fix-apply">Apply to <?php echo (int)$row['qsos']; ?> QSO(s)</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <p class="text-muted small">Updates set COL_CNTY to STATE,County and COL_STATE to the state abbreviation. Only QSOs at station locations you own are listed and changed.</p>
    <?php } ?>
</div>
