<style>
	/*canvas{
	    margin: 0 auto;
    }*/

	#modeChart, #bandChart, #satChart{
		margin: 0 auto;
	}
</style>


<script>
		// General Language
		var lang_statistics_years = "<?php echo lang('statistics_years')?>";
		var lang_statistics_modes = "<?php echo lang('statistics_modes')?>";
		var lang_statistics_bands = "<?php echo lang('statistics_bands')?>";
		var lang_statistics_number_of_qso_worked_each_year = "<?php echo lang('statistics_number_of_qso_worked_each_year')?>";
		var lang_statistics_year = "<?php echo lang('statistics_year')?>";
		var lang_statistics_number_of_qso_worked = "<?php echo lang('statistics_number_of_qso_worked')?>";
		var lang_gen_hamradio_mode = "<?php echo lang('gen_hamradio_mode')?>";
		var lang_gen_hamradio_band = "<?php echo lang('gen_hamradio_band')?>";
</script>

<div class="container statistics">

	<h2>
		<?php echo $page_title; ?>
		<small class="text-muted"><?php echo lang('statistics_explore_the_logbook'); ?></small>
	</h2>

	<br>
	<div hidden class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">General</a>
			</li>
			<?php if ($sat_active) { ?>
			<li class="nav-item">
				<a class="nav-link" id="satellite-tab" data-bs-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false">Satellites</a>
			</li>
			<?php } ?>
		</ul>
	</div>

		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<br />
					<ul class="nav nav-pills" id="myTab2" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="years-tab" data-bs-toggle="tab" href="#yearstab" role="tab" aria-controls="yearstab" aria-selected="true"><?php echo lang('statistics_years'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="mode-tab" data-bs-toggle="tab" href="#modetab" role="tab" aria-controls="modetab" aria-selected="false"><?php echo lang('statistics_modes'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="band-tab" data-bs-toggle="tab" href="#bandtab" role="tab" aria-controls="bandtab" aria-selected="false"><?php echo lang('statistics_bands'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="qso-tab" data-bs-toggle="tab" href="#qsotab" role="tab" aria-controls="bandtab" aria-selected="false"><?php echo lang('statistics_qsos'); ?></a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="unique-tab" data-bs-toggle="tab" href="#uniquetab" role="tab" aria-controls="uniquetab" aria-selected="false"><?php echo lang('statistics_unique_callsigns'); ?></a>
						</li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="yearstab" role="tabpanel" aria-labelledby="years-tab">
						<div class="years" style="margin-top: 20px;">
						</div>
					</div>
					<div class="tab-pane fade" id="modetab" role="tabpanel" aria-labelledby="mode-tab">
							<div class="mode">
							</div>
					</div>
					<div class="tab-pane fade" id="bandtab" role="tabpanel" aria-labelledby="band-tab">
							<div class="band">
							</div>
					</div>
					<div class="tab-pane fade" id="qsotab" role="tabpanel" aria-labelledby="qso-tab">
							<div class="qsos">
							</div>
					</div>
					<div class="tab-pane fade" id="uniquetab" role="tabpanel" aria-labelledby="unique-tab">
							<div class="unique">
							</div>
					</div>
				</div>
			</div>

			<div class="tab-pane fade" id="satellite" role="tabpanel" aria-labelledby="satellite-tab">
				<br/>	
				<div style="display: flex;" id="satContainer"><div style="flex: 1;"><canvas id="satChart" width="500" height="500"></canvas></div><div style="flex: 1;" id="satTable">
				
				<table style="width:100%" class="sattable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>
					<tr>
					<td>#</td>
					<td>Satellite</td>
					<td># of QSO's worked</td>
					</tr>
					</thead>
					<tbody></tbody>
				</table></div></div>
			</div>
		</div>
</div>
