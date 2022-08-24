
<div class="container eqsl">
<div class="card">
  <div class="card-header">
    <h5 class="card-title"><?php echo $page_title; ?></h5>
    <ul class="nav nav-tabs card-header-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="<?php echo site_url('eqsl/import');?>">Download QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/Export');?>">Upload QSOs</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo site_url('eqsl/tools');?>">Tools</a>
      </li>
    </ul>
  </div>

	<div class="card-body">
		<?php $this->load->view('layout/messages'); ?>

		<?php foreach ($eqsl_results as $import) { ?>
			<h3><?php echo $import['name']; ?></h3>
			<div class="alert alert-info" role="alert">
				<?php echo $import['status']; ?>
			</div>
			<?php if (count($import['qsos']) > 0) { ?>
				<table>
					<tr class="titles">
						<td>Date</td>
						<td>Call</td>
						<td>Mode</td>
						<td>Submode</td>
						<td>Log Status</td>
						<td>eQSL Status</td>
					</tr>
					<?php foreach ($import['qsos'] as $qso) { ?>
						<tr>
							<td><?php echo $qso['date']; ?></td>
							<td><?php echo $qso['call']; ?></td>
							<td><?php echo $qso['mode']; ?></td>
							<td><?php echo $qso['submode']; ?></td>
							<td><?php echo $qso['status']; ?></td>
							<td><?php echo $qso['eqsl_status']; ?></td>
						</tr>
					<?php } ?>
				</table>
			<?php } else { ?>
				<p>There are no QSO confirmations waiting for you at eQSL.cc</p>
			<?php } ?>
		<?php } ?>
	</div>
</div>

</div>
