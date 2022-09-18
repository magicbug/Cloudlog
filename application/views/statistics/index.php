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
		<li class="nav-item">
			<a class="nav-link" id="satellite-tab" data-toggle="tab" href="#satellite" role="tab" aria-controls="satellite" aria-selected="false">Satellites</a>
		</li>
	</ul>
	</div>

	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
			<div class="years">
			</div>
			<div class="mode">
			</div>
			<div class="band">
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
