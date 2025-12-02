<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<style>
	/* Award table row states - must override Bootstrap table-hover */
	table.award-table.table tbody tr.saving,
	table.award-table.table tbody tr.saving > td,
	table.award-table.table tbody tr.saving:hover,
	table.award-table.table tbody tr.saving:hover > td {
		background-color: #fff3cd !important;
		transition: background-color 0.3s ease;
	}
	table.award-table.table tbody tr.saved,
	table.award-table.table tbody tr.saved > td,
	table.award-table.table tbody tr.saved:hover,
	table.award-table.table tbody tr.saved:hover > td {
		background-color: #d1e7dd !important;
		transition: background-color 0.3s ease;
	}
	table.award-table.table tbody tr.error,
	table.award-table.table tbody tr.error > td,
	table.award-table.table tbody tr.error:hover,
	table.award-table.table tbody tr.error:hover > td {
		background-color: #f8d7da !important;
		transition: background-color 0.3s ease;
	}
</style>

<h2>Award Settings</h2>

<!-- Info Card -->
<div class="card mb-3">
	<div class="card-header">
		<h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Award Preferences</h5>
	</div>
	<div class="card-body">
		<div class="alert alert-primary alert-dismissible fade show" role="alert">
			<strong><i class="fas fa-lightbulb"></i> Quick Guide:</strong> 
			Select which awards you want to see in the <strong>Awards menu</strong>. Unchecked awards will be hidden from the navigation menu.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
		
		<p class="card-text mb-2">
			Using this list you can control which awards are shown in the Awards menu.
		</p>
		<p class="card-text mb-0">
			Active awards will be shown in the menu, while inactive awards will be hidden.
		</p>
	</div>
</div>

<!-- Awards Table Card -->
<div class="card">
	<div class="card-header">
		<div class="d-flex justify-content-between align-items-center">
			<h5 class="mb-0"><i class="fas fa-table me-2"></i>Awards Configuration</h5>
		</div>
	</div>
	<div class="card-body">
		
		<div class="table-responsive">
			<table class="award-table table table-hover">
				<thead>
					<tr>
						<th class="award-checkbox-cell">Show in Menu</th>
						<th>Award Name</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class='award-checkbox-cell award_cq'>
							<input type="checkbox" data-award="cq" <?php if ($user_awards->cq == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">CQ Magazine</td>
						<td class="award-description">CQ Magazine DX Awards</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_dok'>
							<input type="checkbox" data-award="dok" <?php if ($user_awards->dok == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">DOK</td>
						<td class="award-description">Diplom Ortsverbände Kennung (German Districts)</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_dxcc'>
							<input type="checkbox" data-award="dxcc" <?php if ($user_awards->dxcc == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">DXCC</td>
						<td class="award-description">DX Century Club</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_ffma'>
							<input type="checkbox" data-award="ffma" <?php if ($user_awards->ffma == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">FFMA</td>
						<td class="award-description">Flora and Fauna in Marche Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_iota'>
							<input type="checkbox" data-award="iota" <?php if ($user_awards->iota == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">IOTA</td>
						<td class="award-description">Islands On The Air</td>
					</tr>
					<tr class="table-secondary">
						<td colspan="3"><strong><i class="fas fa-th me-2"></i>Gridmaster Awards</strong></td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_gridmaster_dl'>
							<input type="checkbox" data-award="gridmaster_dl" <?php if ($user_awards->gridmaster_dl == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">Gridmaster DL</td>
						<td class="award-description">German Gridmaster Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_gridmaster_lx'>
							<input type="checkbox" data-award="gridmaster_lx" <?php if ($user_awards->gridmaster_lx == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">Gridmaster LX</td>
						<td class="award-description">Luxembourg Gridmaster Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_gridmaster_ja'>
							<input type="checkbox" data-award="gridmaster_ja" <?php if ($user_awards->gridmaster_ja == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">Gridmaster JA</td>
						<td class="award-description">Japanese Gridmaster Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_gridmaster_us'>
							<input type="checkbox" data-award="gridmaster_us" <?php if ($user_awards->gridmaster_us == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">Gridmaster US</td>
						<td class="award-description">United States Gridmaster Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_gridmaster_uk'>
							<input type="checkbox" data-award="gridmaster_uk" <?php if ($user_awards->gridmaster_uk == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">Gridmaster UK</td>
						<td class="award-description">United Kingdom Gridmaster Award</td>
					</tr>
					<tr class="table-secondary">
						<td colspan="3"></td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_gmdxsummer'>
							<input type="checkbox" data-award="gmdxsummer" <?php if ($user_awards->gmdxsummer == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">GMDX Summer Challenge</td>
						<td class="award-description">GMDX Summer Challenge Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_pota'>
							<input type="checkbox" data-award="pota" <?php if ($user_awards->pota == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">POTA</td>
						<td class="award-description">Parks on the Air</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_sig'>
							<input type="checkbox" data-award="sig" <?php if ($user_awards->sig == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">SIG</td>
						<td class="award-description">Special Interest Group</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_sota'>
							<input type="checkbox" data-award="sota" <?php if ($user_awards->sota == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">SOTA</td>
						<td class="award-description">Summits on the Air</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_uscounties'>
							<input type="checkbox" data-award="uscounties" <?php if ($user_awards->uscounties == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">US Counties</td>
						<td class="award-description">United States Counties Award</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_vucc'>
							<input type="checkbox" data-award="vucc" <?php if ($user_awards->vucc == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">VUCC</td>
						<td class="award-description">VHF/UHF Century Club</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_wab'>
							<input type="checkbox" data-award="wab" <?php if ($user_awards->wab == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">WAB</td>
						<td class="award-description">Worked All Britain</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_waja'>
							<input type="checkbox" data-award="waja" <?php if ($user_awards->waja == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">WAJA</td>
						<td class="award-description">Worked All Japan</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_was'>
							<input type="checkbox" data-award="was" <?php if ($user_awards->was == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">WAS</td>
						<td class="award-description">Worked All States</td>
					</tr>
					<tr>
						<td class='award-checkbox-cell award_wwff'>
							<input type="checkbox" data-award="wwff" <?php if ($user_awards->wwff == 1) echo 'checked'; ?>>
						</td>
						<td class="award-name">WWFF</td>
						<td class="award-description">World Wide Flora and Fauna</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
		<p>
			<button onclick="activateAllAwards();" class="btn btn-success btn-sm">
				<i class="fas fa-check-square"></i> Show All Awards
			</button>
			<button onclick="deactivateAllAwards();" class="btn btn-warning btn-sm">
				<i class="fas fa-minus-square"></i> Hide All Awards
			</button>
		</p>
	</div>
</div>

</div>
