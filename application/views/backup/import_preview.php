<div class="mt-3">
  <h5>Import Preview</h5>
  <form method="post" hx-post="<?php echo site_url('backup/user_do_import'); ?>" hx-target="#user-import-progress" hx-swap="innerHTML">
    <div class="row">
      <div class="col-md-6">
        <h6>Stations</h6>
        <ul class="list-unstyled">
          <?php foreach ($stations as $station): ?>
            <li>
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="import_stations[]" value="<?php echo $station['station_id']; ?>" checked>
                <?php echo htmlspecialchars($station['station_callsign'].' - '.$station['station_profile_name']); ?> (<?php echo $station['qsos_count']; ?> QSOs)
              </label>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="col-md-6">
        <h6>Logbooks</h6>
        <ul class="list-unstyled">
          <?php foreach ($logbooks as $logbook): ?>
            <li>
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="import_logbooks[]" value="<?php echo $logbook['logbook_id']; ?>" checked>
                <?php echo htmlspecialchars($logbook['logbook_name']); ?> (<?php echo $logbook['qsos_count']; ?> QSOs)
              </label>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-2">Import Selected</button>
  </form>
  <div id="user-import-progress" class="mt-3">
    <div id="user-import-spinner" style="display:none;">
      <div class="d-flex align-items-center">
        <strong>Importing, please wait...</strong>
        <div class="spinner-border ms-3" role="status" aria-hidden="true"></div>
      </div>
    </div>
  </div>
  <script>
    (function(){
      var f = document.querySelector('#user-import-preview form');
      if(f){ f.addEventListener('submit', function(){ document.getElementById('user-import-spinner').style.display='block'; }); }
    })();
  </script>
</div>