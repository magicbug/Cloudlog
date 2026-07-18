<?php
$page_title = $page_title ?? '';
$editing_mapping = $editing_mapping ?? null;
$mappings = $mappings ?? array();
?>
<div class="container eqsl">
<h2><?php echo $page_title; ?></h2>
<div class="card">
  <div class="card-header">
    <div class="card-title">eQSL Mappings</div>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/export');?>">Upload QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/mappings');?>">Mappings</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/download');?>">Download eQSL cards</a>
      </li>
    </ul>
  </div>

  <div class="card-body">
    <?php $this->load->view('layout/messages'); ?>

    <div class="alert alert-info" role="alert">
      Add one mapping per station and eQSL identity. Cloudlog uses mappings first for manual import/export and cron sync. Legacy user-level credentials are only used when no active mappings exist.
    </div>

    <h5><?php echo $editing_mapping ? 'Edit Mapping' : 'Add Mapping'; ?></h5>
    <?php echo form_open('eqsl/save_mapping'); ?>
      <input type="hidden" name="mapping_id" value="<?php echo $editing_mapping ? (int) $editing_mapping['mapping_id'] : 0; ?>" />
      <div class="row">
        <div class="mb-3 col-md-4">
          <label class="form-label" for="mapping_station_id">Station location</label>
          <select class="form-select" name="station_id" id="mapping_station_id" required>
            <option value="">Select station location</option>
            <?php if (isset($station_profile)) { foreach ($station_profile->result() as $station) { ?>
              <?php $selected = ($editing_mapping && (int) $editing_mapping['station_id'] === (int) $station->station_id) ? 'selected="selected"' : ''; ?>
              <option value="<?php echo (int) $station->station_id; ?>" <?php echo $selected; ?>>
                <?php echo $station->station_callsign; ?> (<?php echo $station->station_profile_name; ?>)
              </option>
            <?php } } ?>
          </select>
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label" for="mapping_eqsl_username">eQSL username</label>
          <input class="form-control" type="text" name="eqsl_username" id="mapping_eqsl_username" value="<?php echo $editing_mapping ? $editing_mapping['eqsl_username'] : ''; ?>" required />
        </div>
        <div class="mb-3 col-md-4">
          <label class="form-label" for="mapping_eqsl_password">eQSL password</label>
          <input class="form-control" type="password" name="eqsl_password" id="mapping_eqsl_password" value="<?php echo $editing_mapping ? $editing_mapping['eqsl_password'] : ''; ?>" />
          <small class="form-text text-muted">Leave blank to reuse an existing saved password for this eQSL username.</small>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-md-4">
          <label class="form-label" for="mapping_eqsl_qth_nickname">eQSL QTH nickname</label>
          <input class="form-control" type="text" name="eqsl_qth_nickname" id="mapping_eqsl_qth_nickname" value="<?php echo $editing_mapping ? $editing_mapping['eqsl_qth_nickname'] : ''; ?>" required />
        </div>
        <div class="mb-3 col-md-2 form-check mt-4 pt-2">
          <?php $enabled = $editing_mapping ? ((int) $editing_mapping['enabled'] === 1) : true; ?>
          <input class="form-check-input" type="checkbox" name="enabled" id="mapping_enabled" value="1" <?php echo $enabled ? 'checked="checked"' : ''; ?> />
          <label class="form-check-label" for="mapping_enabled">Enabled</label>
        </div>
        <div class="mb-3 col-md-3 form-check mt-4 pt-2">
          <?php $preferred = $editing_mapping ? ((int) $editing_mapping['preferred_for_download'] === 1) : true; ?>
          <input class="form-check-input" type="checkbox" name="preferred_for_download" id="mapping_preferred_for_download" value="1" <?php echo $preferred ? 'checked="checked"' : ''; ?> />
          <label class="form-check-label" for="mapping_preferred_for_download">Use for inbox download</label>
        </div>
      </div>
      <div class="mb-3">
        <button class="btn btn-primary" type="submit"><?php echo $editing_mapping ? 'Update Mapping' : 'Add Mapping'; ?></button>
        <?php if ($editing_mapping) { ?>
          <a class="btn btn-secondary" href="<?php echo site_url('eqsl/mappings'); ?>">Cancel</a>
        <?php } ?>
      </div>
    </form>

    <hr />

    <h5>Configured Mappings</h5>
    <?php if (!empty($mappings)) { ?>
      <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped text-center">
          <thead>
            <tr>
              <th>Station</th>
              <th>QTH Nickname</th>
              <th>eQSL Username</th>
              <th>Enabled</th>
              <th>Download</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($mappings as $mapping) { ?>
              <tr>
                <td><?php echo $mapping['station_callsign']; ?> (<?php echo $mapping['station_profile_name']; ?>)</td>
                <td><?php echo $mapping['eqsl_qth_nickname']; ?></td>
                <td><?php echo $mapping['eqsl_username']; ?></td>
                <td><?php echo ((int) $mapping['enabled'] === 1) ? 'Yes' : 'No'; ?></td>
                <td><?php echo ((int) $mapping['preferred_for_download'] === 1) ? 'Yes' : 'No'; ?></td>
                <td>
                  <a class="btn btn-sm btn-primary" href="<?php echo site_url('eqsl/mappings?edit=' . (int) $mapping['mapping_id']); ?>">Edit</a>
                  <?php echo form_open('eqsl/delete_mapping', array('style' => 'display:inline-block;', 'onsubmit' => "return confirm('Delete this mapping?');")); ?>
                    <input type="hidden" name="mapping_id" value="<?php echo (int) $mapping['mapping_id']; ?>" />
                    <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    <?php } else { ?>
      <p>No eQSL mappings configured yet.</p>
    <?php } ?>
  </div>
</div>
</div>
