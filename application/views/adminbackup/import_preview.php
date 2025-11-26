<div class="container mt-4">
  <h2>Import Preview</h2>
  <form method="post" hx-post="<?php echo site_url('adminbackup/do_import'); ?>" hx-target="#import-progress" hx-swap="innerHTML">
    <h4>Stations</h4>
    <ul>
      <?php foreach ($stations as $station): ?>
        <li>
          <label>
            <input type="checkbox" name="import_stations[]" value="<?php echo $station['station_id']; ?>" checked>
            <?php echo htmlspecialchars($station['station_callsign'] . ' - ' . $station['station_profile_name']); ?>
            <?php if (isset($station['qsos_count'])): ?>
              (<?php echo $station['qsos_count']; ?> QSOs)
            <?php endif; ?>
          </label>
        </li>
      <?php endforeach; ?>
    </ul>
    <h4>Logbooks</h4>
    <ul>
      <?php foreach ($logbooks as $logbook): ?>
        <li>
          <label>
            <input type="checkbox" name="import_logbooks[]" value="<?php echo $logbook['logbook_id']; ?>" checked>
            <?php echo htmlspecialchars($logbook['logbook_name']); ?> (<?php echo $logbook['qsos_count']; ?> QSOs)
          </label>
        </li>
      <?php endforeach; ?>
    </ul>
    <button type="submit" class="btn btn-success">Import Selected</button>
  </form>
  <div id="import-progress" class="mt-4">
    <div id="import-spinner" style="display:none;">
      <div class="d-flex align-items-center">
        <strong>Importing, please wait...</strong>
        <div class="spinner-border ms-3" role="status" aria-hidden="true"></div>
      </div>
    </div>
  </div>
  <script>
    document.querySelector('form').addEventListener('submit', function() {
      document.getElementById('import-spinner').style.display = 'block';
    });
  </script>
</div>
