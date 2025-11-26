<div class="alert alert-info mb-0">
  <strong>Import Progress</strong>
  <ul class="mb-2">
    <li>Stations imported: <?php echo $stations; ?> / <?php echo $total_stations; ?></li>
    <li>Logbooks imported: <?php echo $logbooks; ?> / <?php echo $total_logbooks; ?></li>
    <li>QSOs imported: <?php echo $qsos; ?> / <?php echo $total_qsos; ?></li>
  </ul>
  <?php if (!empty($step)): ?>
    <div>Current step: <?php echo htmlspecialchars($step); ?></div>
  <?php endif; ?>
  <?php if (!empty($conflicts)): ?>
    <div class="mt-2">
      <strong>Conflicts / Notices:</strong>
      <ul class="mb-0">
        <?php foreach ($conflicts as $conflict): ?>
          <li><?php echo htmlspecialchars($conflict); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>