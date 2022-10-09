<style>
	/*canvas{
	    margin: 0 auto;
    }*/

	#modeChart, #bandChart, #satChart{
		margin: 0 auto;
	}
</style>
<div class="container statistics">

	<h2>
		<?php echo $page_title; ?>
		<small class="text-muted">Explore the logbook.</small>
	</h2>

	<br>
	<div hidden class="tabs">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">General</a>
			</li>
			<?php if ($sat_active) { ?>
			<li class="nav-item">
				<a class="nav-link" id="satellite-tab" data-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false">Satellites</a>
			</li>
			<?php } ?>
		</ul>
	</div>

		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<br />
					<ul class="nav nav-pills" id="myTab2" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="years-tab" data-toggle="tab" href="#yearstab" role="tab" aria-controls="yearstab" aria-selected="true">Years</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="mode-tab" data-toggle="tab" href="#modetab" role="tab" aria-controls="modetab" aria-selected="false">Mode</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="band-tab" data-toggle="tab" href="#bandtab" role="tab" aria-controls="bandtab" aria-selected="false">Bands</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="qso-tab" data-toggle="tab" href="#qsotab" role="tab" aria-controls="bandtab" aria-selected="false">QSOs</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="unique-tab" data-toggle="tab" href="#uniquetab" role="tab" aria-controls="uniquetab" aria-selected="false">Unique callsigns</a>
						</li>
					</ul>
				<div class="tab-content">
					<div class="tab-pane fade show active" id="yearstab" role="tabpanel" aria-labelledby="years-tab">
						<div class="years">
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
