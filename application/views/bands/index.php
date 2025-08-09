<?php
$cq = 0;
$dok = 0;
$dxcc = 0;
$iota = 0;
$pota = 0;
$sig = 0;
$sota = 0;
$uscounties = 0;
$vucc = 0;
$was = 0;
$wwff = 0;
?>
<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo lang('options_bands'); ?></h2>

<!-- Info Card -->
<div class="card mb-3">
	<div class="card-header">
		<i class="fas fa-info-circle"></i> <?php echo lang('options_bands'); ?> Information
	</div>
	<div class="card-body">
		<div class="alert alert-primary alert-dismissible fade show" role="alert">
			<strong><i class="fas fa-lightbulb"></i> Quick Guide:</strong> 
			The <strong>first column</strong> controls if a band appears in QSO entry. The <strong>award columns</strong> (CQ, DOK, DXCC, etc.) control which awards are tracked for that band. Use the <strong>master checkboxes</strong> at the bottom to toggle all bands for a specific award.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		
		<p class="card-text mb-2">
			<?php echo lang('options_bands_text_ln1'); ?>
		</p>
		<p class="card-text mb-0">
			<?php echo lang('options_bands_text_ln2'); ?>
		</p>
	</div>
</div>

<!-- Controls Card -->
<div class="card mb-3">
	<div class="card-header">
		<i class="fas fa-sliders-h"></i> Band Management Controls
	</div>
	<div class="card-body">
		<!-- Statistics -->
		<div class="row mb-3">
			<div class="col-md-8">
				<div class="alert alert-info py-2 mb-0">
					<div class="row text-center">
						<div class="col-md-6 col-6">
							<strong id="activeBandsCount">0</strong><br>
							<small>Active for QSO Entry</small>
						</div>
						<div class="col-md-6 col-6">
							<strong><?php echo count($bands); ?></strong><br>
							<small>Total Bands Configured</small>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="btn-group btn-group-sm w-100" role="group">
					<button type="button" class="btn btn-outline-success" id="enableAllAwards" title="Enable all award tracking for all bands">
						<i class="fas fa-check-double"></i> Enable All Awards
					</button>
					<button type="button" class="btn btn-outline-warning" id="resetAllAwards" title="Disable all award tracking">
						<i class="fas fa-times"></i> Reset Awards
					</button>
				</div>
			</div>
		</div>
		
		<!-- Search and Filter -->
		<div class="row">
			<div class="col-md-8">
				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="fas fa-search"></i></span>
					</div>
					<input type="text" id="bandSearch" class="form-control" placeholder="Search bands, frequencies, or band groups... (Press / to focus)">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="button" id="clearSearch" title="Clear search">
							<i class="fas fa-times"></i>
						</button>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="btn-group btn-group-sm w-100" role="group">
					<button type="button" class="btn btn-outline-secondary" id="showActiveOnly">
						<i class="fas fa-eye"></i> Active Only
					</button>
					<button type="button" class="btn btn-outline-secondary" id="showAll">
						<i class="fas fa-list"></i> Show All
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Bands Table Card -->
<div class="card">
<div class="card-header d-flex justify-content-between align-items-center">
	<span><i class="fas fa-table"></i> Bands Configuration</span>
	<span class="badge badge-secondary" id="visibleRowsCount">Loading...</span>
</div>
  <div class="card-body">
	
    <div class="table-responsive">
		<style>
		.card {
			box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
			border: 1px solid rgba(0,0,0,.125);
		}
		.card-header {
			background-color: #f8f9fa;
			border-bottom: 1px solid rgba(0,0,0,.125);
			font-weight: 500;
		}
		.card-header i {
			color: #6c757d;
			margin-right: 0.5rem;
		}
		.bandtable th, .bandtable td {
			text-align: center;
			vertical-align: middle;
			white-space: nowrap;
			padding: 8px 4px;
		}
		.bandtable th {
			background-color: #f8f9fa;
			border-top: 2px solid #dee2e6;
		}
		.bandtable tfoot th {
			background-color: #e9ecef;
			border-top: 2px solid #adb5bd;
		}
		.bandtable th:first-child,
		.bandtable td:first-child {
			position: sticky;
			left: 0;
			background-color: #fff;
			z-index: 2;
			box-shadow: 2px 0 2px rgba(0,0,0,0.1);
		}
		.bandtable th:nth-child(2),
		.bandtable td:nth-child(2) {
			position: sticky;
			left: 40px;
			background-color: #fff;
			z-index: 2;
			box-shadow: 2px 0 2px rgba(0,0,0,0.1);
			font-weight: bold;
		}
		.bandtable thead th:first-child,
		.bandtable thead th:nth-child(2) {
			z-index: 3;
			background-color: #f8f9fa;
		}
		.bandtable input[type="checkbox"] {
			transform: scale(1.2);
			cursor: pointer;
		}
		.band-checkbox-cell {
			background-color: #f8f9fa;
		}
		.frequency-cell {
			font-family: monospace;
			font-size: 0.9em;
		}
		.saving {
			background-color: #fff3cd !important;
			transition: background-color 0.3s ease;
		}
		.saved {
			background-color: #d4edda !important;
			transition: background-color 0.3s ease;
		}
		.updating {
			background-color: #e2e3e5 !important;
			transition: background-color 0.2s ease;
		}
		.updated {
			background-color: #d1ecf1 !important;
			transition: background-color 0.3s ease;
		}
		.band-actions {
			white-space: nowrap;
		}
		.band-actions .btn {
			margin: 0 2px;
		}
		@media (max-width: 768px) {
			.bandtable th, .bandtable td {
				padding: 4px 2px;
				font-size: 0.85em;
			}
		}
		</style>
		
    <table style="width:100%" class="bandtable table table-sm table-striped">
			<thead>
				<tr>
					<th title="Enable/disable band for QSO entry">Active</th>
					<th><?php echo lang('gen_hamradio_band'); ?></th>
					<th title="CQ Magazine DX Awards">CQ</th>
                    <th title="Diplom Ortsverbände Kennung">DOK</th>
                    <th title="DX Century Club">DXCC</th>
                    <th title="Islands On The Air">IOTA</th>
					<th title="Parks on the Air">POTA</th>
					<th title="Special Interest Group">SIG</th>
                    <th title="Summits on the Air">SOTA</th>
                    <th title="US Counties">Counties</th>
                    <th title="VHF/UHF Century Club">VUCC</th>
                    <th title="Worked All States">WAS</th>
					<th title="World Wide Flora and Fauna">WWFF</th>
					<th title="Band group classification">Group</th>
					<th title="SSB Operating Frequency">SSB</th> 
					<th title="Data Operating Frequency">Data</th>
					<th title="CW Operating Frequency">CW</th>
					<?php if($this->session->userdata('user_type') == '99') { ?>
                    <th></th>
                    <th></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($bands as $band) { ?>
				<tr>
                    <td class='band_<?php echo $band->id ?> band-checkbox-cell'><input type="checkbox" <?php if ($band->active == 1) {echo 'checked';}?>></td>
					<td class="band-name"><?php echo $band->band;?></td>
					<td class='cq_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->cq == 1) {echo 'checked'; $cq++;}?>></td>
                    <td class='dok_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->dok == 1) {echo 'checked'; $dok++;}?>></td>
                    <td class='dxcc_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->dxcc == 1) {echo 'checked'; $dxcc++;}?>></td>
                    <td class='iota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->iota == 1) {echo 'checked'; $iota++;}?>></td>
                    <td class='pota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->pota == 1) {echo 'checked'; $pota++;}?>></td>
                    <td class='sig_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->sig == 1) {echo 'checked'; $sig++;}?>></td>
                    <td class='sota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->sota == 1) {echo 'checked'; $sota++;}?>></td>
                    <td class='uscounties_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->uscounties == 1) {echo 'checked'; $uscounties++;}?>></td>
                    <td class='vucc_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->vucc == 1) {echo 'checked'; $vucc++;}?>></td>
                    <td class='was_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->was == 1) {echo 'checked'; $was++;}?>></td>
					<td class='wwff_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->wwff == 1) {echo 'checked'; $wwff++;}?>></td>
					<td><?php echo $band->bandgroup;?></td>
					<td class="frequency-cell"><?php echo $band->ssb;?></td>
					<td class="frequency-cell"><?php echo $band->data;?></td>
					<td class="frequency-cell"><?php echo $band->cw;?></td>
					<?php if($this->session->userdata('user_type') == '99') { ?>
					<td>
						<a href="javascript:editBandDialog('<?php echo $band->bandid ?>');" class="btn btn-outline-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
					</td>
					<td>
						<a href="javascript:deleteBand('<?php echo $band->id . '\',\'' . $band->band ?>');" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></a>
                    </td>
					<?php } ?>
				</tr>

				<?php } ?>
			</tbody>
			<tfoot>
					<th>Toggle All</th>
					<th></th>
					<th class="master_cq"><input type="checkbox" <?php if ($cq > 0) echo 'checked';?>></th>
					<th class="master_dok"><input type="checkbox" <?php if ($dok > 0) echo 'checked';?>></th>
					<th class="master_dxcc"><input type="checkbox" <?php if ($dxcc > 0) echo 'checked';?>></th>
					<th class="master_iota"><input type="checkbox" <?php if ($iota > 0) echo 'checked';?>></th>
					<th class="master_pota"><input type="checkbox" <?php if ($pota > 0) echo 'checked';?>></th>
					<th class="master_sig"><input type="checkbox" <?php if ($sig > 0) echo 'checked';?>></th>
					<th class="master_sota"><input type="checkbox" <?php if ($sota > 0) echo 'checked';?>></th>
					<th class="master_uscounties"><input type="checkbox" <?php if ($uscounties > 0) echo 'checked';?>></th>
					<th class="master_vucc"><input type="checkbox" <?php if ($vucc > 0) echo 'checked';?>></th>
					<th class="master_was"><input type="checkbox" <?php if ($was > 0) echo 'checked';?>></th>
					<th class="master_wwff"><input type="checkbox" <?php if ($wwff > 0) echo 'checked';?>></th>
					<th></th>
					<th></th>
					<th></th>
                    <th></th>
                    <th></th>
					<th></th>
			</tfoot>
		</table>
	</div>
  <br/>
  <p>
		<?php if($this->session->userdata('user_type') == '99') { ?>
		<script>
			var lang_options_bands_edit = '<?php echo lang('options_bands_edit'); ?>';
			var lang_options_bands_create = '<?php echo lang('options_bands_create'); ?>';
			var lang_admin_close = '<?php echo lang('admin_close'); ?>';
			var lang_options_bands_delete_warning = '<?php echo lang('options_bands_delete_warning'); ?>';
			var lang_options_bands_activateall_warning = '<?php echo lang('options_bands_activateall_warning'); ?>';
			var lang_options_bands_deactivateall_warning = '<?php echo lang('options_bands_deactivateall_warning'); ?>';
		</script>
	  	<button onclick="createBandDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> <?php echo lang('options_bands_create'); ?></button>
  		<button onclick="activateAllBands();" class="btn btn-primary btn-sm"><?php echo lang('options_bands_activate_all'); ?></button>
		<button onclick="deactivateAllBands();" class="btn btn-primary btn-sm"><?php echo lang('options_bands_deactivate_all'); ?></button>
		<?php } ?>
	</p>
</div>
</div>
